<?php

namespace App\Http\Controllers\Panel\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return view('panel.teacher.dashboard', compact('user'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('panel.teacher.profile', compact('user'));
    }
    
    public function students()
    {
        $user = Auth::user();
        // Get teacher's students
        $students = []; // Replace with actual students query
        return view('panel.teacher.students', compact('user', 'students'));
    }
    
    public function courses()
    {
        $user = Auth::user();
        // Get teacher's courses
        $courses = []; // Replace with actual courses query
        return view('panel.teacher.courses', compact('user', 'courses'));
    }
    
    public function studentReports()
    {
        $user = Auth::user();
        // Get student reports
        $reports = []; // Replace with actual reports query
        return view('panel.teacher.student-reports', compact('user', 'reports'));
    }
    
    public function attendance()
    {
        $user = Auth::user();
        // Get attendance records
        $attendance = []; // Replace with actual attendance query
        return view('panel.teacher.attendance', compact('user', 'attendance'));
    }
    
    public function classSchedule()
    {
        $user = Auth::user();
        // Get class schedule
        $schedule = []; // Replace with actual schedule query
        return view('panel.teacher.class-schedule', compact('user', 'schedule'));
    }
    
    public function gradeAssignments()
    {
        $user = Auth::user();
        // Get assignments to grade
        $assignments = []; // Replace with actual assignments query
        return view('panel.teacher.grade-assignments', compact('user', 'assignments'));
    }
    
    public function createCourse()
    {
        $user = Auth::user();
        return view('panel.teacher.create-course', compact('user'));
    }
}