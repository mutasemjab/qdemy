<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Category;

class CategoryRepository
{
    public $model;
    public function __construct()
    {
        $this->model = new Category;
        app()->setLocale('ar');
    }

    //  categories where type = major (main programms)
    public function getMajors()
    {
        return $this->model->where('is_active', true)
        ->where('type', 'major')
        ->get();
    }
    //  elementry && tawjihi programm grades
    public function getProgrammsGrades()
    {
        return $this->model->where('is_active', true)
        ->where('level', 'tawjihi_grade')
        ->get();
    }

    //  subjects under elementry && tawjihi programm grades
    public function getSubjectUnderProgrammsGrades()
    {
        $lessons = $this->model->where('type','lesson')->get()->unique('ctg_key');
        return $lessons;
    }

    //  subjects under elementry && tawjihi programm grades
    public function getGradesSemesters()
    {
        $semester = $this->model->where('level','semester')->get()->unique('ctg_key');
        return $semester;
    }

    // elementry programm grades
    public function tawjihiProgrammGrades()
    {
        return $this->model->where('is_active', true)->whereHas('parent',function ($q) {
            $q->where('name_en', 'Tawjihi and Secondary Program')
            ->orWhere('ctg_key', 'tawjihi_and_secondary_program');
        });
    }

    // get elementry programm grades
    public function getTawjihiProgrammGrades()
    {
        return $this->tawjihiProgrammGrades()->get();
    }

    // get tawjihi 2009 grades
    public function getTawjihiFirstGrades()
    {
        return $this->tawjihiProgrammGrades()->where('ctg_key','!=','final_year')->where('ctg_key','!=','vocational-system')->first();
    }

    public function getTawjihiFirstGradesMinistrySubjects()
    {
        return $this->model->where('is_active', true)->whereHas('parent',function ($q) {
            $q->where('ctg_key','ministry-subjects');
        })->get();
    }

    public function getTawjihiFirstGradesSchoolSubjects()
    {
        return $this->model->where('is_active', true)->whereHas('parent',function ($q) {
            $q->where('ctg_key','school-subjects');
        })->get();
    }

    // get tawjihi 2008 grades
    public function getTawjihiLastGrades()
    {
        return $this->tawjihiProgrammGrades()->where('ctg_key','final_year')->first();
    }

    public function getTawjihiVocationalSystemGrades()
    {
        return $this->tawjihiProgrammGrades()->where('ctg_key','vocational-system')->first();
    }

    public function getElementryProgramGrades()
    {
        return $this->model->where('is_active', true)->whereHas('parent',function ($q) {
            $q->where('name_en', 'Elementary Grades Program')
            ->orWhere('ctg_key', 'elementary-grades-program');
        })->get();
    }

    public function getInternationalProgramTypes()
    {
        return $this->model->where('is_active', true)->whereHas('parent',function ($q) {
            $q->where('name_en', 'International Program')
            ->orWhere('ctg_key', 'international-program');
        })->get();
    }

    public function getInternationalProgram()
    {
        return $this->model->where('is_active', true)->where('name_en', 'International Program')
            ->orWhere('ctg_key', 'international-program')->first();
    }

    public function getUniversitiesProgram()
    {
        return $this->model->where('is_active', true)->where('name_en', 'Universities and Colleges Program')
            ->orWhere('ctg_key', 'universities-and-colleges-program')->first();
    }

    public function getDirectChilds($category)
    {
        return $category->children;
    }

    public function getOtionalSubjectsForField($category)
    {
        $optional_form_field_type = $category->optional_form_field_type;

        // احصل علي الواد الموجودة اجباريا ف هذا الحقل
        $this_field_subjects      = Category::where('parent_id',$category->parent_id)
                                    ->where('is_optional',0)->pluck('ctg_key')->toArray();

        $subjects = Category::where('type','lesson')
            ->where('level','tawjihi_program_subject')
            ->whereNotNull('field_type')
            ->where('is_optional',0)
            ->where(function ($q) use($optional_form_field_type,$this_field_subjects) {
                if($optional_form_field_type != 'general') {
                    $q->where('field_type',$optional_form_field_type);
                }
                if($this_field_subjects && is_array($this_field_subjects)){
                    $q->whereNotIn('id',Category::whereIn('ctg_key',$this_field_subjects)->pluck('id')->toArray());
                }
            })
            ->get()
            ->unique('ctg_key')  // فلترة التكرار بعد جلب البيانات
            ->values();

        // dd($subjects);
        return $subjects;
    }

    // احصل علي كل ابناء category معين شجريا
    public function getAllSubChilds($parentId)
    {
        $categories = collect();
        $query = Category::Query();
        if(is_array($parentId)){
            $query->whereIn('parent_id', $parentId);
        }else{
            $query->where('parent_id', $parentId);
        }
        $items = $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();
        foreach ($items as $item) {
            $categories->push($item);
            if ($item->hasChildren()) {
                $categories = $categories->merge($this->getAllSubChilds($item->id));
            }
        }
        return $categories;
    }
}
