<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Course;
use App\Models\Package;
use App\Models\CourseUser;
use Illuminate\Support\Facades\Session;

class CartRepository
{
    public $cart_session;
    public $package_cart_session;
    public $expiry_days;
    public $user;

    public function __construct()
    {
        $this->cart_session         = Session::get('courses', []);
        $this->package_cart_session = Session::get('package_cart', []);
        $this->expiry_days  = env('COMPLETED_WATCHING_COURSES', 30);
        $this->user         = auth_student();
    }
    // put course id courses session
    // @param $courseId = course->id
    public function put($courseId)
    {
        $courses = $this->cart_session;

        $coursesIds = array_filter($courses, function ($course) use($courseId){
            return !CourseUser::where('user_id', $this->user?->id)->where('course_id', $courseId)->exists();
        });

        // تجنب تكرار الكورس
        if (!in_array($courseId, $coursesIds)) {

            $coursesIds[] = $courseId;
            Session::put('courses', $coursesIds);
            // تعيين صلاحية شهر للجلسة
            $this->expiry_days = env('COMPLETED_WATCHING_COURSES', 30);
            Session::put('courses_expiry', now()->addDays($this->expiry_days));
        }else{
            return response()->json([
                'success' => true,
                'message' => __('messages.course_already_in_cart'),
                'courses_count' => count($coursesIds)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.course_added_successfully'),
            'courses_count' => count($coursesIds)
        ]);

    }

    // remove course from courses session
    // @param $courseToRemove = course->id
    // $courses from cart_session = cart courses ids
    public function removeItem($courseToRemove)
    {
        $courses = $this->cart_session;
        $filteredCourses = array_filter($courses, function ($course) use ($courseToRemove) {
            return $course != $courseToRemove;
        });

        Session::put('courses', $filteredCourses);

        return response()->json([
            'success' => true,
            'message' => __('messages.course_removed'),
        ]);
    }

    /**
     * التحقق من أن الكورسات تنتمي للباقة
     */
    public function validateCoursesForPackage(array $courseIds, $packageId)
    {
        $package = Package::with('categories')->find($packageId);
        $categoryIds = $package->categories->pluck('id')->toArray();

        $validCount = Course::whereIn('id', $courseIds)
            ->whereHas('categories', function($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })->count();
                return response()->json([
                    'success' => false,
                    'message' => 'بعض الكورسات لا تنتمي لهذه الباقة',
                    'validCount' => $validCount,
                    'courseIds' => $courseIds,
                ], 400);
        return $validCount == count($courseIds);
    }

    /**
     * إضافة كورسات الباقة للسلة
     */
    public function setPackageCart($packageId, array $courseIds)
    {
        $package = Package::find($packageId);

        $cart = [
            'package_id' => $packageId,
            'package_name' => $package->name,
            'package_price' => $package->price,
            'max_courses' => $package->how_much_course_can_select,
            'courses' => array_values($courseIds)
        ];

        Session::put('package_cart', $cart);
        return $cart;
    }

    /**
     * الحصول على محتويات من الباكدجاتالسلة
     */
    public function getPackageCart()
    {
        return $this->package_cart_session;
    }

    /**
     * الحصول على محتويا من الكورسات
     */
    public function getCourseCart()
    {
        return $this->cart_session;;
    }

    /**
     * مسح السلة
     */
    public function clearCart()
    {
        Session::forget('package_cart');
        Session::forget('courses');
        return response()->json([
            'success' => true,
            'message' => __('messages.courses_removed'),
        ]);
    }

    /**
     * التحقق من وجود باقة في واحدة ف السلة
     * وعدم وجود باقات اخري او كورسات منفردة
     */
    public function validCartContent($packageId = null,$courseId = null)
    {
        $other_courses = $this->cart_session;
        $package_cart  = $this->getPackageCart();
        return !($courseId && $package_cart);
        return !($other_courses || isset($cart['package_id']) && $packageId && $packageId != $cart['package_id']);
    }

    /**
     * الحصول على معرف الباقة الحالية في السلة
     */
    public function getCurrentPackageId()
    {
        $cart = $this->getPackageCart();
        return $cart['package_id'] ?? null;
    }

    /**
     * الحصول على عدد الكورسات في السلة
     */
    public function getPackageCartCount()
    {
        $cart = $this->getPackageCart();
        return isset($cart['courses']) ? count($cart['courses']) : 0;
    }

    /**
     * إزالة كورس من السلة
     */
    public function removeAnyPackageFromCart()
    {
        Session::forget('package_cart');
        return;
    }
}
