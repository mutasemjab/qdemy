<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Traits\Responses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use Responses;

    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
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
                return $this->error_response('Validation failed', $validator->errors());
            }

            // Find user by phone number and ensure they are a student
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return $this->error_response('Phone number not found or not associated with a student account', null);
            }

            // Check if account is activated
            if ($user->activate != 1) {
                return $this->error_response('Account is deactivated. Please contact support.', null);
            }

            // Generate and send OTP
            $result = $this->otpService->generateAndSendOtp($request->phone);

            if (!$result['success']) {
                return $this->error_response('Failed to send OTP. Please try again.', null);
            }

            $userData = [
                'phone_exists' => true,
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'can_reset' => true,
                'otp_sent' => true,
                'message' => 'OTP sent successfully. Please verify to reset password.'
            ];

            return $this->success_response('Phone number verified and OTP sent successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Phone verification failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Step 2: Verify OTP for password reset
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
                'otp' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            // Find user
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return $this->error_response('User not found', null);
            }

            // Check for test OTP
            if ($this->otpService->isTestOtp($request->phone, $request->otp)) {
                return $this->success_response(
                    'OTP verified successfully. You can now reset your password.',
                    [
                        'verified' => true,
                        'user_id' => $user->id,
                        'phone' => $user->phone
                    ]
                );
            }

            // Verify real OTP
            if (!$this->otpService->otpExists($request->phone)) {
                return $this->error_response('OTP has expired. Please request a new one.', null);
            }

            if ($this->otpService->verifyOtp($request->phone, $request->otp)) {
                return $this->success_response(
                    'OTP verified successfully. You can now reset your password.',
                    [
                        'verified' => true,
                        'user_id' => $user->id,
                        'phone' => $user->phone
                    ]
                );
            }

            return $this->error_response('Invalid OTP. Please try again.', null);

        } catch (\Exception $e) {
            return $this->error_response('OTP verification failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Step 3: Reset password using phone number (after OTP verification)
     */
    public function resetPassword(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            // Find user by phone number and ensure they are a student
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return $this->error_response('Phone number not found or not associated with a student account', null);
            }

            // Check if account is activated
            if ($user->activate != 1) {
                return $this->error_response('Account is deactivated. Please contact support.', null);
            }


            // Update password
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Revoke all existing tokens for security
            $user->tokens->each(function ($token, $key) {
                $token->revoke();
            });

            $userData = [
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'password_reset_at' => now(),
                'message' => 'Password has been reset successfully. Please login with your new password.'
            ];

            return $this->success_response('Password reset successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Password reset failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Resend OTP for password reset
     */
    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            // Check if user exists
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'student')
                       ->first();

            if (!$user) {
                return $this->error_response('User not found', null);
            }

            // Check if account is activated
            if ($user->activate != 1) {
                return $this->error_response('Account is deactivated. Please contact support.', null);
            }

            // Send OTP
            $result = $this->otpService->generateAndSendOtp($request->phone);

            if ($result['success']) {
                return $this->success_response('OTP sent successfully', null);
            }

            return $this->error_response('Failed to send OTP. Please try again.', null);

        } catch (\Exception $e) {
            return $this->error_response('Failed to resend OTP: ' . $e->getMessage(), null);
        }
    }
}