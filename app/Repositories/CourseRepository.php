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
        app()->setLocale('ar');
    }

    // query for all courses under univertisy programm
    // courses is directly under univertisy programm
    public function universitiesProgramSubjects()
    {
        $universityProgramId = CategoryRepository()->getUniversitiesProgram()?->id;
        if($universityProgramId){
            return $this->model->where('category_id',$universityProgramId);
        }
        return [];
    }

    // query->get() for all courses under univertisy programm
    public function getuniversitiesProgramSubjects()
    {
        return $this->universitiesProgramSubjects()->get();
    }

    // query for all courses under international programm
    // courses is directly under international programm
    public function internationalProgramSubjects($programm = null)
    {
        $internationalProgramId = CategoryRepository()->getInternationalProgram()?->id;
        if($internationalProgramId){
            return $this->model->where('category_id',$internationalProgramId);
        }
        return [];
    }

    // query->get() for all courses under international programm
    public function getinternationalProgramSubjects($programm)
    {
        return $this->internationalProgramSubjects($programm)->get();
    }

    // query direct courses under courses
    // @param categoryId = Category.id
    // return collection
    public function getDirectCategoryCourses($categoryId)
    {
        $courses = [];
        $courses = Course::where('category_id',$categoryId)->get();
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
