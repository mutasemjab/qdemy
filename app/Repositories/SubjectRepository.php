<?php
namespace App\Repositories;

use App\Models\Subject;

class SubjectRepository
{
    public $model;
    public function __construct()
    {
        $this->model = new Subject;
    }



    //  spiecial progarmms subjects start

    // query for all subjects under univertisy programm
    // subjects is directly under univertisy programm
    public function universitiesProgramSubjects()
    {
        $universityProgramId = CategoryRepository()->getUniversitiesProgram()?->id;
        if($universityProgramId){
            return $this->model->where('is_active', true)->where('programm_id',$universityProgramId);
        }
        return [];
    }

    // query->get() for all subjects under univertisy programm
    public function getuniversitiesProgramSubjects()
    {
        return $this->universitiesProgramSubjects()->get();
    }

    // query for all subjects under international programm
    // subjects is directly under international programm
    public function internationalProgramSubjects($programm = null)
    {
        $internationalProgramId = CategoryRepository()->getInternationalProgram()?->id;
        if($internationalProgramId){
            return $this->model->where('is_active', true)->where('programm_id',$internationalProgramId);
        }
        return [];
    }

    // query->get() for all subjects under international programm
    public function getinternationalProgramSubjects($programm)
    {
        return $this->internationalProgramSubjects($programm)->get();
    }


    //  spiecial progarmms subjects end




    //  tawjihi subjects end

    // get all ministry subjects for first tawjihi grade
    // if is active
    // if has parent with ctg = ministry-subjects
    public function getTawjihiFinalGradesFieldMinistrySubjects($field)
    {
        return $this->model->where('is_active', true)
        ->whereHas('grade',function ($q) {
            $q->where('ctg_key','final_year');
            $q->where('is_active', true);
        })
        ->whereHas('category_subjects',function ($q)use($field) {
            $q->where('category_id', $field->id);
            $q->where('is_ministry', true);
            $q->where('pivot_level', 'field');
            // $q->where('is_optional', false);
        })
        ->get();
    }

    // get all school subjects for first tawjihi grade
    // if is active
    // if has parent with ctg = school-subjects
    public function getTawjihiFinalGradesFieldSchoolSubjects($field)
    {
        return $this->model->where('is_active', true)
        ->whereHas('grade',function ($q) {
            $q->where('ctg_key','final_year');
            $q->where('is_active', true);
        })
        ->whereHas('category_subjects',function ($q)use($field) {
            $q->where('category_id', $field->id);
            $q->where('is_ministry', false);
            $q->where('pivot_level', 'field');
            // $q->where('is_optional', false);
        })
        ->get();
    }


    // @param $subject = instance of subject
    // الحصول علي المواد الاختيارية لمبحث اختياري
    // معين ف احدي حقول توجيهي السنة النهائية
    // return collection
    public function getOptionalSubjectOptions($field,$subject)
    {
        return $this->model->where('is_active', true)
        ->where('id', '!=',$subject->id)
        ->where('field_type_id', $subject->field_type_id)
        ->whereHas('grade',function ($q) {
            $q->where('ctg_key','final_year');
            $q->where('is_active', true);
        })
        ->whereDoesntHave('category_subjects',function ($q)use($subject,$field) {
            $q->where('category_id', $field->id);
            $q->where('pivot_level', 'field');
        })
        ->whereDoesntHave('category_subjects',function ($q)use($subject,$field) {
            $q->where('is_optional', true);
            $q->where('is_ministry', false);
        })
        ->get();

        return collect();
    }

    //  tawjihi subjects start




    // query for all subjects under grade
    // subjects is directly under grade
    public function subjectsForGrade($grade = null)
    {
       return $this->model->where('is_active', true)
       ->where(function ($q)use($grade) {
          if($grade) $q->where('grade_id',$grade?->id);
       });
    }

    // query->get() for all subjects under grade
    public function getSubjectsForGrade($grade = null)
    {
        return $this->subjectsForGrade($grade)->get();
    }


    // query for all subjects under international programm
    // subjects is directly under international programm
    public function subjectsForSemester($semester = null)
    {
       return $this->model->where('is_active', true)->where('semester_id',$semester->id);
    }

    // query->get() for all courses under international programm
    public function getSubjectsForSemester($semester)
    {
        return $this->subjectsForSemester($semester)->get();
    }

    // query direct courses under subject
    // @param sujectId = Subject.id
    // return collection
    public function directSubjectCourses($subjectId)
    {
        $courses = [];
        $courses = Course::where('subject_id',$subjectId)->where('is_active', true)->get();
        return $courses;
    }

    public function getDirectSubjectCourses($subjectId)
    {
        return $this->directSubjectCourses($subjectId);
    }

}
