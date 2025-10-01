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
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * عرض صفحة السلة
     */
    public function index()
    {
        $user = auth_student();
        if (!$user) {
            return redirect()->route('user.login');
        }

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
            $this->createPaymentRecord($user?->id, $courses, $cardNumber, 'single');
            
            // خصم العمولة من المعلم
            $this->deductCommissionFromTeacher($course);

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
                // خصم العمولة من كل معلم
                $this->deductCommissionFromTeacher($course);
            }

            $this->markCardAsUsed($cardNumber, $user?->id);
            $this->createPaymentRecord($user?->id, $validCourses, $cardNumber, 'courses');

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
            $this->createPaymentRecord($user?->id, $validCourses, $cardNumber, 'package', $packageCart['package_id']);

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
     * خصم العمولة من المعلم وتسجيلها في المحفظة (للكورسات العادية)
     */
    private function deductCommissionFromTeacher($course)
    {
        if (!$course->teacher_id) {
            return;
        }

        $commissionPercentage = $course->commission_of_admin ?? 0;
        $commissionAmount = ($course->selling_price * $commissionPercentage) / 100;

        if ($commissionAmount <= 0) {
            return;
        }

        $teacher = User::find($course->teacher_id);
        
        if (!$teacher) {
            return;
        }

        $teacher->decrement('balance', $commissionAmount);

        WalletTransaction::create([
            'user_id' => $teacher->id,
            'admin_id' => 1,
            'amount' => $commissionAmount,
            'type' => 2,
            'note' => "خصم عمولة إدارية ({$commissionPercentage}%) للكورس: {$course->title_ar}"
        ]);

        \Log::info("تم خصم عمولة {$commissionAmount} من المعلم {$teacher->name} للكورس {$course->title_ar}");
    }
}
