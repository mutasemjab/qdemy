<?php
namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\CardUsage;
use App\Models\CardNumber;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use App\Models\CoursePayment;
use App\Models\CoursePaymentDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{

    public function index()
    {
        $user = auth_student();
        // الكورسات الموجودة في الـ Session
        $courses = CartRepository()->cart_session;
        $package = CartRepository()->getPackageCart();

        // تصفية الكورسات بحيث يظهر فقط غير المشترك فيها
        $packageIds = array_filter($courses, function ($course) use ($user) {
            return !CourseUser::where('user_id', $user->id)
                              ->where('course_id', $course)
                              ->exists();
        });
        $courses = Course::whereIn('id',$coursesIds)->get();
        return view('web.checkout', compact('courses','package'));
    }

    public function activateCard(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string',
        ]);

        $cardNumber = CardNumber::where('number', $request->card_number)
                                ->where('activate', 1) // البطاقة مفعلة
                                ->where('status', 2)  // البطاقة غير مستخدمة
                                ->first();

        if (!$cardNumber) {
            return response()->json([
                'success' => false,
                'message' => translate_lang('invalid_card'),
            ]);
        }

        $user    = auth_student();
        $courses = CartRepository()->cart_session;
        // تصفية الكورسات لغير المشترك فيها
        $coursesIds = array_filter($courses, function ($course) use ($user) {
            return !CourseUser::where('user_id', $user->id)
                              ->where('course_id', $course)
                              ->exists();
        });
        $courses = Course::whereIn('id',$coursesIds)->get();

        $totalCost = array_sum(array_column($courses, 'selling_price'));

        // التحقق من تطابق سعر البطاقة مع تكلفة الكورسات
        if ($totalCost != $cardNumber->card->price) {
            return response()->json([
                'success' => false,
                'message' => translate_lang('card_price_mismatch'),
            ]);
        }

        // تسجيل المستخدم في الكورسات
        foreach ($courses as $course) {
            CourseUser::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
        }

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
            'course_no' => count($courses),
            'sum_amount' => $totalCost,
            'payment_method' => 'card',
            'status' => 'completed',
        ]);

        // تسجيل تفاصيل الدفع
        foreach ($courses as $course) {
            CoursePaymentDetail::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'teacher_id' => $course->teacher_id,
                'amount' => $course->selling_price,
                'notes' => translate_lang('payment_notes'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => translate_lang('card_activated'),
        ]);
    }
}
