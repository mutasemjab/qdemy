<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    public function getClasses()
    {
        try {
            $classes = collect(range(1, 12))->map(function ($grade) {
                return [
                    'id' => $grade,
                    'name_ar' => "الصف " . $this->getArabicGradeName($grade),
                    'name_en' => $this->getEnglishGradeName($grade),
                ];
            });

            return $this->success_response('Classes retrieved successfully', $classes);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve classes', $e->getMessage());
        }
    }

    /**
     * Helper for Arabic names
     */
    private function getArabicGradeName($grade)
    {
        $names = [
            1 => 'الأول',
            2 => 'الثاني',
            3 => 'الثالث',
            4 => 'الرابع',
            5 => 'الخامس',
            6 => 'السادس',
            7 => 'السابع',
            8 => 'الثامن',
            9 => 'التاسع',
            10 => 'العاشر',
            11 => 'الحادي عشر',
            12 => 'الثاني عشر',
        ];

        return $names[$grade] ?? $grade;
    }

    /**
     * Helper for English names
     */
    private function getEnglishGradeName($grade)
    {
        $names = [
            1 => 'First Grade',
            2 => 'Second Grade',
            3 => 'Third Grade',
            4 => 'Fourth Grade',
            5 => 'Fifth Grade',
            6 => 'Sixth Grade',
            7 => 'Seventh Grade',
            8 => 'Eighth Grade',
            9 => 'Ninth Grade',
            10 => 'Tenth Grade',
            11 => 'Eleventh Grade',
            12 => 'Twelfth Grade',
        ];

        return $names[$grade] ?? "Grade {$grade}";
    }


    /**
     * Register a new student
     */
    public function register(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'phone' => 'nullable|string|unique:users,phone',
                'password' => 'required|string|min:4|confirmed',
                'clas_id' => 'nullable|exists:clas,id',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'fcm_token' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            // Ensure at least email or phone is provided
            if (!$request->email && !$request->phone) {
                return $this->error_response('Either email or phone is required', null);
            }

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
               $photoPath =  uploadImage('assets/admin/uploads', $photo);
            }

            // Generate referral code
            $referralCode = 'STU_' . strtoupper(Str::random(8));
            while (User::where('referal_code', $referralCode)->exists()) {
                $referralCode = 'STU_' . strtoupper(Str::random(8));
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_name' => 'student',
                'clas_id' => $request->clas_id,
                'photo' => $photoPath,
                'fcm_token' => $request->fcm_token,
                'ip_address' => $request->ip(),
                'referal_code' => $referralCode,
                'activate' => 1,
                'balance' => 0
            ]);

            // Create token
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
                    'activate' => $user->activate
                ],
                'token' => $token,
            ];

            return $this->success_response('Student registered successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Registration failed: ' . $e->getMessage(), null);
        }
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

