<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{

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

            $userData = [
                'phone_exists' => true,
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'can_reset' => true,
                'message' => 'Phone number verified. You can proceed to reset password.'
            ];

            return $this->success_response('Phone number verified successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Phone verification failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Reset password using phone number
     */
    public function resetPassword(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
                'password' => 'required|string|min:6|confirmed'
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
                'ip_address' => $request->ip()
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

}
