<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElementaryProgrammController extends Controller
{
    public function grades_basic_programm()
    {
        $grades = CategoryRepository()->getElementryProgramGrades();
        return view('web.gradesbasic',[
            'grades' => $grades,
        ]);
    }
    public function grade_programm($grade,$slug=null)
    {
        $grade     = Category::FindOrFail($grade);
        $semesters = CategoryRepository()->getDirectChilds($grade) ?? [];
        return view('web.grade',[
            'grade' => $grade,
            'semesters' => $semesters,
        ]);
    }

}
