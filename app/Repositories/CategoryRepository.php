<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Category;
use App\Models\Package;

class CategoryRepository
{
    public $model;
    public function __construct()
    {
        $this->model = new Category;
    }

    //  categories where type = major (main programms)
    public function majors()
    {
        return $this->model->where('is_active', true)
        ->where('type', 'major');
    }
    public function getMajors()
    {
        return $this->majors()->get();
    }

    //  categories where level = tawjihi_program_fields (الشعب الرئيسية  - حقول علمية وادبية)
    public function getFieldTypes()
    {
        return $this->model->where('is_active', true)
        ->where('level', 'tawjihi_program_fields')
        ->get();
    }

    //  elementry
    //where 'level' = 'elementray_grade' (elementray_grade = elementary)
    public function getProgrammsGrades()
    {
        return $this->model->where('is_active', true)
        ->where('level', 'elementray_grade')
        ->get();
    }

    //  subjects under elementry && tawjihi programm grades
    public function getGradeSemesters($gradeId)
    {
        $semesters = $this->model->where('level','semester')
        ->whereHas('parent',function ($q) use($gradeId){
            $q->where('id',$gradeId);
        })
        ->get();
        return $semesters;
    }

    // elementry programm grades
    // where 'ctg_key' = 'tawjihi_and_secondary_program'
    public function tawjihiProgrammGrades()
    {
        return $this->model->where('is_active', true)->where('level','tawjihi_grade');
    }

    // get elementry programm grades
    public function getTawjihiProgrammGrades()
    {
        return $this->tawjihiProgrammGrades()->get();
    }

    // get tawjihi 2009 grades
    // sql from this.tawjihiProgrammGrades  where 'ctg_key','==','first_year'
    public function getTawjihiFirstGrade()
    {
        return $this->tawjihiProgrammGrades()->where('ctg_key','first_year')->first();
    }

    // get tawjihi last year (2008) grades - get by ctg_key
    public function getTawjihiLastGrades()
    {
        return $this->tawjihiProgrammGrades()->where('ctg_key','final_year')->first();
    }

    // get tawjihi last grad files (get by level) grades
    public function getTawjihiLastGradeFieldes($type = null)
    {
        return $this->model->where('is_active', true)
        ->where('level','tawjihi_scientific_fields')
        ->orWhere('level','tawjihi_literary_fields')->get();
    }

    // get all grade years under elementary programm
    // where parent.ctg_key = elementary-grades-program
    // return collection
    public function getElementryProgramGrades()
    {
        return $this->model->where('is_active', true)->where('level','elementray_grade')->get();
    }

    // get major international program
    // return instance of Category
    public function getInternationalProgram()
    {
        return $this->model->where('is_active', true)->where('name_en', 'International Program')
            ->orWhere('ctg_key', 'international-program')->first();
    }

    // get major univirisity program
    // return instance of Category
    public function getUniversitiesProgram()
    {
        return $this->model->where('is_active', true)->where('name_en', 'Universities and Colleges Program')
            ->orWhere('ctg_key', 'universities-and-colleges-program')->first();
    }

    public function getDirectChilds($category)
    {
        return $category->children;
    }

    // احصل علي كل ابناء category معين شجريا
    // if withParent = true  && !is_array($parentId) - ضع ال parent category علس راس القائمة
    public function getAllSubChilds($parentId,$is_active = null, $withParent = false)
    {
        $categories = collect();
        $query = Category::Query();
        if(is_array($parentId)){
            $query->whereIn('parent_id', $parentId);
        }else{
            $query->where('parent_id', $parentId);
        }

        if($is_active !== null){
            $items = $query->where('is_active', $is_active);
        }

         $items = $query->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();
        if($withParent && !is_array($parentId)){
            $categories->push(Category::find($parentId));
        }
        foreach ($items as $item) {
            $categories->push($item);
            if ($item->hasChildren()) {
                $categories = $categories->merge($this->getAllSubChilds($item->id));
            }
        }
        return $categories;
    }

}
