<?php

namespace App\Http\Controllers\Panel\Teacher;

use App\Http\Controllers\Controller;
use App\Traits\HasCommunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    use HasCommunity;
    
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
   
    
    public function createCourse()
    {
        $user = Auth::user();
        return view('panel.teacher.create-course', compact('user'));
    }

      // Community methods
     public function createPost(Request $request)
    {
        return $this->handleCreatePost($request);
    }

    public function toggleLike(Request $request)
    {
        return $this->handleToggleLike($request);
    }

    public function addComment(Request $request)
    {
        return $this->handleAddComment($request);
    }

}