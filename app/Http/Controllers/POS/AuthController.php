<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\POS;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('pos.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Try to find POS by phone or email
        $pos = \App\Models\POS::where('phone', $login)
            ->orWhere('email', $login)
            ->first();

        if ($pos && Hash::check($password, $pos->password)) {
            Auth::guard('pos')->login($pos);
            $request->session()->regenerate();
            return redirect()->route('pos.dashboard');
        }

        return back()->withErrors([
            'login' => 'بيانات الدخول غير صحيحة',
        ])->onlyInput('login');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('pos')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pos.login');
    }

    /**
     * Show edit profile form
     */
    public function showEdit()
    {
        $pos = Auth::guard('pos')->user();
        return view('pos.settings.edit', compact('pos'));
    }

    /**
     * Update POS profile
     */
    public function updateProfile(Request $request)
    {
        $pos = Auth::guard('pos')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:p_o_s,phone,' . $pos->id,
            'email' => 'required|email|max:255|unique:p_o_s,email,' . $pos->id,
            'address' => 'required|string|max:255',
            'country_name' => 'required|string|max:255',
            'google_map_link' => 'nullable|string|max:500',
            'current_password' => 'required|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $pos->password)) {
            return back()->withErrors([
                'current_password' => 'كلمة المرور الحالية غير صحيحة',
            ]);
        }

        // Update basic info
        $pos->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'country_name' => $validated['country_name'],
            'google_map_link' => $validated['google_map_link'],
        ]);

        // Update password if provided
        if (!empty($validated['new_password'])) {
            $pos->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        }

        return back()->with('success', 'تم تحديث البيانات بنجاح');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('pos.auth.forgot-password');
    }

    /**
     * Send password reset email
     */
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:p_o_s,email',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.exists' => 'البريد الإلكتروني غير مسجل',
        ]);

        $pos = POS::where('email', $request->email)->first();

        if (!$pos) {
            return back()->withErrors([
                'email' => 'البريد الإلكتروني غير مسجل',
            ]);
        }

        // Generate reset token
        $resetToken = Str::random(64);
        $pos->update([
            'reset_token' => $resetToken,
            'reset_token_expires_at' => now()->addHours(1),
        ]);

        // Send reset email as plain text
        $resetUrl = route('pos.reset-password', ['token' => $resetToken]);
        $emailBody = "مرحباً بك،\n\n";
        $emailBody .= "تم طلب إعادة تعيين كلمة المرور لحسابك.\n\n";
        $emailBody .= "اضغط على الرابط أدناه لإعادة تعيين كلمة المرور:\n";
        $emailBody .= $resetUrl . "\n\n";
        $emailBody .= "سيكون هذا الرابط صالحاً لمدة ساعة واحدة.\n\n";
        $emailBody .= "إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد.\n\n";
        $emailBody .= "شكراً\n";

        \Mail::raw($emailBody, function ($message) use ($pos) {
            $message->to($pos->email)
                    ->subject('إعادة تعيين كلمة المرور');
        });

        return back()->with('success', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
    }

    /**
     * Show reset password form
     */
    public function showResetPassword($token)
    {
        $pos = POS::where('reset_token', $token)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$pos) {
            return redirect()->route('pos.login')
                ->withErrors(['token' => 'رابط إعادة التعيين غير صحيح أو منتهي الصلاحية']);
        }

        return view('pos.auth.reset-password', compact('token', 'pos'));
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        $pos = POS::where('reset_token', $request->token)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$pos) {
            return redirect()->route('pos.login')
                ->withErrors(['token' => 'رابط إعادة التعيين غير صحيح أو منتهي الصلاحية']);
        }

        // Update password and clear reset token
        $pos->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_expires_at' => null,
        ]);

        return redirect()->route('pos.login')
            ->with('success', 'تم تحديث كلمة المرور بنجاح. يرجى تسجيل الدخول');
    }
}
