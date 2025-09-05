<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Course;
use App\Models\Package;
use App\Models\CourseUser;
use Illuminate\Support\Facades\Session;

class CartRepository
{
    protected $cart_session;
    protected $package_cart_session;
    protected $expiry_days;
    protected $user;

    public function __construct()
    {
        $this->cart_session         = Session::get('courses', []);
        $this->package_cart_session = Session::get('package_cart', []);
        $this->expiry_days          = env('COMPLETED_WATCHING_COURSES', 30);
        $this->user                 = auth_student();
    }

    /**
     * تنظيف السلة عند البداية - التأكد من عدم وجود الباكدج والكورسات معاً
     */
    public function initializeCart()
    {
        // إذا وجدت باكدج، احذف الكورسات العادية
        if ($this->hasPackage()) {
            Session::forget('courses');
            $this->cart_session = [];
        }
    }

    /**
     * إضافة كورس للسلة
     */
    public function put($courseId)
    {
        // التحقق من الصلاحية
        if (!$this->validCartContent(null, $courseId)) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن الجمع بين الكورسات المنفردة والباكدجات في السلة'
            ]);
        }

        // التحقق من الاشتراك السابق
        if ($this->isUserEnrolled($courseId)) {
            return response()->json([
                'success' => false,
                'message' => 'أنت مشترك بالفعل في هذا الكورس'
            ]);
        }

        // التحقق من أن الكورس نشط
        $course = Course::find($courseId);
        if (!$course || !$course->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الكورس غير متاح حالياً'
            ]);
        }

        $courses = $this->cart_session;

        // تجنب التكرار
        if (!in_array($courseId, $courses)) {
            $courses[] = $courseId;
            Session::put('courses', $courses);
            Session::put('courses_expiry', now()->addDays($this->expiry_days));

            return response()->json([
                'success' => true,
                'message' => __('messages.course_added_successfully'),
                'courses_count' => count($courses)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('messages.course_already_in_cart'),
            'courses_count' => count($courses)
        ]);
    }

    /**
     * حذف كورس من السلة
     */
    public function removeItem($courseId)
    {
        // حذف من كورسات الباكدج
        if ($this->hasPackage()) {
            $package = $this->getPackageCart();
            if (in_array($courseId, $package['courses'] ?? [])) {
                $package['courses'] = array_values(array_filter(
                    $package['courses'],
                    fn($id) => $id != $courseId
                ));
                Session::put('package_cart', $package);

                return response()->json([
                    'success' => true,
                    'message' => __('messages.course_removed'),
                    'remaining_count' => count($package['courses']),
                    'required_count' => $package['max_courses']
                ]);
            }
        }

        // حذف من الكورسات العادية
        $courses = $this->cart_session;
        $filteredCourses = array_values(array_filter(
            $courses,
            fn($id) => $id != $courseId
        ));

        Session::put('courses', $filteredCourses);

        return response()->json([
            'success' => true,
            'message' => __('messages.course_removed'),
            'courses_count' => count($filteredCourses)
        ]);
    }


    /**
     * إزالة كورس من باكدج
     */
    public function removeCourseFromPackage($packageId ,$courseToRemove)
    {
        $cart       = $this->getPackageCart();
        if($cart['courses'] && count($cart['courses']) && $cart['package_id'] == $packageId){
            $courses = $cart['courses'];
            $filteredCourses = array_filter($courses, function ($course) use ($courseToRemove) {
                return $course != $courseToRemove;
            });
            $this->setPackageCart($packageId, $filteredCourses);

            if(!count($filteredCourses)) Session::forget('package_cart');

            return response()->json([
                'success' => true,
                'message' => __('messages.course_removed'),
            ]);

        }

        return response()->json([
            'success' => false,
            'message' => __('messages.unexpected_error'),
        ]);
    }

    /**
     * إعداد سلة الباكدج
     */
    public function setPackageCart($packageId, array $courseIds)
    {
        $package = Package::find($packageId);
        if (!$package) {
            throw new \Exception('Package not found');
        }

        // التحقق من عدد الكورسات
        // if (count($courseIds) != $package->how_much_course_can_select) {
        //     throw new \Exception("يجب اختيار {$package->how_much_course_can_select} كورسات بالضبط");
        // }

        // التحقق من أن المستخدم غير مشترك في الكورسات
        $enrolledCourses = $this->getEnrolledCoursesFromList($courseIds);
        if (!empty($enrolledCourses)) {
            $enrolledNames = Course::whereIn('id', $enrolledCourses)->pluck('title_'.getLocale())->implode(', ');
            throw new \Exception("أنت مشترك بالفعل في: {$enrolledNames}");
        }

        // التحقق من أن جميع الكورسات نشطة
        $inactiveCourses = $this->getInactiveCoursesFromList($courseIds);
        if (!empty($inactiveCourses)) {
            $inactiveNames = Course::whereIn('id', $inactiveCourses)->pluck('title_'.getLocale())->implode(', ');
            throw new \Exception("الكورسات التالية غير متاحة: {$inactiveNames}");
        }

        $cart = [
            'package_id' => $packageId,
            'package_name' => $package->name,
            'package_price' => $package->price,
            'max_courses' => $package->how_much_course_can_select,
            'courses' => array_values($courseIds)
        ];

        Session::put('package_cart', $cart);
        Session::forget('courses'); // حذف الكورسات العادية

        return $cart;
    }

    /**
     * الحصول على سلة الباكدج
     */
    public function getPackageCart()
    {
        return Session::get('package_cart', []);
    }

    /**
     * الحصول على سلة الكورسات العادية
     */
    public function getCourseCart()
    {
        return Session::get('courses', []);
    }

    /**
     * مسح السلة بالكامل
     */
    public function clearCart()
    {
        Session::forget('package_cart');
        Session::forget('courses');

        return response()->json([
            'success' => true,
            'message' => __('messages.cart_cleared')
        ]);
    }

    /**
     * حذف الباكدج من السلة
     */
    public function removeAnyPackageFromCart()
    {
        Session::forget('package_cart');

        return response()->json([
            'success' => true,
            'message' => __('messages.package_removed')
        ]);
    }

    /**
     * التحقق من صحة محتوى السلة
     */
    public function validCartContent($packageId = null, $courseId = null)
    {
        $hasPackage = $this->hasPackage();
        $hasCourses = !empty($this->getCourseCart());

        // إذا كنت تحاول إضافة باكدج
        if ($packageId) {
            // لا يمكن إضافة باكدج إذا كان هناك كورسات عادية أو باكدج آخر
            if ($hasCourses || ($hasPackage && $this->getCurrentPackageId() != $packageId)) {
                return false;
            }
        }

        // إذا كنت تحاول إضافة كورس عادي
        if ($courseId) {
            // لا يمكن إضافة كورس عادي إذا كان هناك باكدج
            if ($hasPackage) {
                return false;
            }
        }

        return true;
    }

    /**
     * التحقق من وجود باكدج في السلة
     */
    public function hasPackage()
    {
        $package = $this->getPackageCart();
        return !empty($package) && isset($package['package_id']);
    }

    /**
     * الحصول على معرف الباكدج الحالي
     */
    public function getCurrentPackageId()
    {
        $cart = $this->getPackageCart();
        return $cart['package_id'] ?? null;
    }

    /**
     * الحصول على عدد الكورسات في سلة الباكدج
     */
    public function getPackageCartCount()
    {
        $cart = $this->getPackageCart();
        return isset($cart['courses']) ? count($cart['courses']) : 0;
    }

    /**
     * التحقق من اشتراك المستخدم في كورس
     */
    protected function isUserEnrolled($courseId)
    {
        if (!$this->user) {
            return false;
        }

        return CourseUser::where('user_id', $this->user->id)
                        ->where('course_id', $courseId)
                        ->exists();
    }

    /**
     * الحصول على الكورسات المشترك فيها من قائمة معينة
     */
    protected function getEnrolledCoursesFromList(array $courseIds)
    {
        if (!$this->user) {
            return [];
        }

        return CourseUser::where('user_id', $this->user->id)
                        ->whereIn('course_id', $courseIds)
                        ->pluck('course_id')
                        ->toArray();
    }

    /**
     * الحصول على الكورسات غير النشطة من قائمة معينة
     */
    protected function getInactiveCoursesFromList(array $courseIds)
    {
        return Course::whereIn('id', $courseIds)
                    ->notActive()
                    ->pluck('id')
                    ->toArray();
    }

    /**
     * الحصول على تفاصيل كورسات السلة مع حالة الاشتراك
     */
    public function getCartCoursesWithStatus()
    {
        $courseIds = [];
        $isPackage = false;
        $packageInfo = null;

        if ($this->hasPackage()) {
            $package = $this->getPackageCart();
            $courseIds = $package['courses'] ?? [];
            $isPackage = true;
            $packageInfo = $package;
        } else {
            $courseIds = $this->getCourseCart();
        }

        if (empty($courseIds)) {
            return [
                'courses' => collect(),
                'is_package' => false,
                'package_info' => null
            ];
        }

        $courses = Course::whereIn('id', $courseIds)->get();

        // إضافة معلومات الحالة لكل كورس
        $courses = $courses->map(function ($course) {
            $course->is_enrolled = $this->isUserEnrolled($course->id);
            $course->can_purchase = $course->is_active && !$course->is_enrolled;
            return $course;
        });

        return [
            'courses' => $courses,
            'is_package' => $isPackage,
            'package_info' => $packageInfo
        ];
    }
}
