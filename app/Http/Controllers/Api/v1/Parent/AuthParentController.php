<?php

namespace App\Http\Controllers\Api\v1\Parent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Parentt;
use App\Models\ParentStudent;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthParentController extends Controller
{
    use Responses;


    /**
     * Register a new parent
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
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                $photoPath = uploadImage('assets/admin/uploads', $photo);
            }

            // Generate referral code
            $referralCode = 'PAR_' . strtoupper(Str::random(8));
            while (User::where('referal_code', $referralCode)->exists()) {
                $referralCode = 'PAR_' . strtoupper(Str::random(8));
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_name' => 'parent',
                'photo' => $photoPath,
                'fcm_token' => $request->fcm_token,
                'ip_address' => $request->ip(),
                'referal_code' => $referralCode,
                'activate' => 1,
                'balance' => 0
            ]);

            // Create parent profile
            $parent = Parentt::create([
                'name' => $request->name,
                'user_id' => $user->id
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
                    'activate' => $user->activate
                ],
                'parent_profile' => [
                    'id' => $parent->id,
                    'name' => $parent->name
                ],
                'children' => [],
                'children_count' => 0,
                'token' => $token,
            ];

            return $this->success_response('Parent registered successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Registration failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Login parent
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

            // Find user by email or phone and ensure they are a parent
            $user = User::where($fieldType, $loginField)
                       ->where('role_name', 'parent')
                       ->with(['parentt', 'parentt.students'])
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
                'last_login' => now()
            ]);

            // Delete old tokens and create new one
            $user->tokens()->delete();
            $tokenResult = $user->createToken('auth_token');
            $token = $tokenResult->accessToken;

            // Get children list
            $children = [];
            if ($user->parentt && $user->parentt->students) {
              $children = $user->parentt->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'phone' => $student->phone,
                        'photo' => $student->photo ? asset('assets/admin/uploads/' . $student->photo) : null,
                        'class_id' => $student->clas_id,
                        'balance' => $student->balance,
                        'activate' => $student->activate,
                        'added_at' => $student->pivot->created_at ?? null, // from pivot
                    ];
                });
            }

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
                    'activate' => $user->activate,
                    'last_login' => $user->last_login
                ],
                'parent_profile' => $user->parentt ? [
                    'id' => $user->parentt->id,
                    'name' => $user->parentt->name
                ] : null,
                'children' => $children,
                'children_count' => count($children),
                'token' => $token,
            ];

            return $this->success_response('Login successful', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Login failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get parent profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $user->load(['parentt', 'parentt.students']);

            // Get children list
            $children = [];
            if ($user->parentt && $user->parentt->students) {
              $children = $user->parentt->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'phone' => $student->phone,
                        'photo' => $student->photo ? asset('assets/admin/uploads/' . $student->photo) : null,
                        'class_id' => $student->clas_id,
                        'balance' => $student->balance,
                        'activate' => $student->activate,
                        'added_at' => $student->pivot->created_at ?? null, // from pivot
                    ];
                });
            }

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
                    'activate' => $user->activate,
                    'last_login' => $user->last_login,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ],
                'parent_profile' => $user->parentt ? [
                    'id' => $user->parentt->id,
                    'name' => $user->parentt->name
                ] : null,
                'children' => $children,
                'children_count' => count($children)
            ];

            return $this->success_response('Profile retrieved successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve profile: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update parent profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id,
                'phone' => 'sometimes|nullable|string|unique:users,phone,' . $user->id,
                'password' => 'sometimes|nullable|string|min:4|confirmed',
                'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'fcm_token' => 'sometimes|nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            $updateUserData = [];
            $updateParentData = [];

            // Update user data
            if ($request->has('name')) {
                $updateUserData['name'] = $request->name;
                $updateParentData['name'] = $request->name;
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
            }

            // Update user
            if (!empty($updateUserData)) {
                $user->update($updateUserData);
            }

            // Update or create parent profile
            if (!empty($updateParentData)) {
                $user->parentt()->updateOrCreate(
                    ['user_id' => $user->id],
                    $updateParentData
                );
            }

            $user->refresh();
            $user->load('parentt');

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
                    'activate' => $user->activate,
                    'updated_at' => $user->updated_at
                ],
                'parent_profile' => $user->parentt ? [
                    'id' => $user->parentt->id,
                    'name' => $user->parentt->name
                ] : null
            ];

            return $this->success_response('Profile updated successfully', $userData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update profile: ' . $e->getMessage(), null);
        }
    }

    /**
     * Search for student by phone number
     */
    public function searchStudent(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $validator = Validator::make($request->all(), [
                'phone' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            // Find student by phone
            $student = User::where('phone', $request->phone)
                          ->where('role_name', 'student')
                          ->where('activate', 1)
                          ->first();

            if (!$student) {
                return $this->error_response('No active student found with this phone number', null);
            }

            // Check if student is already added by this parent
            $parentProfile = $user->parent;
            if (!$parentProfile) {
                // Create parent profile if doesn't exist
                $parentProfile = Parentt::create([
                    'name' => $user->name,
                    'user_id' => $user->id
                ]);
            }

            $existingRelation = ParentStudent::where('parentt_id', $parentProfile->id)
                                           ->where('user_id', $student->id)
                                           ->first();

            if ($existingRelation) {
                return $this->error_response('This student is already added to your children list', null);
            }

            $studentData = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'photo' => $student->photo ? asset('assets/admin/uploads/' . $student->photo) : null,
                'class_id' => $student->clas_id,
                'balance' => $student->balance,
                'activate' => $student->activate
            ];

            return $this->success_response('Student found successfully', $studentData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to search student: ' . $e->getMessage(), null);
        }
    }

    /**
     * Add student as child
     */
    public function addChild(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            // Verify the student exists and is active
            $student = User::where('id', $request->student_id)
                          ->where('role_name', 'student')
                          ->where('activate', 1)
                          ->first();

            if (!$student) {
                return $this->error_response('Invalid or inactive student', null);
            }

            // Get or create parent profile
            $parentProfile = $user->parent;
            if (!$parentProfile) {
                $parentProfile = Parentt::create([
                    'name' => $user->name,
                    'user_id' => $user->id
                ]);
            }

            // Check if student is already added
            $existingRelation = ParentStudent::where('parentt_id', $parentProfile->id)
                                           ->where('user_id', $student->id)
                                           ->first();

            if ($existingRelation) {
                return $this->error_response('This student is already added to your children list', null);
            }

            // Add student as child
            $parentStudent = ParentStudent::create([
                'parentt_id' => $parentProfile->id,
                'user_id' => $student->id
            ]);

            $childData = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'photo' => $student->photo ? asset('assets/admin/uploads/' . $student->photo) : null,
                'class_id' => $student->clas_id,
                'balance' => $student->balance,
                'activate' => $student->activate,
                'added_at' => $parentStudent->created_at
            ];

            return $this->success_response('Child added successfully', $childData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to add child: ' . $e->getMessage(), null);
        }
    }

    /**
     * Remove child
     */
    public function removeChild(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }

            $parentProfile = $user->parent;
            if (!$parentProfile) {
                return $this->error_response('Parent profile not found', null);
            }

            // Find and remove the relation
            $parentStudent = ParentStudent::where('parentt_id', $parentProfile->id)
                                        ->where('user_id', $request->student_id)
                                        ->first();

            if (!$parentStudent) {
                return $this->error_response('This student is not in your children list', null);
            }

            $removedChild = [
                'id' => $parentStudent->user_id,
                'removed_at' => now()
            ];

            $parentStudent->delete();

            return $this->success_response('Child removed successfully', $removedChild);

        } catch (\Exception $e) {
            return $this->error_response('Failed to remove child: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get children list
     */
    public function getChildren(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $parentProfile = $user->parentt;
            if (!$parentProfile) {
                return $this->success_response('Children retrieved successfully', [
                    'children' => [],
                    'children_count' => 0,
                    'debug' => 'No parent profile found for user ID: ' . $user->id
                ]);
            }

            // Method 1: Using ParentStudent model (recommended)
            $parentStudents = ParentStudent::getByParent($parentProfile->id);

            $children = $parentStudents->filter(function($parentStudent) {
                return $parentStudent->student !== null;
            })->map(function ($parentStudent) {
                $student = $parentStudent->student;
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'phone' => $student->phone,
                    'photo' => $student->photo ? asset('assets/admin/uploads/' . $student->photo) : null,
                    'class_id' => $student->clas_id,
                    'balance' => $student->balance,
                    'activate' => $student->activate,
                    'added_at' => $parentStudent->created_at
                ];
            })->values();

            $childrenData = [
                'children' => $children,
                'children_count' => $children->count()
            ];

            return $this->success_response('Children retrieved successfully', $childrenData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve children: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get specific child details
     */
    // public function getChild(Request $request, $childId)
    // {
    //     try {
    //         $user = $request->user();

    //         if ($user->role_name !== 'parent') {
    //             return $this->error_response('Access denied. Parents only.', null);
    //         }

    //         $parentProfile = $user->parent;
    //         if (!$parentProfile) {
    //             return $this->error_response('Parent profile not found', null);
    //         }

    //         // Verify this child belongs to this parent
    //         $parentStudent = ParentStudent::where('parentt_id', $parentProfile->id)
    //                                     ->where('user_id', $childId)
    //                                     ->with('user')
    //                                     ->first();

    //         if (!$parentStudent) {
    //             return $this->error_response('Child not found or not associated with your account', null);
    //         }

    //         $childData = [
    //             'id' => $parentStudent->user->id,
    //             'name' => $parentStudent->user->name,
    //             'email' => $parentStudent->user->email,
    //             'phone' => $parentStudent->user->phone,
    //             'photo' => $parentStudent->user->photo ? asset('assets/admin/uploads/' . $parentStudent->user->photo) : null,
    //             'class_id' => $parentStudent->user->clas_id,
    //             'balance' => $parentStudent->user->balance,
    //             'activate' => $parentStudent->user->activate,
    //             'added_at' => $parentStudent->created_at,
    //             'last_login' => $parentStudent->user->last_login
    //         ];

    //         return $this->success_response('Child details retrieved successfully', $childData);

    //     } catch (\Exception $e) {
    //         return $this->error_response('Failed to retrieve child details: ' . $e->getMessage(), null);
    //     }
    // }


    /**
     * Logout parent
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            // Delete current token
            $request->user()->currentAccessToken()->delete();

            return $this->success_response('Logout successful', null);

        } catch (\Exception $e) {
            return $this->error_response('Logout failed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Delete parent account
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
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

            // Delete parent profile and user account (cascade will handle relations)
            $user->delete();

            return $this->success_response('Account deleted successfully', $deletedUserInfo);

        } catch (\Exception $e) {
            return $this->error_response('Failed to delete account: ' . $e->getMessage(), null);
        }
    }

    /**
     * Forgot password
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

            // Find user by phone number and ensure they are a parent
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'parent')
                       ->first();

            if (!$user) {
                return $this->error_response('Phone number not found or not associated with a parent account', null);
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

            // Find user by phone number and ensure they are a parent
            $user = User::where('phone', $request->phone)
                       ->where('role_name', 'parent')
                       ->first();

            if (!$user) {
                return $this->error_response('Phone number not found or not associated with a parent account', null);
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
     * Get parent statistics/dashboard data
     */
    public function getStatistics(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $parentProfile = $user->parent;
            if (!$parentProfile) {
                return $this->success_response('Statistics retrieved successfully', [
                    'total_children' => 0,
                    'active_children' => 0,
                    'total_courses_enrolled' => 0,
                    'pending_payments' => 0
                ]);
            }

            $children = $parentProfile->students()->with('user')->get();
            $totalChildren = $children->count();
            $activeChildren = $children->where('user.activate', 1)->count();

            // You can add more statistics based on your system
            $statistics = [
                'total_children' => $totalChildren,
                'active_children' => $activeChildren,
                'inactive_children' => $totalChildren - $activeChildren,
                'total_balance' => $children->sum('user.balance'),
                'average_balance' => $totalChildren > 0 ? $children->avg('user.balance') : 0,
                'children_by_class' => $children->groupBy('user.clas_id')->map(function ($group) {
                    return $group->count();
                })
            ];

            return $this->success_response('Statistics retrieved successfully', $statistics);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve statistics: ' . $e->getMessage(), null);
        }
    }

}
