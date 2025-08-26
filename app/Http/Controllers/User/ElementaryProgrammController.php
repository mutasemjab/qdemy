<?php

namespace App\Http\Controllers\User;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElementaryProgrammController extends Controller
{
    public function grades_basic_programm()
    {
        $grades = CategoryRepository()->getElementryProgramGrades();
        return view('user.gradesbasic',[
            'grades' => $grades,
        ]);
    }
    public function grade_programm($grade,$slug=null)
    {
        $grade     = Category::FindOrFail($grade);
        $semesters = CategoryRepository()->getDirectChilds($grade) ?? [];
        return view('user.grade',[
            'grade' => $grade,
            'semesters' => $semesters,
        ]);
    }

}
