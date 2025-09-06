<?php

namespace App\Http\Controllers\Panel\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return view('panel.student.dashboard', compact('user'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('panel.student.profile', compact('user'));
    }
    
    public function courses()
    {
        $user = Auth::user();
        // Get student's enrolled courses
        $courses = []; // Replace with actual course query
        return view('panel.student.courses', compact('user', 'courses'));
    }
    
    public function schedule()
    {
        $user = Auth::user();
        // Get student's schedule
        $schedule = []; // Replace with actual schedule query
        return view('panel.student.schedule', compact('user', 'schedule'));
    }
    
    public function results()
    {
        $user = Auth::user();
        // Get student's test results
        $results = []; // Replace with actual results query
        return view('panel.student.results', compact('user', 'results'));
    }
    
    public function questionBank()
    {
        $user = Auth::user();
        // Get question bank
        $questions = []; // Replace with actual questions query
        return view('panel.student.question-bank', compact('user', 'questions'));
    }
    
    public function assignments()
    {
        $user = Auth::user();
        // Get student's assignments
        $assignments = []; // Replace with actual assignments query
        return view('panel.student.assignments', compact('user', 'assignments'));
    }
}