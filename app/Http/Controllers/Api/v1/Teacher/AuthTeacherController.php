<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\OtpService;
use Exception;

class AuthTeacherController extends Controller
{
    use Responses;

    
   protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }


    /**
     * Login teacher
     */
    public function login(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'login' => 'required|string', // can be email or phone
                'password' => 'required|string',
                'fcm_token' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            $loginField = $request->login;
            $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            // Find user by email or phone and ensure they are a teacher
            $user = User::where($fieldType, $loginField)
                       ->where('role_name', 'teacher')
                       ->with('teacher')
                       ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error_response('Invalid credentials', null);
            }

            // Check if account is activated
            if ($user->activate != 1) {
                return $this->error_response('Account is deactivated. Please contact support.', null);
            }

            // Update FCM token and login info
            $user->update([
                'fcm_token' => $request->fcm_token ?? $user->fcm_token,
                'ip_address' => $request->ip(),
                'last_login' => now()
            ]);

            // Delete old tokens and create new one
            $user->tokens()->delete();
            $tokenResult = $user->createToken('auth_token');
            $token = $tokenResult->accessToken;

            $userData = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role_name' => $user->role_name,
                    'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/Profile-picture.jpg'),
                    'balance' => $user->balance,
                    'referal_code' => $user->referal_code,
                    'activate' => $user->activate,
                    'last_login' => $user->last_login
                ],
                'teacher_profile' => $user->teacher ? [
                    'id' => $user->teacher->id,
                    'name_of_lesson' => $user->teacher->name_of_lesson,
                    'description_en' => $user->teacher->description_en,
                    'description_ar' => $user->teacher->description_ar,
                    'facebook' => $user->teacher->facebook,
                    'instagram' => $user->teacher->instagram,
                    'youtube' => $user->teacher->youtube,
                    'whataspp' => $user->teacher->whataspp
                ] : null,
                'token' => $token,
            ];

            return $this->success_response('Login successful', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Login failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get teacher profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $user->load('teacher');

            $userData = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role_name' => $user->role_name,
                    'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/Profile-picture.jpg'),
                    'balance' => $user->balance,
                    'referal_code' => $user->referal_code,
                    'activate' => $user->activate,
                    'last_login' => $user->last_login,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ],
                'teacher_profile' => $user->teacher ? [
                    'id' => $user->teacher->id,
                    'name_of_lesson' => $user->teacher->name_of_lesson,
                    'description_en' => $user->teacher->description_en,
                    'description_ar' => $user->teacher->description_ar,
                    'facebook' => $user->teacher->facebook,
                    'instagram' => $user->teacher->instagram,
                    'youtube' => $user->teacher->youtube,
                    'whataspp' => $user->teacher->whataspp
                ] : null
            ];

            return $this->success_response('Profile retrieved successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve profile: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update teacher profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id,
                'phone' => 'sometimes|nullable|string|unique:users,phone,' . $user->id,
                'password' => 'sometimes|nullable|string|min:4|confirmed',
                'name_of_lesson' => 'sometimes|required|string|max:255',
                'description_en' => 'sometimes|nullable|string',
                'description_ar' => 'sometimes|nullable|string',
                'facebook' => 'sometimes|nullable|url',
                'instagram' => 'sometimes|nullable|url',
                'youtube' => 'sometimes|nullable|url',
                'whataspp' => 'sometimes|nullable|string',
                'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif',
                'fcm_token' => 'sometimes|nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            $updateUserData = [];
            $updateTeacherData = [];

            // Update user data
            if ($request->has('name')) {
                $updateUserData['name'] = $request->name;
                $updateTeacherData['name'] = $request->name;
            }
            if ($request->has('email')) {
                $updateUserData['email'] = $request->email;
            }
            if ($request->has('phone')) {
                $updateUserData['phone'] = $request->phone;
            }
            if ($request->has('fcm_token')) {
                $updateUserData['fcm_token'] = $request->fcm_token;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $updateUserData['password'] = Hash::make($request->password);
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoPath = uploadImage('assets/admin/uploads', $request->file('photo'));
                $updateUserData['photo'] = $photoPath;
                $updateTeacherData['photo'] = $photoPath;
            }

            // Update teacher specific data
            if ($request->has('name_of_lesson')) {
                $updateTeacherData['name_of_lesson'] = $request->name_of_lesson;
            }
            if ($request->has('description_en')) {
                $updateTeacherData['description_en'] = $request->description_en;
            }
            if ($request->has('description_ar')) {
                $updateTeacherData['description_ar'] = $request->description_ar;
            }
            if ($request->has('facebook')) {
                $updateTeacherData['facebook'] = $request->facebook;
            }
            if ($request->has('instagram')) {
                $updateTeacherData['instagram'] = $request->instagram;
            }
            if ($request->has('youtube')) {
                $updateTeacherData['youtube'] = $request->youtube;
            }
            if ($request->has('whataspp')) {
                $updateTeacherData['whataspp'] = $request->whataspp;
            }

            // Update user
            if (!empty($updateUserData)) {
                $user->update($updateUserData);
            }

            // Update or create teacher profile
            if (!empty($updateTeacherData)) {
                $user->teacher()->updateOrCreate(
                    ['user_id' => $user->id],
                    $updateTeacherData
                );
            }

            $user->refresh();
            $user->load('teacher');

            $userData = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role_name' => $user->role_name,
                    'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/Profile-picture.jpg'),
                    'balance' => $user->balance,
                    'referal_code' => $user->referal_code,
                    'activate' => $user->activate,
                    'updated_at' => $user->updated_at
                ],
                'teacher_profile' => $user->teacher ? [
                    'id' => $user->teacher->id,
                    'name_of_lesson' => $user->teacher->name_of_lesson,
                    'description_en' => $user->teacher->description_en,
                    'description_ar' => $user->teacher->description_ar,
                    'facebook' => $user->teacher->facebook,
                    'instagram' => $user->teacher->instagram,
                    'youtube' => $user->teacher->youtube,
                    'whataspp' => $user->teacher->whataspp
                ] : null
            ];

            return $this->success_response('Profile updated successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update profile: ' . $e->getMessage(), null);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:4|confirmed'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->error_response('Current password is incorrect', null);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return $this->success_response('Password changed successfully', null);

        } catch (\Exception $e) {
            return $this->error_response('Failed to change password: ' . $e->getMessage(), null);
        }
    }

    /**
     * Logout teacher
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Delete current token
            $request->user()->currentAccessToken()->delete();

            return $this->success_response('Logout successful', null);

        } catch (\Exception $e) {
            return $this->error_response('Logout failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Delete teacher account
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Validation for password confirmation
            $validator = Validator::make($request->all(), [
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Password confirmation required', $validator->errors());
            }

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return $this->error_response('Invalid password confirmation', null);
            }

            // Delete all user tokens
            $user->tokens()->delete();

            // Store user info before deletion
            $deletedUserInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'deleted_at' => now()
            ];

            // Delete teacher profile and user account (cascade will handle teacher)
            $user->delete();

            return $this->success_response('Account deleted successfully', $deletedUserInfo);

        } catch (\Exception $e) {
            return $this->error_response('Failed to delete account: ' . $e->getMessage(), null);
        }
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

            // Find user by phone number and ensure they are a teacher
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'teacher')
                       ->first();

            if (!$user) {
                return $this->error_response('Phone number not found or not associated with a teacher account', null);
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
                       ->where('role_name', 'teacher')
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

            // Find user by phone number and ensure they are a teacher
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'teacher')
                       ->first();

            if (!$user) {
                return $this->error_response('Phone number not found or not associated with a teacher account', null);
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
                       ->where('role_name', 'teacher')
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