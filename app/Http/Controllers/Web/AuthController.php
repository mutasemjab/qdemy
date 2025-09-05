<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ParentStudent;
use App\Models\Parentt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        // Find the user
        $user = User::where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return redirect()->route('user.login')->with('error','User Not found with these phone number');
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.login')->with('success','Password Change Successfully');

    }

    public function showLogin()
    {

        return view('web.login');
    }


    public function showRegister()
    {

        return view('web.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'     => 'required|string|exists:users,phone',
            'password'  => 'required|string',
        ]);

        if (Auth::attempt([
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
            'activate' => 1
        ])) {
            $request->session()->regenerate();
            
            // Redirect based on user role
            $user = Auth::user();
            return $this->redirectToUserPanel($user);
        }

        return redirect()->back()
            ->withErrors(['phone' => translate_lang('auth_failed')])
            ->withInput($request->except('password'));
    }

    public function register(Request $request)
    {
        // Validation rules based on role
        $rules = [
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|unique:users,phone|max:20',
            'email'     => 'required|string|unique:users,email|max:255',
            'password'  => 'required|string|min:6',
            'role_name' => 'required|in:student,parent',
        ];

        // Add grade validation for students
        if ($request->role_name === 'student') {
            $rules['grade'] = 'required|in:1,2,3,4,5,6,7,8,9';
        }

        // Add children validation for parents
        if ($request->role_name === 'parent') {
            $rules['selected_children'] = 'nullable|string'; // JSON string of child IDs
        }

        $request->validate($rules);

        DB::beginTransaction();
        
        try {
            // Create the user
            $user = User::create([
                'name'      => $request->name,
                'phone'     => $request->phone,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role_name' => $request->role_name,
                'clas_id'   => $request->role_name === 'student' ? $request->grade : null,
                'activate'  => 1,
            ]);

            // If it's a parent, create parent record and relationships
            if ($request->role_name === 'parent') {
                $parent = Parentt::create([
                    'name' => $request->name,
                    'user_id' => $user->id,
                ]);

                // Add selected children if any
                if ($request->selected_children) {
                    $childrenIds = json_decode($request->selected_children, true);
                    
                    if (is_array($childrenIds) && !empty($childrenIds)) {
                        // Verify all children exist and are students
                        $validChildren = User::whereIn('id', $childrenIds)
                                           ->where('role_name', 'student')
                                           ->where('activate', 1)
                                           ->pluck('id')
                                           ->toArray();

                        // Create parent-student relationships
                        foreach ($validChildren as $childId) {
                            ParentStudent::create([
                                'parentt_id' => $parent->id,
                                'user_id' => $childId,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            
            Auth::login($user);

            // Redirect based on user role
            return $this->redirectToUserPanel($user);

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withErrors(['registration' => 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.'])
                ->withInput($request->except('password'));
        }
    }

    /**
     * Search for students by phone number (AJAX endpoint)
     */
    public function searchStudent(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $students = User::where('role_name', 'student')
                       ->where('activate', 1)
                       ->where('phone', 'LIKE', '%' . $request->phone . '%')
                       ->select('id', 'name', 'phone', 'clas_id')
                       ->get();

        return response()->json([
            'success' => true,
            'students' => $students,
            'count' => $students->count()
        ]);
    }

    /**
     * Get available students for parent selection
     */
    public function getAvailableStudents(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = User::where('role_name', 'student')
                    ->where('activate', 1);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
        }

        $students = $query->select('id', 'name', 'phone', 'clas_id')
                         ->orderBy('name')
                         ->paginate(20);

        return response()->json([
            'success' => true,
            'students' => $students->items(),
            'pagination' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'total' => $students->total()
            ]
        ]);
    }

    /**
     * Redirect user to their appropriate panel
     */
    private function redirectToUserPanel($user)
    {
        switch ($user->role_name) {
            case 'student':
                return redirect()->route('student.dashboard');
            case 'parent':
                return redirect()->route('parent.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            default:
                return redirect()->route('home');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
