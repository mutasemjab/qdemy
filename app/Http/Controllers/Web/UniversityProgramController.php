<?php

namespace  App\Http\Controllers\Web;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UniversityProgramController extends Controller
{

    public function index()
    {
        $programm = CategoryRepository()->getUniversitiesProgram();
        $subjects = SubjectRepository()->universitiesProgramSubjects($programm)->paginate(PGN);
        return view('web.univerisity-subjects',[
            'programm'  => $programm,
            'subjects'   => $subjects,
        ]);
    }

    public function courses($programm = null)
    {
        $programm = Category::find($subject->programm_id);
        $courses = SubjectRepository()->universitiesProgramSubjects($programm)->paginate(PGN);
        return view('web.courses',[
            'title'    => 'International Program',
            'courses'  => $courses,
        ]);
    }
}

