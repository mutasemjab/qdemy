<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseUser;
use App\Models\CoursePayment;
use App\Models\CoursePaymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CourseUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('courseUser-table');
        
        $courseUsers = CourseUser::with(['course', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.course-users.index', compact('courseUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('courseUser-add');
        
        $courses = Course::select('id', 'title_ar', 'selling_price')->get();
        $users = User::select('id', 'name', 'phone')->get();
        
        return view('admin.course-users.create', compact('courses', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('courseUser-add');
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:card,visa,cash',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Check if user is already enrolled in this course
            $existingEnrollment = CourseUser::where('user_id', $request->user_id)
                ->where('course_id', $request->course_id)
                ->first();

            if ($existingEnrollment) {
                return redirect()->back()
                    ->withErrors(['error' => __('messages.User already enrolled in this course')])
                    ->withInput();
            }

            // Get course details
            $course = Course::findOrFail($request->course_id);
            $user = User::findOrFail($request->user_id);
            
            // Use provided amount or course selling_price
            $amount = $request->amount ?? $course->selling_price ?? 0;

            // Create course enrollment
            $courseUser = CourseUser::create([
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
            ]);

            // Create payment record
            $coursePayment = CoursePayment::create([
                'user_id' => $request->user_id,
                'course_no' => $request->course_id,
                'currency' => config('app.currency', 'USD'),
                'transaction_reference' => 'ADMIN_' . time() . '_' . $request->user_id,
                'payment_method' => $request->payment_method,
                'deal_type' => 'course',
                'status' => 'completed',
                'sum_amount' => $amount,
                'receipt_number' => 'REC_' . date('Ymd') . '_' . str_pad($courseUser->id, 6, '0', STR_PAD_LEFT),
                'invioced_date' => now()->format('Y-m-d'),
                'notes' => $request->notes,
            ]);

            // Create payment detail record
            CoursePaymentDetail::create([
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
                'teacher_id' => $course->teacher_id ?? null,
                'amount' => $amount,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.course-users.index')
                ->with('success', __('messages.Course enrolled successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => __('messages.Something went wrong')])
                ->withInput();
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseUser $courseUser)
    {
        Gate::authorize('courseUser-edit');
        
        $courseUser->load(['course', 'user']);
        $courses = Course::select('id', 'title_ar', 'selling_price')->get();
        $users = User::select('id', 'name', 'phone')->get();
        
        // Get payment details
        $paymentDetails = CoursePaymentDetail::where('user_id', $courseUser->user_id)
            ->where('course_id', $courseUser->course_id)
            ->first();
            
        return view('admin.course-users.edit', compact('courseUser', 'courses', 'users', 'paymentDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseUser $courseUser)
    {
        Gate::authorize('courseUser-edit');
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:card,visa,cash',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Check if another enrollment exists with same user/course combination
            $existingEnrollment = CourseUser::where('user_id', $request->user_id)
                ->where('course_id', $request->course_id)
                ->where('id', '!=', $courseUser->id)
                ->first();

            if ($existingEnrollment) {
                return redirect()->back()
                    ->withErrors(['error' => __('messages.User already enrolled in this course')])
                    ->withInput();
            }

            // Get course details
            $course = Course::findOrFail($request->course_id);
            $amount = $request->amount ?? $course->selling_price ?? 0;

            // Update course enrollment
            $courseUser->update([
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
            ]);

            // Update payment record
            CoursePayment::where('user_id', $courseUser->user_id)
                ->where('course_no', $courseUser->course_id)
                ->update([
                    'user_id' => $request->user_id,
                    'course_no' => $request->course_id,
                    'payment_method' => $request->payment_method,
                    'sum_amount' => $amount,
                    'notes' => $request->notes,
                ]);

            // Update payment detail record
            CoursePaymentDetail::where('user_id', $courseUser->user_id)
                ->where('course_id', $courseUser->course_id)
                ->update([
                    'user_id' => $request->user_id,
                    'course_id' => $request->course_id,
                    'teacher_id' => $course->teacher_id ?? null,
                    'amount' => $amount,
                    'notes' => $request->notes,
                ]);

            DB::commit();

            return redirect()->route('admin.course-users.index')
                ->with('success', __('messages.Course enrollment updated successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => __('messages.Something went wrong')])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseUser $courseUser)
    {
        Gate::authorize('courseUser-delete');
        
        try {
            DB::beginTransaction();

            // Delete related payment records
            CoursePayment::where('user_id', $courseUser->user_id)
                ->where('course_no', $courseUser->course_id)
                ->delete();

            CoursePaymentDetail::where('user_id', $courseUser->user_id)
                ->where('course_id', $courseUser->course_id)
                ->delete();

            // Delete course enrollment
            $courseUser->delete();

            DB::commit();

            return redirect()->route('admin.course-users.index')
                ->with('success', __('messages.Course enrollment deleted successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => __('messages.Something went wrong')]);
        }
    }
}