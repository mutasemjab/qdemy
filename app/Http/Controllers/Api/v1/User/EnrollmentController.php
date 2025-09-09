<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\CardNumber;
use App\Models\CardUsage;
use App\Models\CoursePayment;
use App\Models\CoursePaymentDetail;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class EnrollmentController extends Controller
{
    use Responses;

    /**
     * عرض محتويات السلة
     */
    public function index()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        // تنظيف السلة عند البداية
        CartRepository()->initializeCart();

        // الحصول على البيانات من السلة
        $cartData = CartRepository()->getCartCoursesWithStatus();

        return $this->success_response('تم جلب محتويات السلة بنجاح', [
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

        $user = auth('user-api')->user();
        if ($validator->fails()) {
            return $this->error_response($validator->errors()->first(), null);
        }

        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        $result = CartRepository()->put($request->course_id);
        
        if ($result && isset($result->original) && $result->original['success']) {
            return $this->success_response($result->original['message'], $result->original);
        }

        return $this->error_response('حدث خطأ أثناء إضافة الكورس للسلة', null);
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
            return $this->error_response($validator->errors()->first(), null);
        }

        try {
            // التحقق من الصلاحية
            if (!CartRepository()->validCartContent($request->package_id)) {
                CartRepository()->clearCart(); // مسح السلة بالكامل للبدء من جديد
            }

            $cart = CartRepository()->setPackageCart($request->package_id, $request->courses);

            return $this->success_response('تم تحديث السلة بنجاح', $cart);

        } catch (\Exception $e) {
            return $this->error_response($e->getMessage(), null);
        }
    }

    /**
     * الحصول على محتويات سلة الباكدج
     */
    public function getPackageCart(Request $request)
    {
        try {
            $cart = CartRepository()->getPackageCart();

            return $this->success_response('تم جلب محتويات السلة بنجاح', $cart);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب محتويات السلة', null);
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

        $user = auth('user-api')->user();
        if ($validator->fails()) {
            return $this->error_response($validator->errors()->first(), null);
        }

        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            $result = CartRepository()->removeItem($request->course_id);
            
            // Get updated cart data
            $cartData = CartRepository()->getCartCoursesWithStatus();
            
            if ($result && isset($result->original) && $result->original['success']) {
                return $this->success_response($result->original['message'], [
                    'success' => true,
                    'message' => $result->original['message'],
                    'courses_count' => $cartData['courses']->count(),
                    'cart_data' => [
                        'courses' => $cartData['courses'],
                        'is_package' => $cartData['is_package'],
                        'package_info' => $cartData['package_info']
                    ]
                ]);
            }

            return $this->error_response('حدث خطأ أثناء حذف الكورس من السلة', null);
            
        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء حذف الكورس من السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * مسح السلة بالكامل
     */
    public function clearCart()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            CartRepository()->clearCart();
            
            return $this->success_response('تم مسح السلة بنجاح', [
                'success' => true,
                'message' => 'تم مسح السلة بنجاح',
                'courses_count' => 0
            ]);
            
        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء مسح السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Debug cart session - for testing purposes
     */
    public function debugCart()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            $sessionKey = 'cart_' . $user->id;
            $packageSessionKey = 'package_cart_' . $user->id;
            
            $debugData = [
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'cart_session_key' => $sessionKey,
                'package_session_key' => $packageSessionKey,
                'cart_session_data' => session($sessionKey, []),
                'package_session_data' => session($packageSessionKey, []),
                'all_session_data' => session()->all(),
                'course_cart' => CartRepository()->getCourseCart(),
                'package_cart' => CartRepository()->getPackageCart(),
            ];

            return $this->success_response('بيانات تشخيص السلة', $debugData);
            
        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء تشخيص السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get cart summary
     */
    public function getCartSummary()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            $cartData = CartRepository()->getCartCoursesWithStatus();
            
            $summary = [
                'courses_count' => $cartData['courses']->count(),
                'total_amount' => $cartData['courses']->sum('selling_price'),
                'original_amount' => $cartData['courses']->sum('price'),
                'discount_amount' => $cartData['courses']->sum('price') - $cartData['courses']->sum('selling_price'),
                'is_package' => $cartData['is_package'],
                'can_checkout' => $cartData['courses']->count() > 0,
                'package_info' => $cartData['package_info']
            ];

            return $this->success_response('ملخص السلة', $summary);
            
        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب ملخص السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * حذف الباكدج من السلة
     */
    public function removeCartFromAnyPackage(Request $request)
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        $result = CartRepository()->removeAnyPackageFromCart();
        
        if ($result && isset($result->original) && $result->original['success']) {
            return $this->success_response($result->original['message'], $result->original);
        }

        return $this->error_response('حدث خطأ أثناء حذف الباكدج من السلة', null);
    }

    /**
     * حذف كورس من الباكدج
     */
    public function removeCourseFromPackage(Request $request)
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response(__('messages.login first'), null);
        }

        if (!$request->package_id || !$request->course_id) {
            return $this->error_response(__('messages.unexpected_error'), null);
        }

        $result = CartRepository()->removeCourseFromPackage($request->package_id, $request->course_id);
        
        if ($result && isset($result->original) && $result->original['success']) {
            return $this->success_response($result->original['message'], $result->original);
        }

        return $this->error_response('حدث خطأ أثناء حذف الكورس من الباكدج', null);
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

        $user = auth('user-api')->user();
        if ($validator->fails()) {
            return $this->error_response($validator->errors()->first(), null);
        }

        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        // التحقق من البطاقة
        $cardValidation = $this->validateCard($request->card_number);
        if (!$cardValidation['valid']) {
            return $this->error_response($cardValidation['message'], null);
        }

        $cardNumber = $cardValidation['card'];
        // get course as collection to use same functions
        $courses = Course::where('id', $request->course_id)->get();
        $course = (clone $courses)->first();
        $courseId = $course?->id;

        // التحققات
        $enrollmentCheck = $this->checkEnrollmentEligibility($user?->id, $course, $cardNumber);
        if (!$enrollmentCheck['valid']) {
            return $this->error_response($enrollmentCheck['message'], null);
        }

        // تنفيذ عملية التسجيل
        DB::beginTransaction();
        try {
            $this->enrollUserInCourse($user?->id, $course);
            $this->markCardAsUsed($cardNumber, $user?->id);
            $this->createPaymentRecord($user?->id, $courses, $cardNumber, 'single');

            CartRepository()->removeItem($courseId);

            DB::commit();

            return $this->success_response('تم تفعيل البطاقة وإضافتك للكورس بنجاح', [
                'course_id' => $courseId,
                'enrollment_status' => 'active'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->error_response('حدث خطأ: ' . $e->getMessage(), null);
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

        $user = auth('user-api')->user();
        if ($validator->fails()) {
            return $this->error_response($validator->errors()->first(), null);
        }

        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        // التحقق من البطاقة
        $cardValidation = $this->validateCard($request->card_number);
        if (!$cardValidation['valid']) {
            return $this->error_response($cardValidation['message'], null);
        }

        $cardNumber = $cardValidation['card'];

        // الحصول على الكورسات الصالحة للشراء
        $validCourses = $this->getValidCoursesForPurchase($user?->id);
        if ($validCourses->isEmpty()) {
            return $this->error_response(translate_lang('cart_is_empty'), null);
        }

        $totalCost = $validCourses->sum('selling_price');

        // التحقق من قيمة البطاقة
        if ($cardNumber->card->price != $totalCost) {
            return $this->error_response('يجب أن تساوي قيمة البطاقة سعر الكورسات', null);
        }

        // تنفيذ عملية الشراء
        DB::beginTransaction();
        try {
            foreach ($validCourses as $course) {
                $this->enrollUserInCourse($user?->id, $course);
            }

            $this->markCardAsUsed($cardNumber, $user?->id);
            $this->createPaymentRecord($user?->id, $validCourses, $cardNumber, 'courses');

            CartRepository()->clearCart();

            DB::commit();

            return $this->success_response(translate_lang('card_activated'), [
                'enrolled_courses' => $validCourses->pluck('id'),
                'total_amount' => $totalCost,
                'courses_count' => $validCourses->count()
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->error_response('حدث خطأ: ' . $e->getMessage(), null);
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

        $user = auth('user-api')->user();
        if ($validator->fails()) {
            return $this->error_response($validator->errors()->first(), null);
        }

        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        // التحقق من البطاقة
        $cardValidation = $this->validateCard($request->card_number);
        if (!$cardValidation['valid']) {
            return $this->error_response($cardValidation['message'], null);
        }

        $cardNumber = $cardValidation['card'];
        $packageCart = CartRepository()->getPackageCart();

        if (!$packageCart || empty($packageCart['courses'])) {
            return $this->error_response('لا توجد باكدج في السلة', null);
        }

        // الحصول على الكورسات الصالحة للشراء من الباكدج
        $validCourses = $this->getValidPackageCoursesForPurchase($user?->id, $packageCart);
        if ($validCourses->isEmpty()) {
            return $this->error_response(translate_lang('cart_is_empty'), null);
        }

        // التحقق من عدد الكورسات
        if ($validCourses->count() != $packageCart['max_courses']) {
            return $this->error_response('يجب أن تحتوي السلة على ' . $packageCart['max_courses'] . ' كورسات صالحة وغير مشترك فيها', null);
        }

        // التحقق من قيمة البطاقة
        if ($cardNumber->card->price != $packageCart['package_price']) {
            return $this->error_response('يجب أن تساوي قيمة البطاقة سعر الباكدج', null);
        }

        // تنفيذ عملية الشراء
        DB::beginTransaction();
        try {
            foreach ($validCourses as $course) {
                $this->enrollUserInCourse($user?->id, $course);
            }

            $this->markCardAsUsed($cardNumber, $user?->id);
            $this->createPaymentRecord($user?->id, $validCourses, $cardNumber, 'package', $packageCart['package_id']);

            CartRepository()->clearCart();

            DB::commit();

            return $this->success_response(translate_lang('card_activated'), [
                'enrolled_courses' => $validCourses->pluck('id'),
                'package_id' => $packageCart['package_id'],
                'package_price' => $packageCart['package_price'],
                'courses_count' => $validCourses->count()
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->error_response('حدث خطأ: ' . $e->getMessage(), null);
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
        // التحقق من الاشتراك السابق
        if (CourseUser::where('user_id', $userId)->where('course_id', $course->id)->exists()) {
            return [
                'valid' => false,
                'message' => 'أنت مشترك بالفعل في هذا الكورس'
            ];
        }

        // التحقق من حالة الكورس
        if (!$course->is_active) {
            return [
                'valid' => false,
                'message' => 'هذا الكورس غير متاح حالياً'
            ];
        }

        // التحقق من قيمة البطاقة
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
}