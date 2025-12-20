<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('web.forgot-password');
    }

    /**
     * Step 1: Check phone and send OTP for password reset
     */
    public function checkPhoneForReset(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Find user by phone number and ensure they are a student
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return back()->withErrors(['phone' => __('front.phone_not_found_or_not_student')])->withInput();
            }

            // Check if account is activated
            if ($user->activate != 1) {
                return back()->withErrors(['phone' => __('front.account_deactivated')])->withInput();
            }

            // Generate and send OTP
            $result = $this->otpService->generateAndSendOtp($request->phone);

            if (!$result['success']) {
                return back()->withErrors(['phone' => __('front.failed_to_send_otp')])->withInput();
            }

            // Store phone in session for next step
            session(['reset_phone' => $request->phone]);

            return redirect()->route('user.forgot-password.verify-otp')
                           ->with('success', __('front.otp_sent_successfully'));

        } catch (\Exception $e) {
            return back()->withErrors(['phone' => __('front.phone_verification_failed') . ': ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOtpForm()
    {
        if (!session('reset_phone')) {
            return redirect()->route('user.forgot-password')->withErrors(['error' => __('front.session_expired')]);
        }

        return view('web.verify-otp');
    }

    /**
     * Step 2: Verify OTP for password reset
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required|string',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $phone = session('reset_phone');

            if (!$phone) {
                return redirect()->route('user.forgot-password')->withErrors(['error' => __('front.session_expired')]);
            }

            // Find user
            $user = User::where('phone', $phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return back()->withErrors(['otp' => __('front.user_not_found')]);
            }

            // Check for test OTP
            if ($this->otpService->isTestOtp($phone, $request->otp)) {
                session(['otp_verified' => true]);
                return redirect()->route('user.forgot-password.reset')
                               ->with('success', __('front.otp_verified'));
            }

            // Verify real OTP
            if (!$this->otpService->otpExists($phone)) {
                return back()->withErrors(['otp' => __('front.otp_expired')]);
            }

            if ($this->otpService->verifyOtp($phone, $request->otp)) {
                session(['otp_verified' => true]);
                return redirect()->route('user.forgot-password.reset')
                               ->with('success', __('front.otp_verified'));
            }

            return back()->withErrors(['otp' => __('front.invalid_otp')]);

        } catch (\Exception $e) {
            return back()->withErrors(['otp' => __('front.otp_verification_failed') . ': ' . $e->getMessage()]);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm()
    {
        if (!session('reset_phone') || !session('otp_verified')) {
            return redirect()->route('user.forgot-password')->withErrors(['error' => __('front.session_expired')]);
        }

        return view('web.reset-password');
    }

    /**
     * Step 3: Reset password using phone number (after OTP verification)
     */
    public function resetPassword(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $phone = session('reset_phone');

            if (!$phone || !session('otp_verified')) {
                return redirect()->route('user.forgot-password')->withErrors(['error' => __('front.session_expired')]);
            }

            // Find user by phone number and ensure they are a student
            $user = User::where('phone', $phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return back()->withErrors(['password' => __('front.user_not_found')]);
            }

            // Check if account is activated
            if ($user->activate != 1) {
                return back()->withErrors(['password' => __('front.account_deactivated')]);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->password),
                'ip_address' => $request->ip()
            ]);

            // Revoke all existing tokens for security
            $user->tokens->each(function ($token, $key) {
                $token->revoke();
            });

            // Clear session
            session()->forget(['reset_phone', 'otp_verified']);

            return redirect()->route('user.login')
                           ->with('success', __('front.password_reset_success'));

        } catch (\Exception $e) {
            return back()->withErrors(['password' => __('front.password_reset_failed') . ': ' . $e->getMessage()]);
        }
    }

    /**
     * Resend OTP for password reset
     */
    public function resendOtp(Request $request)
    {
        try {
            $phone = session('reset_phone');

            if (!$phone) {
                return redirect()->route('user.forgot-password')->withErrors(['error' => __('front.session_expired')]);
            }

            // Check if user exists
            $user = User::where('phone', $phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return back()->withErrors(['error' => __('front.user_not_found')]);
            }

            // Check if account is activated
            if ($user->activate != 1) {
                return back()->withErrors(['error' => __('front.account_deactivated')]);
            }

            // Send OTP
            $result = $this->otpService->generateAndSendOtp($phone);

            if ($result['success']) {
                return back()->with('success', __('front.otp_sent_successfully'));
            }

            return back()->withErrors(['error' => __('front.failed_to_send_otp')]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('front.failed_to_resend_otp') . ': ' . $e->getMessage()]);
        }
    }
}