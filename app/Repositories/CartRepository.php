<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\CourseUser;
use Illuminate\Support\Facades\Session;

class CartRepository
{
    public $cart_session;
    public $expiry_days;
    public $user;

    public function __construct()
    {
        $this->cart_session = Session::get('courses', []);
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

    // remove courses from courses session
    public function forgetAll()
    {
       Session::forget('courses');
        return response()->json([
            'success' => true,
            'message' => __('messages.courses_removed'),
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

}
