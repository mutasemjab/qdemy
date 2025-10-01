<?php

namespace App\Http\Controllers\Api\v1\User;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\CardNumber;
use App\Models\CardUsage;
use App\Models\ContentUserProgress;
use App\Models\CourseContent;
use App\Models\CoursePayment;
use App\Models\CoursePaymentDetail;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Repositories\CartRepository;


class EnrollmentController extends Controller
{
    use Responses;

    /**
     * Get the appropriate cart repository based on request type
     */
    private function getCartRepository()
    {
        // Check if request is from API (mobile)
        if (request()->is('api/*') || auth()->guard('user-api')->check()) {
            return new \App\Repositories\MobileCartRepository();
        }
        
        // Default to web cart repository
        return new \App\Repositories\CartRepository();
    }

    /**
     * عرض محتويات السلة
     */
    public function index()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        $cartRepository = $this->getCartRepository();

        // تنظيف السلة عند البداية
        $cartRepository->initializeCart();

        // الحصول على البيانات من السلة
        $cartData = $cartRepository->getCartCoursesWithStatus();

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

        try {
            $cartRepository = $this->getCartRepository();
            $result = $cartRepository->put($request->course_id);

            if ($result && isset($result->original)) {
                if ($result->original['success']) {
                    return $this->success_response($result->original['message'], $result->original);
                } else {
                    return $this->error_response($result->original['message'], null);
                }
            }

            return $this->error_response('حدث خطأ أثناء إضافة الكورس للسلة', null);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ: ' . $e->getMessage(), null);
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
            $cartRepository = $this->getCartRepository();
            $result = $cartRepository->removeItem($request->course_id);

            // Get updated cart data
            $cartData = $cartRepository->getCartCoursesWithStatus();

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
            $cartRepository = $this->getCartRepository();
            $cartRepository->clearCart();

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
            $cartRepository = $this->getCartRepository();
            $isApiRequest = request()->is('api/*') || auth()->guard('user-api')->check();
            
            $debugData = [
                'user_id' => $user->id,
                'request_type' => $isApiRequest ? 'API (Mobile)' : 'Web',
                'repository_type' => get_class($cartRepository),
                'course_cart' => $cartRepository->getCourseCart(),
                'package_cart' => $cartRepository->getPackageCart(),
            ];

            // Add session data only for web requests
            if (!$isApiRequest) {
                $sessionKey = 'cart_' . $user->id;
                $packageSessionKey = 'package_cart_' . $user->id;
                
                $debugData = array_merge($debugData, [
                    'session_id' => session()->getId(),
                    'cart_session_key' => $sessionKey,
                    'package_session_key' => $packageSessionKey,
                    'cart_session_data' => session($sessionKey, []),
                    'package_session_data' => session($packageSessionKey, []),
                    'all_session_data' => session()->all(),
                ]);
            } else {
                $debugData['cache_keys'] = [
                    'courses' => "mobile_cart_courses_{$user->id}",
                    'package' => "mobile_cart_package_{$user->id}"
                ];
            }

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
            $cartRepository = $this->getCartRepository();
            $cartData = $cartRepository->getCartCoursesWithStatus();

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

        $cartRepository = $this->getCartRepository();
        $result = $cartRepository->removeAnyPackageFromCart();

        if ($result && isset($result->original) && $result->original['success']) {
            return $this->success_response($result->original['message'], $result->original);
        }

        return $this->error_response('حدث خطأ أثناء حذف الباكدج من السلة', null);
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
        $validCourses = $this->getValidCoursesForPurchase($user->id);
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
                $this->enrollUserInCourse($user->id, $course);
                // خصم العمولة من كل معلم
                $this->deductCommissionFromTeacher($course);
            }

            $this->markCardAsUsed($cardNumber, $user->id);
            $this->createPaymentRecord($user->id, $validCourses, $cardNumber, 'courses');

            $cartRepository = $this->getCartRepository();
            $cartRepository->clearCart();

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
        $cartRepository = $this->getCartRepository();
        $courseIds = $cartRepository->getCourseCart();

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
     * خصم العمولة من المعلم وتسجيلها في المحفظة
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

    public function getUserEnrolledCourses(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->error_response('User not authenticated', null);
            }

            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');
            $sortBy = $request->get('sort_by', 'latest');

            $query = Course::whereHas('enrollments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['teacher', 'subject.grade', 'subject.semester', 'subject.program']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%");
                });
            }

            switch ($sortBy) {
                case 'name':
                    $query->orderBy('title_ar', 'asc');
                    break;
                case 'progress':
                    break;
                default:
                    $query->latest();
            }

            $courses = $query->paginate($perPage);

            $coursesData = $courses->getCollection()->map(function ($course) use ($user) {
                $calculateCourseProgress = $this->getCourseProgressData($user->id, $course->id);
                $enrollment = $course->enrollments()->where('user_id', $user->id)->first();

                return [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                    'description_en' => $course->description_en,
                    'description_ar' => $course->description_ar,
                    'selling_price' => $course->selling_price,
                    'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                    'enrollment_date' => $enrollment ? $enrollment->created_at : null,
                    'teacher' => $course->teacher ? [
                        'id' => $course->teacher->id,
                        'name' => $course->teacher->name,
                        'name_of_lesson' => $course->teacher->name_of_lesson,
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : null,
                    ] : null,
                    'subject' => $course->subject ? [
                        'id' => $course->subject->id,
                        'name_ar' => $course->subject->name_ar,
                        'name_en' => $course->subject->name_en,
                        'color' => $course->subject->color,
                        'icon' => $course->subject->icon,
                        'grade_info' => $course->subject->grade ? [
                            'id' => $course->subject->grade->id,
                            'name_ar' => $course->subject->grade->name_ar,
                            'name_en' => $course->subject->grade->name_en,
                            'level' => $course->subject->grade->level
                        ] : null,
                        'semester_info' => $course->subject->semester ? [
                            'id' => $course->subject->semester->id,
                            'name_ar' => $course->subject->semester->name_ar,
                            'name_en' => $course->subject->semester->name_en
                        ] : null,
                        'program_info' => $course->subject->program ? [
                            'id' => $course->subject->program->id,
                            'name_ar' => $course->subject->program->name_ar,
                            'name_en' => $course->subject->program->name_en
                        ] : null
                    ] : null,
                    'user_progress' => [
                        'course_progress' => $calculateCourseProgress['course_progress'],
                        'completed_videos' => $calculateCourseProgress['completed_videos'],
                        'watching_videos' => $calculateCourseProgress['watching_videos'],
                        'total_videos' => $calculateCourseProgress['total_videos']
                    ],
                    'created_at' => $course->created_at,
                    'updated_at' => $course->updated_at
                ];
            });

            if ($sortBy === 'progress') {
                $coursesData = $coursesData->sortByDesc('user_progress.course_progress')->values();
            }

            $responseData = [
                'enrolled_courses' => $coursesData,
                'filters' => [
                    'search' => $search,
                    'sort_by' => $sortBy
                ],
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                    'has_more_pages' => $courses->hasMorePages()
                ]
            ];

            return $this->success_response('User enrolled courses retrieved successfully', $responseData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve enrolled courses: ' . $e->getMessage(), null);
        }
    }

    private function getCourseProgressData($userId, $courseId)
    {
        $course = Course::find($courseId);
        $progressPercentage = $course->calculateCourseProgress($userId);
        
        $totalVideos = CourseContent::where('course_id', $courseId)
            ->where('content_type', 'video')
            ->count();
        
        $completedVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->where('content_user_progress.completed', true)
            ->count();
        
        $watchingVideos = 0;
        
        return [
            'course_progress' => $progressPercentage,
            'completed_videos' => $completedVideos,
            'watching_videos' => $watchingVideos,
            'total_videos' => $totalVideos
        ];
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

        $user = auth('user-api')->user();
        if ($validator->fails()) {
            return $this->error_response($validator->errors()->first(), null);
        }

        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            $cartRepository = $this->getCartRepository();
            
            if (!$cartRepository->validCartContent($request->package_id)) {
                $cartRepository->clearCart();
            }

            $cart = $cartRepository->setPackageCart($request->package_id, $request->courses);

            return $this->success_response('تم تحديث السلة بنجاح', [
                'success' => true,
                'cart' => $cart,
                'message' => 'تم تحديث السلة بنجاح'
            ]);

        } catch (\Exception $e) {
            return $this->error_response($e->getMessage(), null);
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

        $cardValidation = $this->validateCard($request->card_number);
        if (!$cardValidation['valid']) {
            return $this->error_response($cardValidation['message'], null);
        }

        $cardNumber = $cardValidation['card'];
        $cartRepository = $this->getCartRepository();
        $packageCart = $cartRepository->getPackageCart();

        if (!$packageCart || empty($packageCart['courses'])) {
            return $this->error_response('لا توجد باكدج في السلة', null);
        }

        $validCourses = $this->getValidPackageCoursesForPurchase($user->id, $packageCart);
        if ($validCourses->isEmpty()) {
            return $this->error_response(translate_lang('cart_is_empty'), null);
        }

        if ($validCourses->count() != $packageCart['max_courses']) {
            return $this->error_response('يجب أن تحتوي السلة على ' . $packageCart['max_courses'] . ' كورسات صالحة وغير مشترك فيها', null);
        }

        if ($cardNumber->card->price != $packageCart['package_price']) {
            return $this->error_response('يجب أن تساوي قيمة البطاقة سعر الباكدج', null);
        }

        DB::beginTransaction();
        try {
            foreach ($validCourses as $course) {
                $this->enrollUserInCourse($user->id, $course);
            }

            $this->markCardAsUsed($cardNumber, $user->id);
            $this->createPaymentRecord($user->id, $validCourses, $cardNumber, 'package', $packageCart['package_id']);

            $cartRepository->clearCart();

            DB::commit();

            return $this->success_response(translate_lang('card_activated'), [
                'enrolled_courses' => $validCourses->pluck('id'),
                'total_amount' => $packageCart['package_price'],
                'courses_count' => $validCourses->count(),
                'package_id' => $packageCart['package_id']
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->error_response('حدث خطأ: ' . $e->getMessage(), null);
        }
    }
}
