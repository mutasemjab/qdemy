<?php
namespace App\Repositories;

use App\Models\Course;
use App\Models\Category;
use App\Models\CourseUser;

class CourseRepository
{
    public $model;
    public function __construct()
    {
        $this->model = new Course;
        app()->setLocale('ar');
    }

    public function universitiesProgramCourses()
    {
        $universitiesProgramsId = CategoryRepository()->universitiesPrograms()->first()?->id;
        $universitiesCoursesParents = [];
        if($universitiesProgramsId){
            $universitiesCoursesParents = CategoryRepository()->getAllSubChilds($universitiesProgramsId)->pluck('id')->toArray();
            return $this->model->whereIn('category_id',$universitiesCoursesParents);
        }
        return [];
    }

    public function getUniversitiesProgramCourses()
    {
        return $this->universitiesProgramCourses()->get();
    }

    public function internationalProgramCourses($programm)
    {
        $internationalCoursesParents = [];
        if($programm){
            $internationalCoursesParents = CategoryRepository()->getAllSubChilds($programm)->pluck('id')->toArray();
            return $this->model->whereIn('category_id',$internationalCoursesParents);
        }
        return [];
    }

    public function getInternationalProgramCourses($programm)
    {
        return $this->internationalProgramCourses($programm)->get();
    }

    public function userCourses($userId)
    {
        if(!$userId) return null;
        $coursesIds = CourseUser::where('user_id',$userId)->pluck('course_id')->toArray();
        if($coursesIds && count($coursesIds)) return Course::whereIn('id',$coursesIds);
        return null;
    }

    public function getUserCourses($userId)
    {
        if(!$userId) return null;
        return $this->userCourses($userId)->get();
    }

    public function getUserCoursesIds($userId)
    {
        if(!$userId) return [];
        return $this->userCourses($userId)?->pluck('id')->toArray() ?? [];
    }

}
