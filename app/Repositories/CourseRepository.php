<?php
namespace App\Repositories;

use App\Models\Course;
use App\Models\Category;
use App\Models\CourseUser;
use App\Models\Package;

class CourseRepository
{
    public $model;
    public function __construct()
    {
        $this->model = new Course;
    }

    // query direct courses under courses
    // @param categoryId = Category.id
    // return collection
    public function getDirectCategoryCourses($subjectId)
    {
        $courses = [];
        $courses = Course::where('subject_id',$subjectId)->get();
        return $courses;
    }


    // user courses section

    // query enrollend courses for currnt login user
    public function userCourses($userId)
    {
        if(!$userId) return null;
        $coursesIds = CourseUser::where('user_id',$userId)->pluck('course_id')->toArray();
        if($coursesIds && count($coursesIds)) return Course::whereIn('id',$coursesIds);
        return null;
    }

    // query->get() enrollend courses for currnt login user
    public function getUserCourses($userId)
    {
        if(!$userId) return null;
        return $this->userCourses($userId)->get();
    }

    // query->get() enrollend courses ids for currnt login user
    public function getUserCoursesIds($userId)
    {
        if(!$userId) return [];
        return $this->userCourses($userId)?->pluck('id')->toArray() ?? [];
    }

}
