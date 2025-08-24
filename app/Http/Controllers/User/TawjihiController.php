<?php

namespace App\Http\Controllers\User;

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

        return view('user.tawjihi',[
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
        return view('user.tawjihi-first-year',[
            'tawjihiFirstYear' => $tawjihiFirstYear,
            'ministrySubjects' => $ministrySubjects,
            'schoolSubjects'   => $schoolSubjects,
        ]);
    }

    public function tawjihi_grade_year_fields()
    {
        $tawjihiLastYear       = CategoryRepository()->getTawjihiLastGrades();
        $tawjihiLastYearFields = CategoryRepository()->getDirectChilds($tawjihiLastYear);
        return view('user.tawjihi-last-year',[
            'tawjihiLastYear'        => $tawjihiLastYear,
            'tawjihiLastYearFields'  => $tawjihiLastYearFields,
        ]);
    }

    public function tawjihi_grade_year_field(Category $field)
    {
        $tawjihiLastYearFields = CategoryRepository()->getDirectChilds($field);
        $ministrySubjects      = CategoryRepository()->getDirectChilds($field)->where('is_ministry',1);
        $schoolSubjects        = CategoryRepository()->getDirectChilds($field)->where('is_ministry',0);
        return view('user.tawjihi-field',[
            'field'                 => $field,
            'ministrySubjects'      => $ministrySubjects,
            'schoolSubjects'        => $schoolSubjects,
            'tawjihiLastYearFields' => $tawjihiLastYearFields,
        ]);
    }
}
