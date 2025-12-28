<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Subject;
use App\Models\User;
use App\Traits\CourseManagementTrait;
use App\Traits\SubjectCategoryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller 
{
    use CourseManagementTrait,SubjectCategoryTrait;

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
            
       $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();
            
        $subjects = Subject::active()
            ->ordered()
            ->get();
            
        return view('admin.courses.create', compact('teachers', 'subjects','parentCategories'));
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

        // Get parent categories
        $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();

        $subjects = Subject::active()
            ->ordered()
            ->get();

        return view('admin.courses.edit', compact('course', 'teachers', 'parentCategories', 'subjects'));
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

      public function getChildCategories($parentId)
    {
        $categories = Category::where('parent_id', $parentId)
            ->active()
            ->ordered()
            ->get(['id', 'name_ar', 'name_en']);

        return response()->json($categories);
    }

     /**
     * Get subjects by category ID (AJAX)
     */
    public function getSubjectsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        if (!$categoryId) {
            return response()->json([]);
        }

        try {
            // Call the trait method that returns formatted data for API
            $subjects = $this->getSubjectsByCategoryForApi($categoryId);
            
            return response()->json($subjects->toArray());
            
        } catch (\Exception $e) {
            \Log::error('Error in getSubjectsByCategory controller: ' . $e->getMessage());
            \Log::error('Category ID: ' . $categoryId);
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([]);
        }
    }

}