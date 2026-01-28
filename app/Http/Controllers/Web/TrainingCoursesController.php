<?php

namespace App\Http\Controllers\Web;

use App\Models\Subject;
use App\Http\Controllers\Controller;

class TrainingCoursesController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('web.training-courses', [
            'subjects' => $subjects,
        ]);
    }
}
