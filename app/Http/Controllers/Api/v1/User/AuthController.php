<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\User;
use App\Services\OtpService;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    use Responses;

    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function getClasses()
    {
        try {
            $classes = Clas::get();
            return $this->success_response('Classes retrieved successfully', $classes);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve classes', $e->getMessage());
        }
    }

      private function formatUserData($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role_name' => $user->role_name,
            'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : null,
            'balance' => $user->balance,
            'referal_code' => $user->referal_code,
            'clas_id' => $user->clas_id,
            'activate' => $user->activate
        ];
    }


    /**
     * Register a new student
     */
     public function register(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'phone' => 'nullable|string|unique:users,phone',
                'password' => 'required|string|min:4|confirmed',
                'role_name' => 'nullable|in:student,parent',
                'clas_id' => 'nullable|exists:clas,id',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'fcm_token' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Ensure at least email or phone is provided
            if (!$request->email && !$request->phone) {
                return response()->json([
                    'status' => false,
                    'message' => 'Either email or phone is required'
                ], 422);
            }

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoPath = uploadImage('assets/admin/uploads', $photo);
            }

            // Generate referral code
            $prefix = $request->role_name === 'parent' ? 'PAR_' : 'STU_';
            $referralCode = $prefix . strtoupper(Str::random(8));
            while (User::where('referal_code', $referralCode)->exists()) {
                $referralCode = $prefix . strtoupper(Str::random(8));
            }

            // Create user (NOT activated yet)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_name' => "student",
                'clas_id' => $request->clas_id,
                'photo' => $photoPath,
                'fcm_token' => $request->fcm_token,
                'ip_address' => $request->ip(),
                'referal_code' => $referralCode,
                'activate' => 2, // User is not activated until OTP verification
                'balance' => 0
            ]);

            // Send OTP to phone if provided
            if ($request->phone) {
                $result = $this->otpService->generateAndSendOtp($request->phone);
                
                if (!$result['success']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Registration successful but failed to send OTP. Please try again.',
                        'user_id' => $user->id
                    ], 500);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully. Please verify OTP.',
                'data' => [
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'requires_otp' => (bool) $user->phone
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Step 2: Verify OTP and activate user
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for test OTP
        // if ($this->otpService->isTestOtp($request->phone, $request->otp)) {
        //     $user = User::where('phone', $request->phone)->first();
            
        //     if (!$user) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'User not found'
        //         ], 404);
        //     }

        //     // Activate user
        //     $user->update(['activate' => 1]);

        //     $token = $user->createToken('auth_token')->accessToken;

        //     return response()->json([
        //         'status' => true,
        //         'message' => 'OTP verified successfully (Test Mode)',
        //         'data' => $this->formatUserData($user),
        //         'token' => $token
        //     ], 200);
        // }

        // Verify OTP
        $user = User::where('phone', $request->phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (!$this->otpService->otpExists($request->phone)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ], 400);
        }

        if ($this->otpService->verifyOtp($request->phone, $request->otp)) {
            // Activate user
            $user->update(['activate' => 1]);

            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully',
                'data' => $this->formatUserData($user),
                'token' => $token
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP. Please try again.'
        ], 400);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->otpService->generateAndSendOtp($request->phone);

        if ($result['success']) {
            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to send OTP. Please try again.'
        ], 500);
    }

    /**
     * Login student
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

            // Find user by email or phone and ensure they are a student
            $user = User::where($fieldType, $loginField)
                       ->where('role_name', 'student')
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
                    'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : null,
                    'balance' => $user->balance,
                    'referal_code' => $user->referal_code,
                    'clas_id' => $user->clas_id,
                    'activate' => $user->activate,
                    'last_login' => $user->last_login
                ],
                'token' => $token,
            ];

            return $this->success_response('Login successful', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Login failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'student') {
                return $this->error_response('Access denied. Students only.', null);
            }

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_name' => $user->role_name,
                'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : null,
                'balance' => $user->balance,
                'referal_code' => $user->referal_code,
                'clas_id' => $user->clas_id,
                'activate' => $user->activate,
                'last_login' => $user->last_login,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];

            return $this->success_response('Profile retrieved successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve profile: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'student') {
                return $this->error_response('Access denied. Students only.', null);
            }

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id,
                'phone' => 'sometimes|nullable|string|unique:users,phone,' . $user->id,
                'password' => 'sometimes|nullable|string|min:4|confirmed',
                'clas_id' => 'sometimes|nullable|exists:clas,id',
                'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif',
                'fcm_token' => 'sometimes|nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            $updateData = [];

            // Update basic info
            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }
            if ($request->has('email')) {
                $updateData['email'] = $request->email;
            }
            if ($request->has('phone')) {
                $updateData['phone'] = $request->phone;
            }
            if ($request->has('clas_id')) {
                $updateData['clas_id'] = $request->clas_id;
            }
            if ($request->has('fcm_token')) {
                $updateData['fcm_token'] = $request->fcm_token;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
              $photoPath =  uploadImage('assets/admin/uploads',$request->hasFile('photo'));
                $updateData['photo'] = $photoPath;
            }

            // Update user
            $user->update($updateData);
            $user->refresh();

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_name' => $user->role_name,
                'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : null,
                'balance' => $user->balance,
                'referal_code' => $user->referal_code,
                'clas_id' => $user->clas_id,
                'activate' => $user->activate,
                'updated_at' => $user->updated_at
            ];

            return $this->success_response('Profile updated successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update profile: ' . $e->getMessage(), null);
        }
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'student') {
                return $this->error_response('Access denied. Students only.', null);
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

            // Delete user account
            $user->delete();

            return $this->success_response('Account deleted successfully', $deletedUserInfo);

        } catch (\Exception $e) {
            return $this->error_response('Failed to delete account: ' . $e->getMessage(), null);
        }
    }


}

