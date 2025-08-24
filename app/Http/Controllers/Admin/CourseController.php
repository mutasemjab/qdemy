<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:course-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:course-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:course-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:course-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with(['teacher', 'category'])->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $teachers = Teacher::all();
        $parentCategories = Category::getFlatList();
        $parentId = $request->get('parent_id');
        return view('admin.courses.create', compact('teachers', 'parentCategories','parentId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'teacher_id' => 'nullable|exists:teachers,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->all();

        // Handle photo upload
         if ($request->hasFile('photo')) {
          $data['photo'] =uploadImage('assets/admin/uploads', $request->photo);
         }

        Course::create($data);

        return redirect()->route('courses.index')
            ->with('success', __('messages.course_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['teacher', 'category', 'sections.contents']);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $teachers = Teacher::all();
        $categories = Category::all();
        return view('admin.courses.edit', compact('course', 'teachers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'teacher_id' => 'nullable|exists:teachers,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->all();

        // Handle photo upload
           if ($request->hasFile('photo')) {
           if ($course->photo) {
                $filePath = base_path('assets/admin/uploads/' . $course->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['photo'] =uploadImage('assets/admin/uploads', $request->photo);
        } else {
            // Keep the current photo
            unset($data['photo']);
        }

        $course->update($data);

        return redirect()->route('courses.index')
            ->with('success', __('messages.course_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // Delete photo
        if ($course->photo) {
            $filePath = base_path('assets/admin/uploads/' . $course->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', __('messages.course_deleted_successfully'));
    }
}