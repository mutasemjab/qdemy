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
        $tawjihiFirstYear  = CategoryRepository()->getTawjihiFirstGrades();
        $tawjihiLastYear   = CategoryRepository()->getTawjihiLastGrades();
        $tawjihiVocational = CategoryRepository()->getTawjihiVocationalSystemGrades();

        return view('web.tawjihi',[
            'tawjihiLastYear'   => $tawjihiLastYear,
            'tawjihiFirstYear'  => $tawjihiFirstYear,
            'tawjihiVocational' => $tawjihiVocational,
        ]);
    }

    public function tawjihi_first_year()
    {
        $tawjihiFirstYear  = CategoryRepository()->getTawjihiFirstGrades();
        $ministrySubjects  = CategoryRepository()->getTawjihiFirstGradesMinistrySubjects();
        $schoolSubjects    = CategoryRepository()->getTawjihiFirstGradesSchoolSubjects();
        return view('web.tawjihi-first-year',[
            'tawjihiFirstYear' => $tawjihiFirstYear,
            'ministrySubjects' => $ministrySubjects,
            'schoolSubjects'   => $schoolSubjects,
        ]);
    }

    public function tawjihi_grade_year_fields()
    {
        $tawjihiLastYear       = CategoryRepository()->getTawjihiLastGrades();
        $tawjihiLastYearFields = CategoryRepository()->getDirectChilds($tawjihiLastYear);
        return view('web.tawjihi-last-year',[
            'tawjihiLastYear'        => $tawjihiLastYear,
            'tawjihiLastYearFields'  => $tawjihiLastYearFields,
        ]);
    }

    public function tawjihi_grade_year_field(Category $field)
    {
        $tawjihiLastYearFields = CategoryRepository()->getDirectChilds($field);
        $ministrySubjects      = CategoryRepository()->getDirectChilds($field)->where('is_ministry',1);
        $schoolSubjects        = CategoryRepository()->getDirectChilds($field)->where('is_ministry',0);
        return view('web.tawjihi-field',[
            'field'                 => $field,
            'ministrySubjects'      => $ministrySubjects,
            'schoolSubjects'        => $schoolSubjects,
            'tawjihiLastYearFields' => $tawjihiLastYearFields,
        ]);
    }
}
