<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Clas;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role_name','student')->with('clas');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('referal_code', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role_name')) {
            $query->where('role_name', $request->role_name);
        }

        // Filter by status
        if ($request->filled('activate')) {
            $query->where('activate', $request->activate);
        }

        // Filter by category
        if ($request->filled('clas_id')) {
            $query->where('clas_id', $request->clas_id);
        }

        $users = $query->paginate(15);
        $classes = Clas::all();

        return view('admin.users.index', compact('users', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = Clas::all();
        return view('admin.users.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role_name' => 'nullable',
            'phone' => 'nullable|string|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'activate' => 'required|in:1,2',
            'balance' => 'nullable|numeric|min:0',
            'referal_code' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'clas_id' => 'nullable|exists:clas,id',
        ]);

        $data = $request->all();

        // Handle password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        // Generate referral code if not provided
        if (empty($data['referal_code'])) {
            $data['referal_code'] = Str::random(8);
        }

        $data['role_name'] = 'student';

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', __('messages.User created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('clas');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $classes = Clas::all();
        return view('admin.users.edit', compact('user', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable',
                'string',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'activate' => 'required|in:1,2',
            'balance' => 'nullable|numeric|min:0',
            'referal_code' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'clas_id' => 'nullable|exists:clas,id',
        ]);

        $data = $request->all();

        // Handle password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                $filePath = base_path('assets/admin/uploads/' . $user->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }


        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', __('messages.User updated successfully'));
    }


}
