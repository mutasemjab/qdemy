<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\CardNumber;
use App\Models\CardUsage;
use App\Models\CoursePayment;
use App\Models\CoursePaymentDetail;
use App\Models\Package;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class EnrollmentController extends Controller
{
    use Responses;

   /**
     * Get cart key for user
     */
    private function getCartKey($userId, $type = 'courses')
    {
        return "user_cart_{$userId}_{$type}";
    }

    /**
     * Get cart contents using Cache instead of Session
     */
    public function index()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            $courseIds = Cache::get($this->getCartKey($user->id, 'courses'), []);
            $packageCart = Cache::get($this->getCartKey($user->id, 'package'), []);

            $isPackage = !empty($packageCart);
            $finalCourseIds = $isPackage ? ($packageCart['courses'] ?? []) : $courseIds;

            $courses = collect();
            if (!empty($finalCourseIds)) {
                $courses = Course::whereIn('id', $finalCourseIds)->get();
                
                // Add enrollment status
                $courses = $courses->map(function ($course) use ($user) {
                    $course->is_enrolled = CourseUser::where('user_id', $user->id)
                                                   ->where('course_id', $course->id)
                                                   ->exists();
                    $course->can_purchase = $course->is_active && !$course->is_enrolled;
                    return $course;
                });
            }

            return $this->success_response('تم جلب محتويات السلة بنجاح', [
                'courses' => $courses,
                'is_package' => $isPackage,
                'package_info' => $isPackage ? $packageCart : null,
                'courses_count' => $courses->count(),
                'total_amount' => $courses->sum('selling_price'),
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب محتويات السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Add course to cart using Cache
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
            $courseId = $request->course_id;

            // Check if user is already enrolled
            if (CourseUser::where('user_id', $user->id)->where('course_id', $courseId)->exists()) {
                return $this->error_response('أنت مشترك بالفعل في هذا الكورس', null);
            }

            // Check if course is active
            $course = Course::find($courseId);
            if (!$course || !$course->is_active) {
                return $this->error_response('هذا الكورس غير متاح حالياً', null);
            }

            // Check for package cart conflict
            $packageCart = Cache::get($this->getCartKey($user->id, 'package'), []);
            if (!empty($packageCart)) {
                return $this->error_response('لا يمكن الجمع بين الكورسات المنفردة والباكدجات في السلة', null);
            }

            // Get current courses
            $courses = Cache::get($this->getCartKey($user->id, 'courses'), []);

            // Avoid duplicates
            if (!in_array($courseId, $courses)) {
                $courses[] = $courseId;
                Cache::put($this->getCartKey($user->id, 'courses'), $courses, now()->addDays(30));

                // Get updated cart data
                $updatedCourses = Course::whereIn('id', $courses)->get();

                return $this->success_response('تم إضافة الكورس بنجاح', [
                    'success' => true,
                    'message' => 'تم إضافة الكورس بنجاح',
                    'courses_count' => count($courses),
                    'cart_data' => [
                        'courses' => $updatedCourses,
                        'is_package' => false,
                        'package_info' => null
                    ]
                ]);
            }

            return $this->error_response('الكورس موجود بالفعل في السلة', null);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء إضافة الكورس للسلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Remove course from cart
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
            $courseId = $request->course_id;

            // Check package cart first
            $packageCart = Cache::get($this->getCartKey($user->id, 'package'), []);
            if (!empty($packageCart) && in_array($courseId, $packageCart['courses'] ?? [])) {
                $packageCart['courses'] = array_values(array_filter(
                    $packageCart['courses'],
                    fn($id) => $id != $courseId
                ));
                
                if (empty($packageCart['courses'])) {
                    Cache::forget($this->getCartKey($user->id, 'package'));
                } else {
                    Cache::put($this->getCartKey($user->id, 'package'), $packageCart, now()->addDays(30));
                }

                return $this->success_response('تم حذف الكورس من السلة', [
                    'success' => true,
                    'remaining_count' => count($packageCart['courses']),
                    'required_count' => $packageCart['max_courses'] ?? 0
                ]);
            }

            // Remove from regular courses
            $courses = Cache::get($this->getCartKey($user->id, 'courses'), []);
            $filteredCourses = array_values(array_filter(
                $courses,
                fn($id) => $id != $courseId
            ));

            Cache::put($this->getCartKey($user->id, 'courses'), $filteredCourses, now()->addDays(30));

            return $this->success_response('تم حذف الكورس من السلة', [
                'success' => true,
                'courses_count' => count($filteredCourses)
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء حذف الكورس من السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update package cart
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
            $package = Package::find($request->package_id);
            if (!$package) {
                return $this->error_response('الباكدج غير موجود', null);
            }

            // Check for enrolled courses
            $enrolledCourses = CourseUser::where('user_id', $user->id)
                                       ->whereIn('course_id', $request->courses)
                                       ->pluck('course_id')
                                       ->toArray();

            if (!empty($enrolledCourses)) {
                $enrolledNames = Course::whereIn('id', $enrolledCourses)
                                     ->pluck('title_' . app()->getLocale())
                                     ->implode(', ');
                return $this->error_response("أنت مشترك بالفعل في: {$enrolledNames}", null);
            }

            // Check for inactive courses
            $inactiveCourses = Course::whereIn('id', $request->courses)
                                   ->where('is_active', false)
                                   ->pluck('id')
                                   ->toArray();

            if (!empty($inactiveCourses)) {
                $inactiveNames = Course::whereIn('id', $inactiveCourses)
                                     ->pluck('title_' . app()->getLocale())
                                     ->implode(', ');
                return $this->error_response("الكورسات التالية غير متاحة: {$inactiveNames}", null);
            }

            $cart = [
                'package_id' => $request->package_id,
                'package_name' => $package->name,
                'package_price' => $package->price,
                'max_courses' => $package->how_much_course_can_select,
                'courses' => array_values($request->courses)
            ];

            Cache::put($this->getCartKey($user->id, 'package'), $cart, now()->addDays(30));
            Cache::forget($this->getCartKey($user->id, 'courses')); // Clear regular courses

            return $this->success_response('تم تحديث السلة بنجاح', $cart);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء تحديث السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            Cache::forget($this->getCartKey($user->id, 'courses'));
            Cache::forget($this->getCartKey($user->id, 'package'));

            return $this->success_response('تم مسح السلة بنجاح', [
                'success' => true,
                'courses_count' => 0
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء مسح السلة: ' . $e->getMessage(), null);
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
            $courseIds = Cache::get($this->getCartKey($user->id, 'courses'), []);
            $packageCart = Cache::get($this->getCartKey($user->id, 'package'), []);

            $isPackage = !empty($packageCart);
            $finalCourseIds = $isPackage ? ($packageCart['courses'] ?? []) : $courseIds;

            $courses = Course::whereIn('id', $finalCourseIds)->get();

            $summary = [
                'courses_count' => $courses->count(),
                'total_amount' => $courses->sum('selling_price'),
                'original_amount' => $courses->sum('price'),
                'discount_amount' => $courses->sum('price') - $courses->sum('selling_price'),
                'is_package' => $isPackage,
                'can_checkout' => $courses->count() > 0,
                'package_info' => $isPackage ? $packageCart : null
            ];

            return $this->success_response('ملخص السلة', $summary);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب ملخص السلة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Debug cart - show cache contents
     */
    public function debugCart()
    {
        $user = auth('user-api')->user();
        if (!$user) {
            return $this->error_response('يجب تسجيل الدخول أولاً', null);
        }

        try {
            $debugData = [
                'user_id' => $user->id,
                'courses_cache_key' => $this->getCartKey($user->id, 'courses'),
                'package_cache_key' => $this->getCartKey($user->id, 'package'),
                'courses_cache_data' => Cache::get($this->getCartKey($user->id, 'courses'), []),
                'package_cache_data' => Cache::get($this->getCartKey($user->id, 'package'), []),
                'cache_driver' => config('cache.default'),
            ];

            return $this->success_response('بيانات تشخيص السلة', $debugData);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء تشخيص السلة: ' . $e->getMessage(), null);
        }
    }
}