<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


use App\Models\Parentt;
use App\Models\ParentStudent;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:parent-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:parent-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:parent-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:parent-delete', ['only' => ['destroy']]);
    }
   
    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Parentt::with(['user', 'students']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%")
                               ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by has user account
        if ($request->filled('has_user')) {
            if ($request->has_user == 'yes') {
                $query->whereNotNull('user_id');
            } elseif ($request->has_user == 'no') {
                $query->whereNull('user_id');
            }
        }

        // Filter by number of students
        if ($request->filled('students_count')) {
            if ($request->students_count == 'with_students') {
                $query->has('students');
            } elseif ($request->students_count == 'no_students') {
                $query->doesntHave('students');
            }
        }

        $parents = $query->withCount('students')->paginate(15);

        return view('admin.parents.index', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::where('role_name', 'student')->get();
        return view('admin.parents.create', compact( 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            
            // User creation fields
            'create_user_account' => 'boolean',
            'email' => 'required_if:create_user_account,1|nullable|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'password' => 'required_if:create_user_account,1|nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Student relationships
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        
        try {
            $parentData = [
                'name' => $request->name,
            ];

            // Create user account if requested
            $userId = null;
            if ($request->boolean('create_user_account') && $request->filled('email')) {
                $userData = [
                    'name' => $request->name,
                    'role_name' => 'parent',
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'activate' => 1,
                    'balance' => 0,
                    'referal_code' => Str::random(8),
                ];

                // Handle photo upload
                if ($request->hasFile('photo')) {
                    $userData['photo'] = uploadImage('assets/admin/uploads', $request->photo);
                }

                $user = User::create($userData);
                $userId = $user->id;
            }

            $parentData['user_id'] = $userId;
            $parent = Parentt::create($parentData);

            // Create parent-student relationships
            if ($request->filled('student_ids')) {
                foreach ($request->student_ids as $studentId) {
                    ParentStudent::create([
                        'parentt_id' => $parent->id,
                        'user_id' => $studentId,
                    ]);
                }
            }

            DB::commit();

            $message = $userId 
                ? __('messages.Parent and user account created successfully')
                : __('messages.Parent created successfully');

            return redirect()->route('parents.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Clean up uploaded photo if transaction failed
            if (isset($userData['photo']) ) {
                    $filePath = base_path('assets/admin/uploads/' .$userData['photo']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return back()->withInput()->with('error', __('messages.An error occurred while creating the parent'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Parentt $parent)
    {
        $parent->load(['user', 'students']);
        return view('parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parentt $parent)
    {
        $parent->load(['user', 'students']);

        $students = User::where('role_name', 'student')->get();
        $selectedStudentIds = $parent->students->pluck('id')->toArray();
        
        return view('parents.edit', compact('parent', 'students', 'selectedStudentIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parentt $parent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            
            // User update fields
            'update_user_info' => 'boolean',
            'email' => 'nullable|email|unique:users,email,' . ($parent->user_id ?? 'NULL'),
            'phone' => 'nullable|string|unique:users,phone,' . ($parent->user_id ?? 'NULL'),
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Student relationships
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        
        try {
            $parentData = [
                'name' => $request->name,
            ];

            // Update associated user if exists and update is requested
            if ($parent->user && $request->boolean('update_user_info')) {
                $userUpdateData = [
                    'name' => $request->name,
                ];

                if ($request->filled('email')) {
                    $userUpdateData['email'] = $request->email;
                }

                if ($request->filled('phone')) {
                    $userUpdateData['phone'] = $request->phone;
                }

                if ($request->filled('password')) {
                    $userUpdateData['password'] = Hash::make($request->password);
                }

             

                // Handle photo upload
                if ($request->hasFile('photo')) {
                    // Delete old photo
                    if ($parent->user->photo ) {
                        $filePath = base_path('assets/admin/uploads/' . $parent->user->photo);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    $userUpdateData['photo'] = uploadImage('assets/admin/uploads', $request->photo);
                }

                $parent->user->update($userUpdateData);
            }

            $parent->update($parentData);

            // Update parent-student relationships
            // First, delete existing relationships
            ParentStudent::where('parentt_id', $parent->id)->delete();
            
            // Then create new relationships
            if ($request->filled('student_ids')) {
                foreach ($request->student_ids as $studentId) {
                    ParentStudent::create([
                        'parentt_id' => $parent->id,
                        'user_id' => $studentId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('parents.index')
                ->with('success', __('messages.Parent updated successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', __('messages.An error occurred while updating the parent'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parentt $parent)
    {
        DB::beginTransaction();
        
        try {
            // Delete parent-student relationships first
            ParentStudent::where('parentt_id', $parent->id)->delete();

            // Delete associated user if exists (will cascade due to foreign key)
            if ($parent->user) {
                // Delete user photo if exists
                if ($parent->user->photo ) {
                   $filePath = base_path('assets/admin/uploads/' . $parent->user->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                }
                $parent->user->delete();
            }

            $parent->delete();

            DB::commit();

            return redirect()->route('parents.index')
                ->with('success', __('messages.Parent deleted successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', __('messages.An error occurred while deleting the parent'));
        }
    }

    /**
     * Remove a student from parent.
     */
    public function removeStudent(Parentt $parent, User $student)
    {
        try {
            ParentStudent::where('parentt_id', $parent->id)
                         ->where('user_id', $student->id)
                         ->delete();

            return redirect()->route('parents.show', $parent)
                ->with('success', __('messages.Student removed from parent successfully'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.An error occurred while removing the student'));
        }
    }

    /**
     * Add a student to parent.
     */
    public function addStudent(Request $request, Parentt $parent)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        try {
            // Check if relationship already exists
            $exists = ParentStudent::where('parentt_id', $parent->id)
                                  ->where('user_id', $request->student_id)
                                  ->exists();

            if ($exists) {
                return back()->with('warning', __('messages.Student is already associated with this parent'));
            }

            ParentStudent::create([
                'parentt_id' => $parent->id,
                'user_id' => $request->student_id,
            ]);

            return redirect()->route('parents.show', $parent)
                ->with('success', __('messages.Student added to parent successfully'));

        } catch (\Exception $e) {
            return back()->with('error', __('messages.An error occurred while adding the student'));
        }
    }
}
