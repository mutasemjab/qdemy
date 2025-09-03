<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Subject;
use App\Models\User;
use App\Traits\CourseManagementTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller 
{
    use CourseManagementTrait;

    public function __construct()
    {
        $this->middleware('permission:course-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:course-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:course-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:course-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource
     */
    public function index()
    {
        // Changed from 'category' to 'subject' and 'teacher' relationship
        $courses = Course::with(['teacher:id,name,email', 'subject:id,name_ar,name_en'])
            ->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request)
    {
        // Get teachers from users table where role_name = 'teacher'
        $teachers = User::where('role_name', 'teacher')
            ->where('activate', 1)
            ->select('id', 'name', 'email')
            ->get();
            
        // Get subjects instead of categories
        $subjects = Subject::active()
            ->with(['grade:id,name_ar,name_en', 'program:id,name_ar,name_en'])
            ->ordered()
            ->get();
            
        return view('admin.courses.create', compact('teachers', 'subjects'));
    }

    /**
     * Display the specified resource
     */
    public function show(Course $course)
    {
        $course->load([
            'teacher:id,name,email', 
            'subject:id,name_ar,name_en', 
            'sections.contents' => function($query) {
                $query->orderBy('order');
            }
        ]);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(Course $course)
    {
        // Get teachers from users table where role_name = 'teacher'
        $teachers = User::where('role_name', 'teacher')
            ->where('activate', 1)
            ->select('id', 'name', 'email')
            ->get();
            
        // Get subjects
        $subjects = Subject::active()
            ->with(['grade:id,name_ar,name_en', 'program:id,name_ar,name_en'])
            ->ordered()
            ->get();
            
        return view('admin.courses.edit', compact('course', 'teachers', 'subjects'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        return $this->storeCourse($request, true);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, Course $course)
    {
        return $this->updateCourse($request, $course, true);
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(Course $course)
    {
        // Use the trait's delete method which handles Bunny CDN cleanup
        return $this->deleteCourse($course);
    }
}