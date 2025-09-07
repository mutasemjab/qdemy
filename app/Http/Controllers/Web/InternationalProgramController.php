<?php

namespace  App\Http\Controllers\Web;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InternationalProgramController extends Controller
{

    public function index()
    {
        $programm  = CategoryRepository()->getInternationalProgram();
        $programms = CategoryRepository()->getInternationalPrograms();
        return view('web.gprograms',[
            'programm'  => $programm,
            'programms' => $programms,
        ]);
    }

    public function programm(Category $programm)
    {
        $subjects = SubjectRepository()->internationalProgramSubjects($programm)->paginate(PGN);
        return view('web.international-subjects',[
            'programm'  => $programm,
            'subjects'   => $subjects,
        ]);
    }
    public function courses($programm = null)
    {
        $programm = Category::find($subject->programm_id);
        $courses = SubjectRepository()->internationalProgramSubjects($programm)->paginate(PGN);
        return view('web.courses',[
            'title'    => $programm->name_en,
            'courses'  => $courses,
        ]);
    }

}
