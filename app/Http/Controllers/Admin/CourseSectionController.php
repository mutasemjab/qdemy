<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\CourseManagementTrait;


class CourseSectionController extends Controller
{
    use CourseManagementTrait;
    
    public function __construct()
    {
        $this->middleware('permission:course-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:course-add', ['only' => ['create', 'store', 'createContent', 'storeContent']]);
        $this->middleware('permission:course-edit', ['only' => ['edit', 'update', 'editContent', 'updateContent']]);
        $this->middleware('permission:course-delete', ['only' => ['destroy', 'destroyContent']]);
    }

    /**
     * Display sections and contents for a course
     */
    public function index(Course $course)
    {
        $course->load(['sections.contents' => function($query) {
            $query->orderBy('order');
        }]);

        $directContents = $course->contents()->whereNull('section_id')->orderBy('order')->get();

        return view('admin.courses.sections.index', compact('course', 'directContents'));
    }

    /**
     * Show a specific section
     */
    public function show(Course $course, CourseSection $section)
    {
        if ($section->course_id !== $course->id) {
            abort(404);
        }

        $section->load(['contents' => function($query) {
            $query->orderBy('order');
        }, 'children.contents']);

        return view('admin.courses.sections.show', compact('course', 'section'));
    }

    /**
     * Show the form for creating a new section
     */
    public function create(Course $course)
    {
        $sections = $course->sections;
        return view('admin.courses.sections.create', compact('course', 'sections'));
    }

    /**
     * Store a newly created section - USING TRAIT
     */
    public function store(Request $request, Course $course)
    {
        $response = $this->storeCourseSection($request, $course);
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('admin.courses.sections.index', $course)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

    /**
     * Show the form for editing a section
     */
    public function edit(Course $course, CourseSection $section)
    {
        if ($section->course_id !== $course->id) {
            abort(404);
        }

        $sections = $course->sections->where('id', '!=', $section->id);
        return view('admin.courses.sections.edit', compact('course', 'section', 'sections'));
    }

    /**
     * Update a section - USING TRAIT
     */
    public function update(Request $request, Course $course, CourseSection $section)
    {
        $response = $this->updateCourseSection($request, $course, $section);
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('admin.courses.sections.index', $course)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

    /**
     * Remove a section - USING TRAIT
     */
    public function destroy(Course $course, CourseSection $section)
    {
        $response = $this->deleteCourseSection($course, $section);
        
        if (request()->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('admin.courses.sections.index', $course)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

    // === CONTENT MANAGEMENT ===

    /**
     * Show the form for creating content
     */
    public function createContent(Course $course)
    {
        $sections = $course->sections;
        return view('admin.courses.contents.create', compact('course', 'sections'));
    }

    /**
     * Store course content - USING TRAIT
     */
    public function storeContent(Request $request, Course $course)
    {
        return $this->storeCourseContent($request, $course);
    }

    /**
     * Show the form for editing content
     */
    public function editContent(Course $course, CourseContent $content)
    {
        if ($content->course_id !== $course->id) {
            abort(404);
        }

        $sections = $course->sections;
        return view('admin.courses.contents.edit', compact('course', 'content', 'sections'));
    }

    /**
     * Update course content - USING TRAIT
     */
    public function updateContent(Request $request, CourseContent $content)
    {
        return $this->updateCourseContent($request, $content);
    }

    /**
     * Delete course content - USING TRAIT
     */
    public function destroyContent(CourseContent $content)
    {
        $response = $this->deleteCourseContent($content);
        
        if (request()->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        $course = $content->course;
        
        if ($data->success) {
            return redirect()->route('admin.courses.sections.index', $course)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }
}
