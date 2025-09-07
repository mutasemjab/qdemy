<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Subject;
use App\Models\CategorySubject;

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
        if ($universityProgramId) {
            return $this->model->where('is_active', true)->where('programm_id', $universityProgramId);
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
    public function internationalProgramSubjects($programm)
    {
        return $this->model->where('is_active', true)->where('grade_id', $programm->id);
    }

    // query->get() for all subjects under international programm
    public function getinternationalProgramSubjects($programm)
    {
        return $this->internationalProgramSubjects($programm)->get();
    }


    //  spiecial progarmms subjects end




    //  tawjihi subjects start


    // get all ministry subjects for first tawjihi grade
    // if is active
    // where belong to tawjihi first year grade ('ctg_key','first_year')
    public function tawjihiFirstGradeSubjects()
    {
        return $this->model->where('is_active', true)
        ->whereHas('grade',function ($q) {
            $q->where('ctg_key','first_year');
            $q->where('is_active', true);
        });
    }

    public function getTawjihiFirstGradesMinistrySubjects()
    {
        return $this->tawjihiFirstGradeSubjects()
        ->whereHas('category_subjects',function ($q) {
            $q->where('is_ministry', true);
            $q->where('pivot_level', 'grade');
        })
        ->get();
    }

    // get all school subjects for first tawjihi grade
    // if is active
    public function getTawjihiFirstGradesSchoolSubjects()
    {
        return $this->tawjihiFirstGradeSubjects()
        ->whereHas('category_subjects',function ($q) {
            $q->where('is_ministry', false);
            $q->where('pivot_level', 'grade');
        })
        ->get();
    }

    // get all ministry subjects for final tawjihi grade
    // if is active
    // if has parent with ctg = ministry-subjects
    public function getTawjihiFinalGradesFieldMinistrySubjects($field)
    {
        $subjects = collect();
        // dd(CategorySubject::where('category_id', $field->id)->get());
        $CategorySubjects = CategorySubject::where('category_id', $field->id)
            ->where('pivot_level', 'field')
            ->where('is_ministry', true)
            ->whereHas('subject', function ($q) {
                $q->where('is_active', true);
                // $q->whereHas('grade', function ($q) {
                //     $q->where('ctg_key', 'final_year');
                // });
        })
        ->get();
        // dd($CategorySubjects->get() ,$field);

        foreach ($CategorySubjects as $key => $CategorySubject) {
            $subject = Subject::find($CategorySubject->subject_id);
            $subjects->push($subject);
        }

        return $subjects;
    }

    // get all school subjects for final tawjihi grade
    // if is active
    // if has parent with ctg = school-subjects
    public function getTawjihiFinalGradesFieldSchoolSubjects($field)
    {
        $subjects = collect();
        // dd(CategorySubject::where('category_id', $field->id)->get());
        $CategorySubjects = CategorySubject::where('category_id', $field->id)
            ->where('pivot_level', 'field')
            ->where('is_ministry', false)
            ->whereHas('subject', function ($q) {
                $q->where('is_active', true);
        })
        ->get();
        foreach ($CategorySubjects as $key => $CategorySubject) {
            $subject = Subject::find($CategorySubject->subject_id);
            $subjects->push($subject);
        }

        return $subjects;
    }


    // @param $subject = instance of subject
    // الحصول علي المواد الاختيارية لمبحث اختياري
    // معين ف احدي حقول توجيهي السنة النهائية
    // return collection
    public function getOptionalSubjectOptions($field, $subject)
    {
        return $this->model->where('is_active', true)
            ->where( function ($q) use ($subject, $field) {
                if($subject->field_type_id) $q->where('field_type_id', $subject->field_type_id);
            })
            ->whereDoesntHave('category_subjects', function ($q) use ($subject, $field) {
                $q->where('category_id', $field->id);
                $q->where('pivot_level', 'field');
            })
            ->get();

        return collect();
    }

    //  tawjihi subjects end



    // query for all subjects under grade
    // subjects is directly under grade
    public function subjectsForGrade($grade = null)
    {
        return $this->model->where('is_active', true)
            ->where(function ($q) use ($grade) {
                if ($grade) $q->where('grade_id', $grade?->id);
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
        return $this->model->where('is_active', true)->where('semester_id', $semester->id);
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
        $courses = Course::where('subject_id', $subjectId)->where('is_active', true)->get();
        return $courses;
    }

    public function getDirectSubjectCourses($subjectId)
    {
        return $this->directSubjectCourses($subjectId);
    }
}
