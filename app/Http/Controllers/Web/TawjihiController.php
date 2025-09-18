<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TawjihiController extends Controller
{
    public function tawjihi_programm()
    {
        $tawjihiFirstYear  = CategoryRepository()->getTawjihiFirstGrade();
        $tawjihiLastYear   = CategoryRepository()->getTawjihiLastGrades();

        return view('web.tawjihi',[
            'tawjihiLastYear'   => $tawjihiLastYear,
            'tawjihiFirstYear'  => $tawjihiFirstYear,
        ]);
    }

    public function tawjihi_first_year()
    {
        $tawjihiFirstYear  = CategoryRepository()->getTawjihiFirstGrade();
        $ministrySubjects  = SubjectRepository()->getTawjihiFirstGradesMinistrySubjects();
        $schoolSubjects    = SubjectRepository()->getTawjihiFirstGradesSchoolSubjects();
        return view('web.tawjihi-first-year',[
            'tawjihiFirstYear' => $tawjihiFirstYear,
            'ministrySubjects' => $ministrySubjects,
            'schoolSubjects'   => $schoolSubjects,
        ]);
    }

    public function tawjihi_grade_last_year_fields()
    {
        $tawjihiLastYear       = CategoryRepository()->getTawjihiLastGrades();
        $tawjihiLastYearFields = CategoryRepository()->getDirectChilds($tawjihiLastYear);
        return view('web.tawjihi-last-year',[
            'tawjihiLastYear'        => $tawjihiLastYear,
            'tawjihiLastYearFields'  => $tawjihiLastYearFields,
        ]);
    }

    public function tawjihi_last_year_field(Category $field)
    {
        $tawjihiLastYearFields = CategoryRepository()->getDirectChilds($field);
        $ministrySubjects      = SubjectRepository()->getTawjihiFinalGradesFieldMinistrySubjects($field);
        $schoolSubjects        = SubjectRepository()->getTawjihiFinalGradesFieldSchoolSubjects($field);
        return view('web.tawjihi-last-year-field',[
            'field'                 => $field,
            'ministrySubjects'      => $ministrySubjects,
            'schoolSubjects'        => $schoolSubjects,
            'tawjihiLastYearFields' => $tawjihiLastYearFields,
        ]);
    }
}
