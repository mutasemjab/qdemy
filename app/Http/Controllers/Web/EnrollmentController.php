<?php
namespace App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Package;
use App\Models\CardUsage;
use App\Models\CardNumber;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use App\Models\CoursePayment;
use Illuminate\Support\Facades\DB;
use App\Models\CoursePaymentDetail;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\CommissionDistribution;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * عرض صفحة السلة
     */
    public function index()
    {
        $user = auth_student();
        // if (!$user) {
        //     return redirect()->route('user.login');
        // }

        CartRepository()->initializeCart();
        $cartData = CartRepository()->getCartCoursesWithStatus();

        return view('web.checkout', [
            'courses' => $cartData['courses'],
            'is_package' => $cartData['is_package'],
            'package_info' => $cartData['package_info']
        ]);
    }

    /**
     * إضافة كورس للسلة
     */
    public function addToSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);

        $user = auth_student();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?: 'يجب تسجيل الدخول أولاً'
            ]);
        }

        return CartRepository()->put($request->course_id);
    }

    /**
     * تحديث سلة الباكدج
     */
    public function updatePackageCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            if (!CartRepository()->validCartContent($request->package_id)) {
                CartRepository()->clearCart();
            }

            $cart = CartRepository()->setPackageCart($request->package_id, $request->courses);

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'message' => 'تم تحديث السلة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * الحصول على محتويات سلة الباكدج
     */
    public function getPackageCart(Request $request)
    {
        try {
            $cart = CartRepository()->getPackageCart();

            return response()->json([
                'success' => true,
                'cart' => $cart
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب محتويات السلة'
            ], 500);
        }
    }

    /**
     * حذف كورس من السلة
     */
    public function removeCourseFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id'
        ]);

        $user = auth_student();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?: 'يجب تسجيل الدخول أولاً'
            ]);
        }

        return CartRepository()->removeItem($request->course_id);
    }

    /**
     * حذف الباكدج من السلة
     */
    public function removeCartFromAnyPackage(Request $request)
    {
        $user = auth_student();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ]);
        }

        return CartRepository()->removeAnyPackageFromCart();
    }

    public function removeCourseFromPackage(Request $request)
    {
        $user = auth_student();
        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => __('messages.login first'),
            ]);
        }
        if (!$request->package_id || !$request->course_id) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unexpected_error'),
            ]);
        }
        return CartRepository()->removeCourseFromPackage($request->package_id, $request->course_id);
    }

    /**
     * تفعيل البطاقة لكورس واحد
     */
    public function activateCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string',
            'course_id' => 'required|exists:courses,id'
        ]);

        $user = auth_student();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?: 'يجب تسجيل الدخول أولاً'
            ]);
        }

        $cardValidation = $this->validateCard($request->card_number);
        if (!$cardValidation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $cardValidation['message']
            ]);
        }

        $cardNumber = $cardValidation['card'];
        $courses = Course::where('id', $request->course_id)->get();
        $course = (clone $courses)->first();
        $courseId = $course?->id;

        $enrollmentCheck = $this->checkEnrollmentEligibility($user?->id, $course, $cardNumber);
        if (!$enrollmentCheck['valid']) {
            return response()->json([
                'success' => false,
                'message' => $enrollmentCheck['message']
            ]);
        }

        DB::beginTransaction();
        try {
            $this->enrollUserInCourse($user?->id, $course);
            $this->markCardAsUsed($cardNumber, $user?->id);
            $payment = $this->createPaymentRecord($user?->id, $courses, $cardNumber, 'single');

            // توزيع العمولات حسب الإعداد
            $this->distributeCommissions($course, $cardNumber, $payment->id);

            CartRepository()->removeItem($courseId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تفعيل البطاقة وإضافتك للكورس بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * الدفع بالبطاقة للكورسات العادية
     */
    public function paymentForCourseWithCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string'
        ]);

        $user = auth_student();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?: 'يجب تسجيل الدخول أولاً'
            ]);
        }

        $cardValidation = $this->validateCard($request->card_number);
        if (!$cardValidation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $cardValidation['message']
            ]);
        }

        $cardNumber = $cardValidation['card'];
        $validCourses = $this->getValidCoursesForPurchase($user?->id);

        if ($validCourses->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => translate_lang('cart_is_empty')
            ]);
        }

        $totalCost = $validCourses->sum('selling_price');

        if ($cardNumber->card->price != $totalCost) {
            return response()->json([
                'success' => false,
                'message' => 'يجب أن تساوي قيمة البطاقة سعر الكورسات'
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($validCourses as $course) {
                $this->enrollUserInCourse($user?->id, $course);
            }

            $this->markCardAsUsed($cardNumber, $user?->id);
            $payment = $this->createPaymentRecord($user?->id, $validCourses, $cardNumber, 'courses');

            // توزيع العمولات لكل كورس
            foreach ($validCourses as $course) {
                $this->distributeCommissions($course, $cardNumber, $payment->id);
            }

            CartRepository()->clearCart();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => translate_lang('card_activated')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * الدفع النقدي عبر WhatsApp
     */
    public function paymentWithCash(Request $request)
    {
        $user = auth_student();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => translate_lang('login_first')
            ]);
        }

        $paymentType = $request->input('payment_type', 'courses');

        if ($paymentType === 'package') {
            $packageCart = CartRepository()->getPackageCart();
            if (!$packageCart || empty($packageCart['courses'])) {
                return response()->json([
                    'success' => false,
                    'message' => translate_lang('no_package_in_cart')
                ]);
            }

            $courses = Course::whereIn('id', $packageCart['courses'] ?? [])->get();
            $total = $packageCart['package_price'];
        } else {
            $courseIds = CartRepository()->getCourseCart();
            $courses = Course::whereIn('id', $courseIds)->get();

            if ($courses->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => translate_lang('cart_empty')
                ]);
            }

            $total = $courses->sum('selling_price');
        }

        // رقم WhatsApp بكود الأردن
        $whatsappNumber = '962775743580';

        // بناء رسالة WhatsApp مع روابط الكورسات
        $message = "مرحبا، أود الاشتراك في الكورسات التالية:\n\n";

        foreach ($courses as $index => $course) {
            $courseUrl = route('course', ['course' => $course->id, 'slug' => $course->slug]);
            $message .= ($index + 1) . ". " . $course->title_ar . "\n";
            $message .= "الرابط: " . $courseUrl . "\n";
            $message .= "السعر: " . $course->selling_price . " " . CURRENCY . "\n\n";
        }

        $message .= "الإجمالي: " . $total . " " . CURRENCY . "\n";
        $message .= "الاسم: " . $user->name . "\n";
        $message .= "الهاتف: " . ($user->phone ?? 'لم يتم تحديده');

        // تحويل الرقم إلى صيغة WhatsApp
        $whatsappUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($message);

        // حفظ سجل الطلب
        DB::beginTransaction();
        try {
            $courseList = $courses->pluck('id')->toArray();

            $payment = CoursePayment::create([
                'user_id' => $user->id,
                'course_no' => count($courseList),
                'sum_amount' => $total,
                'payment_method' => 'cash',
                'status' => 'pending',
                'deal_type' => $paymentType === 'package' ? 'package' : 'course'
            ]);

            foreach ($courses as $course) {
                CoursePaymentDetail::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id ?? null,
                    'amount' => $paymentType === 'package' ? null : $course->selling_price,
                    'notes' => 'طلب دفع نقدي عبر WhatsApp'
                ]);
            }

            CartRepository()->clearCart();

            DB::commit();

            return response()->json([
                'success' => true,
                'whatsapp_url' => $whatsappUrl,
                'message' => translate_lang('cash_payment_success')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => translate_lang('payment_error')
            ]);
        }
    }

    /**
     * الدفع بالبطاقة للباكدج
     */
    public function paymentForPackageWithCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string'
        ]);

        $user = auth_student();
        if ($validator->fails() || !$user) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?: 'يجب تسجيل الدخول أولاً'
            ]);
        }

        $cardValidation = $this->validateCard($request->card_number);
        if (!$cardValidation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $cardValidation['message']
            ]);
        }

        $cardNumber = $cardValidation['card'];
        $packageCart = CartRepository()->getPackageCart();

        if (!$packageCart || empty($packageCart['courses'])) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد باكدج في السلة'
            ]);
        }

        $validCourses = $this->getValidPackageCoursesForPurchase($user?->id, $packageCart);
        if ($validCourses->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => translate_lang('cart_is_empty')
            ]);
        }

        if ($validCourses->count() != $packageCart['max_courses']) {
            return response()->json([
                'success' => false,
                'message' => 'يجب أن تحتوي السلة على ' . $packageCart['max_courses'] . ' كورسات صالحة وغير مشترك فيها'
            ]);
        }

        if ($cardNumber->card->price != $packageCart['package_price']) {
            return response()->json([
                'success' => false,
                'message' => 'يجب أن تساوي قيمة البطاقة سعر الباكدج'
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($validCourses as $course) {
                $this->enrollUserInCourse($user?->id, $course);
            }

            $this->markCardAsUsed($cardNumber, $user?->id);
            $payment = $this->createPaymentRecord($user?->id, $validCourses, $cardNumber, 'package', $packageCart['package_id']);

            // توزيع العمولات لكل كورس في الباكدج
            foreach ($validCourses as $course) {
                $this->distributeCommissions($course, $cardNumber, $payment->id);
            }

            CartRepository()->clearCart();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => translate_lang('card_activated')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper Methods
     */

    private function validateCard($cardNumber)
    {
        $card = CardNumber::where('number', $cardNumber)
            ->where('activate', 1)
            ->where('status', 2)
            ->first();

        if (!$card) {
            return [
                'valid' => false,
                'message' => translate_lang('invalid_card'),
                'card' => null
            ];
        }

        return [
            'valid' => true,
            'message' => '',
            'card' => $card
        ];
    }

    private function checkEnrollmentEligibility($userId, $course, $cardNumber)
    {
        if (CourseUser::where('user_id', $userId)->where('course_id', $course->id)->exists()) {
            return [
                'valid' => false,
                'message' => 'أنت مشترك بالفعل في هذا الكورس'
            ];
        }

        if (!$course->is_active) {
            return [
                'valid' => false,
                'message' => 'هذا الكورس غير متاح حالياً'
            ];
        }

        if ($cardNumber->card->price != $course->selling_price) {
            return [
                'valid' => false,
                'message' => 'يجب أن تساوي قيمة البطاقة سعر الكورس'
            ];
        }

        return ['valid' => true];
    }

    private function getValidCoursesForPurchase($userId)
    {
        $courseIds = CartRepository()->getCourseCart();

        return Course::active()
            ->whereIn('id', $courseIds)
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('course_id')
                    ->from('course_users')
                    ->where('user_id', $userId);
            })
            ->get();
    }

    private function getValidPackageCoursesForPurchase($userId, $packageCart)
    {
        $courseIds = $packageCart['courses'] ?? [];

        return Course::active()
            ->whereIn('id', $courseIds)
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('course_id')
                    ->from('course_users')
                    ->where('user_id', $userId);
            })
            ->get();
    }

    private function enrollUserInCourse($userId, $course)
    {
        return CourseUser::create([
            'user_id' => $userId,
            'course_id' => $course->id
        ]);
    }

    private function markCardAsUsed($cardNumber, $userId)
    {
        $cardNumber->update(['status' => 1]);

        return CardUsage::create([
            'user_id' => $userId,
            'card_number_id' => $cardNumber->id,
            'used_at' => now()
        ]);
    }

    private function createPaymentRecord($userId, $courses, $cardNumber, $type = 'courses', $packageId = null)
    {
        $totalAmount = $type === 'package'
            ? $cardNumber->card->price
            : $courses->sum('selling_price');

        $payment = CoursePayment::create([
            'user_id' => $userId,
            'course_no' => $courses->count(),
            'sum_amount' => $totalAmount,
            'payment_method' => 'card',
            'status' => 'completed',
            'package_id' => $packageId,
            'deal_type' => $type === 'package' ? 'package' : 'course'
        ]);

        foreach ($courses as $course) {
            CoursePaymentDetail::create([
                'user_id' => $userId,
                'course_id' => $course->id,
                'teacher_id' => $course->teacher_id ?? null,
                'amount' => $type === 'package' ? null : $course->selling_price,
                'notes' => translate_lang('payment_notes')
            ]);
        }

        return $payment;
    }

    /**
     * توزيع العمولات بين المنصة والمعلم حسب الإعدادات
     *
     * المعادلة:
     * - عمولة المنصة = course.selling_price × commission_of_admin / 100
     * - عمولة نقطة البيع = course.selling_price × pos.percentage / 100
     *
     * حسب الإعداد pos_commission_distribution:
     * - 50_50: تقسيم عمولة POS بالتساوي (المنصة: -50%, المعلم: -50%)
     * - 100_teacher: المعلم يدفع كل عمولة POS (المنصة: 0, المعلم: -100%)
     * - 100_platform: المنصة تدفع كل عمولة POS (المنصة: -100%, المعلم: 0)
     */
    private function distributeCommissions($course, $cardNumber, $paymentId)
    {
        // التحقق من وجود معلم
        if (!$course->teacher_id) {
            return;
        }

        $teacher = User::find($course->teacher_id);
        if (!$teacher) {
            return;
        }

        // حساب العمولات الأساسية
        $coursePrice = $course->selling_price;
        $platformCommissionPercentage = $course->commission_of_admin ?? 0;
        $platformCommissionAmount = ($coursePrice * $platformCommissionPercentage) / 100;

        // الحصول على عمولة نقطة البيع من الـ Card
        $card = $cardNumber->card;
        $pos = $card->pos;
        $posCommissionPercentage = $pos?->percentage ?? 0;
        $posCommissionAmount = ($coursePrice * $posCommissionPercentage) / 100;

        // الحصول على إعداد توزيع العمولة
        $setting = Setting::first();
        $distributionType = $setting?->pos_commission_distribution ?? '50_50';

        // حساب الخصومات حسب نوع التوزيع
        $platformPosDeduction = 0;
        $teacherPosDeduction = 0;

        if ($distributionType === '50_50') {
            // تقسيم عمولة POS بالتساوي
            $platformPosDeduction = $posCommissionAmount / 2;
            $teacherPosDeduction = $posCommissionAmount / 2;
        } elseif ($distributionType === '100_teacher') {
            // المعلم يدفع كل عمولة POS
            $teacherPosDeduction = $posCommissionAmount;
        } elseif ($distributionType === '100_platform') {
            // المنصة تدفع كل عمولة POS
            $platformPosDeduction = $posCommissionAmount;
        }

        // حساب المبالغ النهائية
        $platformFinalAmount = $platformCommissionAmount - $platformPosDeduction;
        $teacherFinalAmount = $coursePrice - $platformCommissionAmount - $teacherPosDeduction;

        // تحديث رصيد المعلم
        $teacher->increment('balance', $teacherFinalAmount);

        // تسجيل في WalletTransaction
        WalletTransaction::create([
            'user_id' => $teacher->id,
            'admin_id' => 1,
            'amount' => $teacherFinalAmount,
            'type' => 1, // إضافة رصيد
            'note' => "رصيد من بيع الكورس: {$course->title_ar} (عمولة منصة: {$platformCommissionPercentage}%, نقطة بيع: {$posCommissionPercentage}%, توزيع: {$distributionType})"
        ]);

        // تسجيل في CommissionDistribution
        CommissionDistribution::create([
            'course_payment_id' => $paymentId,
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'pos_id' => $pos?->id,
            'course_price' => $coursePrice,
            'platform_commission_percentage' => $platformCommissionPercentage,
            'platform_commission_amount' => $platformCommissionAmount,
            'pos_commission_percentage' => $posCommissionPercentage,
            'pos_commission_amount' => $posCommissionAmount,
            'distribution_type' => $distributionType,
            'platform_final_amount' => $platformFinalAmount,
            'teacher_final_amount' => $teacherFinalAmount,
            'platform_pos_deduction' => $platformPosDeduction,
            'teacher_pos_deduction' => $teacherPosDeduction,
            'notes' => "توزيع عمولات للكورس {$course->title_ar} - نسبة توزيع: {$distributionType}"
        ]);

        \Log::info("توزيع العمولات: المعلم {$teacher->name} حصل على {$teacherFinalAmount} للكورس {$course->title_ar} (توزيع: {$distributionType})");
    }
}
