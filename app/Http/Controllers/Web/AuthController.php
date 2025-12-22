<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ParentStudent;
use App\Models\Parentt;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }


    public function showLogin()
    {

        return view('web.login');
    }


    public function showRegister()
    {
        $classes = DB::table('clas')->get();
        return view('web.register', compact('classes'));
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
            'phone'     => 'nullable|string|unique:users,phone|max:20',
            'email'     => 'required|string|unique:users,email|max:255',
            'password'  => 'required|string|min:6',
            'role_name' => 'required|in:student,parent',
        ];

        // Add grade validation for students
        if ($request->role_name === 'student') {
            $rules['grade'] = 'required|exists:clas,id';
        }

        // Add children validation for parents
        if ($request->role_name === 'parent') {
            $rules['selected_children'] = 'nullable|string'; // JSON string of child IDs
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // Determine if OTP is required (phone is provided)
            $requiresOtp = !empty($request->phone);

            // Create the user
            $user = User::create([
                'name'      => $request->name,
                'phone'     => $request->phone,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role_name' => $request->role_name,
                'clas_id'   => $request->role_name === 'student' ? $request->grade : null,
                'activate'  => $requiresOtp ? 0 : 1, // Not activated if OTP required
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

            // If phone provided, send OTP and redirect to verification page
            if ($requiresOtp) {
                $result = $this->otpService->generateAndSendOtp($request->phone);

                if (!$result['success']) {
                    return redirect()->back()
                        ->withErrors(['otp' => 'فشل إرسال رمز التحقق. يرجى المحاولة مرة أخرى.'])
                        ->withInput($request->except('password'));
                }

                // Store user ID in session for OTP verification
                Session::put('pending_verification_user_id', $user->id);
                Session::put('pending_verification_phone', $request->phone);

                return redirect()->route('otp.verify')
                    ->with('success', 'تم التسجيل بنجاح. يرجى إدخال رمز التحقق المرسل إلى هاتفك.');
            }

            // No OTP required, login immediately
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
     * Show OTP verification form
     */
    public function showOtpVerification()
    {
        if (!Session::has('pending_verification_user_id')) {
            return redirect()->route('register')
                ->withErrors(['error' => 'لا توجد عملية تحقق معلقة.']);
        }

        $phone = Session::get('pending_verification_phone');

        // Return your existing otp.blade.php view
        return view('web.otp', compact('phone'));
    }

    /**
     * Step 2: Verify OTP and activate user
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:4',
        ]);

        if (!Session::has('pending_verification_user_id')) {
            return redirect()->route('register')
                ->withErrors(['error' => 'لا توجد عملية تحقق معلقة.']);
        }

        $userId = Session::get('pending_verification_user_id');
        $phone = Session::get('pending_verification_phone');

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['error' => 'المستخدم غير موجود.']);
        }

        // Check for test OTP
        if ($this->otpService->isTestOtp($phone, $request->otp)) {
            $user->update(['activate' => 1]);

            Session::forget(['pending_verification_user_id', 'pending_verification_phone']);

            Auth::login($user);

            return $this->redirectToUserPanel($user);
        }

        // Verify real OTP
        if (!$this->otpService->otpExists($phone)) {
            return redirect()->back()
                ->withErrors(['otp' => 'انتهت صلاحية رمز التحقق. يرجى طلب رمز جديد.'])
                ->withInput();
        }

        if ($this->otpService->verifyOtp($phone, $request->otp)) {
            // Activate user
            $user->update(['activate' => 1]);

            // Clear session
            Session::forget(['pending_verification_user_id', 'pending_verification_phone']);

            // Login user
            Auth::login($user);

            // Redirect based on user role
            return $this->redirectToUserPanel($user);
        }

        return redirect()->back()
            ->withErrors(['otp' => 'رمز التحقق غير صحيح. يرجى المحاولة مرة أخرى.'])
            ->withInput();
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        if (!Session::has('pending_verification_user_id')) {
            return redirect()->route('register')
                ->withErrors(['error' => 'لا توجد عملية تحقق معلقة.']);
        }

        $phone = Session::get('pending_verification_phone');

        $result = $this->otpService->generateAndSendOtp($phone);

        if ($result['success']) {
            return redirect()->back()
                ->with('success', 'تم إعادة إرسال رمز التحقق بنجاح.');
        }

        return redirect()->back()
            ->withErrors(['otp' => 'فشل إعادة إرسال رمز التحقق. يرجى المحاولة مرة أخرى.']);
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
            $query->where(function ($q) use ($search) {
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
                return redirect()->route('home');
            case 'parent':
                return redirect()->route('home');
            case 'teacher':
                return redirect()->route('home');
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
