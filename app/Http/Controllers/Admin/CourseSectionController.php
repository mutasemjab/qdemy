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
     * Show the form for creating content
     */
    public function index(Course $course)
    {
        // Load course with nested relationships
        $course->load([
            'sections' => function($query) {
                $query->whereNull('parent_id')->orderBy('order');
            },
            'sections.contents' => function($query) {
                $query->orderBy('created_at');
            },


        ]);

        $directContents = $course->contents()
            ->whereNull('section_id')
            ->orderBy('created_at')
            ->get();

        return view('admin.courses.sections.index', compact('course', 'directContents'));
    }

    /**
     * Handle section show with proper authorization
     */
    public function show(Course $course, CourseSection $section)
    {
        // Ensure the section belongs to this course
        if ($section->course_id !== $course->id) {
            abort(404, 'Section not found for this course');
        }

        $section->load([
            'contents' => function($query) {
                $query->orderBy('created_at');
            }, 
            'children.contents' => function($query) {
                $query->orderBy('created_at');
            }
        ]);

        return view('admin.courses.sections.show', compact('course', 'section'));
    }



    /**
     * Show the form for creating a new section
     */
    public function create(Course $course)
    {
        $sections = $course->sections;
        // Calculate next order value (max + 1)
        $maxOrder = (CourseSection::where('course_id', $course->id)->max('order') ?? 0) + 1;

        return view('admin.courses.sections.create', compact('course', 'sections', 'maxOrder'));
    }

    /**
     * Store a newly created section - USING TRAIT
     */
    public function store(Request $request, Course $course)
    {
        // The trait now handles both API and web responses automatically
        return $this->storeCourseSection($request, $course);
    }

    /**
     * Enhanced edit method with better validation
     */
    public function edit(Course $course, CourseSection $section)
    {
        // Ensure the section belongs to this course
        if ($section->course_id !== $course->id) {
            abort(404, 'Section not found for this course');
        }

        // Get all sections except this one for parent selection
        $sections = $course->sections()
            ->where('id', '!=', $section->id)
            ->whereNull('parent_id') // Only allow top-level sections as parents
            ->get();
            
        return view('admin.courses.sections.edit', compact('course', 'section', 'sections'));
    }

    /**
     * Update a section - USING TRAIT
     */
    public function update(Request $request, Course $course, CourseSection $section)
    {
        // The trait now handles both API and web responses automatically
        return $this->updateCourseSection($request, $course, $section);
    }

    /**
     * Remove a section - USING TRAIT
     */
    public function destroy(Course $course, CourseSection $section)
    {
        // The trait now handles both API and web responses automatically
        return $this->deleteCourseSection($course, $section);
    }

    // === CONTENT MANAGEMENT ===

    public function createContent(Request $request, Course $course)
    {
        $sections = $course->sections;
        $selectedSectionId = $request->get('section_id');
        // Calculate max order for this course to help users set proper order values
        $maxOrder = CourseContent::where('course_id', $course->id)->max('order') ?? 0;

        return view('admin.courses.contents.create', compact('course', 'sections', 'selectedSectionId', 'maxOrder'));
    }
    /**
     * Store course content - USING TRAIT
     */
    public function storeContent(Request $request, Course $course)
    {
        // The trait now handles both API and web responses automatically
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
        // Calculate max order for this course to help users set proper order values
        $maxOrder = CourseContent::where('course_id', $course->id)->max('order') ?? 0;
        return view('admin.courses.contents.edit', compact('course', 'content', 'sections', 'maxOrder'));
    }

    /**
     * Update course content - USING TRAIT
     */
      public function updateContent(Request $request, Course $course, CourseContent $content)
    {
        // Ensure the content belongs to this course
        if ($content->course_id !== $course->id) {
            abort(404, 'Content not found for this course');
        }

        // The trait now handles both API and web responses automatically
        return $this->updateCourseContent($request, $content);
    }

    /**
     * Delete course content - USING TRAIT
     */
    public function destroyContent( Course $course, CourseContent $content)
    {
        // The trait now handles both API and web responses automatically
        return $this->deleteCourseContent($content);
    }
}
