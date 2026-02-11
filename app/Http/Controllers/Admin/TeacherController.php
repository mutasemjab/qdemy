<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:teacher-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:teacher-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:teacher-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:teacher-delete', ['only' => ['destroy']]);
    }
   

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_of_lesson', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%")
                               ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by lesson
        if ($request->filled('lesson')) {
            $query->where('name_of_lesson', 'like', "%{$request->lesson}%");
        }

        // Filter by has user account
        if ($request->filled('has_user')) {
            if ($request->has_user == 'yes') {
                $query->whereNotNull('user_id');
            } elseif ($request->has_user == 'no') {
                $query->whereNull('user_id');
            }
        }

        $teachers = $query->paginate(15);

        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.teachers.create',);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_of_lesson' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'whataspp' => 'nullable|url',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // User creation fields
            'create_user_account' => 'boolean',
            'email' => 'required_if:create_user_account,1|nullable|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'password' => 'required_if:create_user_account,1|nullable|string|min:6',
        ]);

        DB::beginTransaction();
        
        try {
            $teacherData = $request->only([
                'name', 'name_of_lesson', 'description_en', 'description_ar',
                'facebook', 'instagram', 'youtube','whataspp'
            ]);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $teacherData['photo'] = uploadImage('assets/admin/uploads', $request->photo);
            }

            // Create user account if requested
            $userId = null;
            if ($request->boolean('create_user_account') && $request->filled('email')) {
                $userData = [
                    'name' => $request->name,
                    'role_name' => 'teacher',
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'activate' => 1,
                    'balance' => 0,
                    'referal_code' => Str::random(8),
                ];

                // Use same photo for user if uploaded
                if (isset($teacherData['photo'])) {
                    $userData['photo'] = $teacherData['photo'];
                }

                $user = User::create($userData);
                $userId = $user->id;
            }

            $teacherData['user_id'] = $userId;
            $teacher = Teacher::create($teacherData);

            DB::commit();

            $message = $userId 
                ? __('messages.Teacher and user account created successfully')
                : __('messages.Teacher created successfully');

            return redirect()->route('teachers.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Clean up uploaded photo if transaction failed
            if (isset($teacherData['photo'])) {
                $filePath = base_path('assets/admin/uploads/' . $teacherData['photo']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return back()->withInput()->with('error', __('messages.An error occurred while creating the teacher'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_of_lesson' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'whataspp' => 'nullable|url',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // User update fields
            'update_user_info' => 'boolean',
            'email' => 'nullable|email|unique:users,email,' . ($teacher->user_id ?? 'NULL'),
            'phone' => 'nullable|string|unique:users,phone,' . ($teacher->user_id ?? 'NULL'),
            'password' => 'nullable|string|min:6',
            'activate' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $teacherData = $request->only([
                'name', 'name_of_lesson', 'description_en', 'description_ar',
                'facebook', 'instagram', 'youtube','whataspp'
            ]);

            // Handle photo upload
            if ($request->hasFile('photo')) {
               if ($teacher->photo) {
                $filePath = base_path('assets/admin/uploads/' . $teacher->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                }
                $teacherData['photo'] = uploadImage('assets/admin/uploads', $request->photo);
            }

            // Update associated user if exists and update is requested
            if ($teacher->user && $request->boolean('update_user_info')) {
                $userUpdateData = [
                    'name' => $request->name,
                ];

                if ($request->filled('email')) {
                    $userUpdateData['email'] = $request->email;
                }

                if ($request->filled('phone')) {
                    $userUpdateData['phone'] = $request->phone;
                }

                if ($request->filled('activate')) {
                    $userUpdateData['activate'] = $request->activate;
                }

                if ($request->filled('password')) {
                    $userUpdateData['password'] = Hash::make($request->password);
                }



                // Update user photo if teacher photo was updated
                if (isset($teacherData['photo'])) {
                    // Delete old user photo if different from teacher photo
                    if ($teacher->user->photo && $teacher->user->photo !== $teacher->photo) {
                        $filePath = base_path('assets/admin/uploads/' . $teacher->photo);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    $userUpdateData['photo'] = $teacherData['photo'];
                }

                $teacher->user->update($userUpdateData);
            }

            $teacher->update($teacherData);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', __('messages.Teacher updated successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', __('messages.An error occurred while updating the teacher'));
        }
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();

        try {
            // Delete teacher photo if exists
            if ($teacher->photo) {
                $filePath = base_path('assets/admin/uploads/' . $teacher->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete associated user if exists
            if ($teacher->user) {
                $teacher->user->delete();
            }

            $teacher->delete();

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', __('messages.Teacher deleted successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', __('messages.An error occurred while deleting the teacher'));
        }
    }


}
