<?php

namespace App\Http\Controllers\User;

use App\Models\Course;
use App\Models\CardUsage;
use App\Models\CardNumber;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use App\Models\CoursePayment;
use Illuminate\Support\Facades\DB;
use App\Models\CoursePaymentDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    // cart index page
    // get courses from session and shoe it
    public function index()
    {
        $user = auth('user')->user();
        // الكورسات الموجودة في الـ Session
        $courses = Session::get('courses', []);

        // تصفية الكورسات بحيث يظهر فقط غير المشترك فيها
        $coursesIds = array_filter($courses, function ($course) use ($user) {
            return !CourseUser::where('user_id', $user->id)
                              ->where('course_id', $course)
                              ->exists();
        });
        $courses = Course::whereIn('id',$coursesIds)->get();
        return view('user.checkout', compact('courses'));
    }

    // add course to session cart
    public function addToSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);
        $user   = auth('user')->user();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success' => true,
                'message' => $validator->errors()->first(),
            ]);
        }

        $requestData = json_decode($request->getContent(), true);
        $courseId = $request->course_id;

        // تصفية الكورسات بحيث يظهر فقط غير المشترك فيها
        $courses = Session::get('courses', []);
        $courses = array_filter($courses, function ($course) use ($user) {
            return !CourseUser::where('user_id', $user->id)
                              ->where('course_id', $course)
                              ->exists();
        });

        // تجنب تكرار الكورس
        if (!in_array($courseId, $courses)) {
            $courses[] = $courseId;
            Session::put('courses', $courses);
            // تعيين صلاحية شهر للجلسة
            $expiryDays = env('COMPLETED_WATCHING_COURSES', 30);
            Session::put('courses_expiry', now()->addDays($expiryDays));
        }else{
            return response()->json([
                'success' => true,
                'message' => __('messages.course_already_in_cart'),
                'courses_count' => count($courses)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.course_added_successfully'),
            'courses_count' => count($courses)
        ]);
    }

    // enroll course by card number
    public function activateCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string',
            'course_id'   => 'required|exists:courses,id'
        ]);
        $user = auth('user')->user();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success'   => true,
                'message'   => $validator->errors()->first(),
            ]);
        }

        $cardNumber = CardNumber::where('number', $request->card_number)
                                ->where('activate', 1) // البطاقة مفعلة
                                ->where('status', 2)  // البطاقة غير مستخدمة
                                ->first();
        if (!$cardNumber) {
            return response()->json([
                'success' => false,
                'message' => 'البطاقة غير موجودة أو غير صالحة للاستخدام',
            ]);
        }

        $courseId = $request->course_id; // تأكد من تحديد الكورس المناسب أو استلامه من الطلب
        $course = Course::find($courseId);

        // التحقق من أن المستخدم غير مشترك بالفعل في الكورس
        $alreadyEnrolled = CourseUser::where('user_id', $user->id)
                                     ->where('course_id', $courseId)
                                     ->exists();

        if ($alreadyEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'أنت مشترك بالفعل في هذا الكورس',
            ]);
        }
        if ($cardNumber->card->price != $course->selling_price) {
            return response()->json([
                'success' => false,
                'message' => 'يجب أن تساوي قيمة البطاقة سعر الكورس',
            ]);
        }


        DB::beginTransaction();
        try {
            // إضافة المستخدم إلى الكورس
            CourseUser::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
            ]);

            // تحديث حالة البطاقة
            $cardNumber->update([
                'status' => 1, // البطاقة مستخدمة
            ]);

            // تسجيل استخدام البطاقة
            CardUsage::create([
                'user_id' => $user->id,
                'card_number_id' => $cardNumber->id,
                'used_at' => now(),
            ]);

            // تسجيل عملية الدفع
            $coursePayment = CoursePayment::create([
                'user_id' => $user->id,
                'course_no' => 1,
                'sum_amount' => $cardNumber->card->price,
                'payment_method' => 'card',
                'status' => 'completed',
            ]);

            CoursePaymentDetail::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'teacher_id' => $course->teacher?->id ?? null,
                'amount' => $cardNumber->card->price,
                'notes' => 'تم الدفع باستخدام بطاقة Qdemy',
            ]);

            // حذف الكورس المضاف من سيشن الكارت
            $courses = Session::get('courses', []);
            $filteredCourses = array_filter($courses, function ($course) use ($request) {
                return $course != $request->course_id;
            });
            Session::put('courses', $filteredCourses);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $success = false;
            $message = $e->getMessage();
        }

        return response()->json([
            'success' => $success ?? true,
            'message' => $message ?? 'تم تفعيل البطاقة وإضافتك للكورس بنجاح',
        ]);
    }

    // payment to cart courses by card number
    // and mark card as used
    public function paymentWithCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string',
        ]);
        $user = auth('user')->user();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success'   => true,
                'message'   => $validator->errors()->first(),
            ]);
        }

        $cardNumber = CardNumber::where('number', $request->card_number)
                                ->where('activate', 1)
                                ->where('status', 2)
                                ->first();

        if (!$cardNumber) {
            return response()->json([
                'success' => false,
                'message' => __('messages.invalid_card'),
            ]);
        }

        // تصفية الكورسات بحيث يظهر فقط غير المشترك فيها
        $courses = Session::get('courses', []);
        $coursesIds = array_filter($courses, function ($course) use ($user) {
            return !CourseUser::where('user_id', $user->id)
            ->where('course_id', $course)
            ->exists();
        });
        $courses   = Course::whereIn('id',$coursesIds)->get();
        $totalCost = $courses->sum('selling_price');

        // التحقق من وجود كورسات ف الكارت
        if (!$courses || !$courses->count()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.cart_is_empty'),
            ]);
        }

        // التحقق من تساوي قيمة البطاقة مع سعر الكورسات
        if ($cardNumber->card->price != $totalCost) {
            return response()->json([
                'success' => false,
                'message' => 'يجب أن تساوي قيمة البطاقة سعر الكورسات',
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($courses as $course) {
                CourseUser::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);
            }
            $cardNumber->update(['status' => 1]);
            CardUsage::create([
                'user_id' => $user->id,
                'card_number_id' => $cardNumber->id,
                'used_at' => now(),
            ]);
            CoursePayment::create([
                'user_id' => $user->id,
                'course_no' => count($courses),
                'sum_amount' => $totalCost,
                'payment_method' => 'card',
                'status' => 'completed',
            ]);
            foreach ($courses as $course) {
                CoursePaymentDetail::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id ?? null,
                    'amount' => $course->selling_price,
                    'notes' => __('messages.payment_notes'),
                ]);
            }
            Session::forget('courses');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $success = false;
            $message = $e->getMessage();
        }
        return response()->json([
            'success' => $success ?? true,
            'message' => $message ?? __('messages.card_activated'),
        ]);
    }

    // remove course from courses session
    public function removeCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id'   => 'required|exists:courses,id'
        ]);
        $user = auth('user')->user();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success'   => true,
                'message'   => $validator->errors()->first(),
            ]);
        }

        $courses = Session::get('courses', []);
        $filteredCourses = array_filter($courses, function ($course) use ($request) {
            return $course != $request->course_id;
        });

        Session::put('courses', $filteredCourses);

        return response()->json([
            'success' => true,
            'message' => __('messages.course_removed'),
        ]);
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'cardholder_name' => 'required|string',
            'card_number' => 'required|string|min:16|max:16',
            'expiry' => 'required|string',
            'cvc' => 'required|string|min:3|max:3',
        ]);

        $paymentProcessed = true;

        if ($paymentProcessed) {
            return response()->json([
                'success' => true,
                'message' => 'تمت عملية الدفع بنجاح',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'فشل الدفع. الرجاء المحاولة مرة أخرى.',
            ]);
        }
    }

    public function paymentSuccess()
    {
        return view('payment-success');
    }

}
