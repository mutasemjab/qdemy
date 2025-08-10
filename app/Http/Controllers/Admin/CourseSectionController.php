<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseContent;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
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
     * Show the form for creating a new section
     */
    public function create(Course $course)
    {
        $sections = $course->sections; // For parent selection
        return view('admin.courses.sections.create', compact('course', 'sections'));
    }

    /**
     * Store a newly created section
     */
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:course_sections,id',
        ]);

        $course->sections()->create($request->all());

        return redirect()->route('courses.sections.index', $course)
            ->with('success', __('messages.section_created_successfully'));
    }

    /**
     * Show the form for editing a section
     */
    public function edit(Course $course, CourseSection $section)
    {
        $sections = $course->sections->where('id', '!=', $section->id); // Exclude current section
        return view('admin.courses.sections.edit', compact('course', 'section', 'sections'));
    }

    /**
     * Update the specified section
     */
    public function update(Request $request, Course $course, CourseSection $section)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:course_sections,id',
        ]);

        $section->update($request->all());

        return redirect()->route('courses.sections.index', $course)
            ->with('success', __('messages.section_updated_successfully'));
    }

    /**
     * Remove the specified section
     */
    public function destroy(Course $course, CourseSection $section)
    {
        $section->delete();

        return redirect()->route('courses.sections.index', $course)
            ->with('success', __('messages.section_deleted_successfully'));
    }

    /**
     * Show the form for creating content
     */
    public function createContent(Course $course)
    {
        $sections = $course->sections;
        return view('admin.courses.contents.create', compact('course', 'sections'));
    }

    /**
     * Store a newly created content
     */
    public function storeContent(Request $request, Course $course)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_type' => 'required|in:video,pdf,quiz,assignment',
            'is_free' => 'required|in:1,2',
            'order' => 'required|integer|min:0',
            'section_id' => 'nullable|exists:course_sections,id',
            
            // Video fields
            'video_type' => 'nullable|required_if:content_type,video|in:youtube,bunny',
            'video_url' => 'nullable|required_if:content_type,video|url',
            'video_duration' => 'nullable|integer|min:0',
            
            // PDF fields
            'file_path' => 'nullable|required_if:content_type,pdf|file|mimes:pdf|max:10240',
            'pdf_type' => 'nullable|required_if:content_type,pdf|in:homework,worksheet,notes,other',
        ]);

        $data = $request->all();
        $data['course_id'] = $course->id;

        // Handle file upload for PDF
        if ($request->hasFile('file_path')) {
              $data['file_path'] =uploadImage('assets/admin/uploads', $request->file_path);
        }

        CourseContent::create($data);

        return redirect()->route('courses.sections.index', $course)
            ->with('success', __('messages.content_created_successfully'));
    }

    /**
     * Show the form for editing content
     */
    public function editContent(Course $course, CourseContent $content)
    {
        $sections = $course->sections;
        return view('admin.courses.contents.edit', compact('course', 'content', 'sections'));
    }

    /**
     * Update the specified content
     */
    public function updateContent(Request $request, Course $course, CourseContent $content)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_type' => 'required|in:video,pdf,quiz,assignment',
            'is_free' => 'required|in:1,2',
            'order' => 'required|integer|min:0',
            'section_id' => 'nullable|exists:course_sections,id',
            
            // Video fields
            'video_type' => 'nullable|required_if:content_type,video|in:youtube,bunny',
            'video_url' => 'nullable|required_if:content_type,video|url',
            'video_duration' => 'nullable|integer|min:0',
            
            // PDF fields
            'file_path' => 'nullable|file|mimes:pdf|max:10240',
            'pdf_type' => 'nullable|required_if:content_type,pdf|in:homework,worksheet,notes,other',
        ]);

        $data = $request->all();

        // Handle file upload for PDF
        if ($request->hasFile('file_path')) {
           if ($content->file_path) {
                $filePath = base_path('assets/admin/uploads/' . $content->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['file_path'] =uploadImage('assets/admin/uploads', $request->file_path);
        } else {
            // Keep the current file_path
            unset($data['file_path']);
        }

        $content->update($data);

        return redirect()->route('courses.sections.index', $course)
            ->with('success', __('messages.content_updated_successfully'));
    }

    /**
     * Remove the specified content
     */
    public function destroyContent(Course $course, CourseContent $content)
    {
        // Delete file if exists
        if ($content->file_path) {
                $filePath = base_path('assets/admin/uploads/' . $content->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
        }

        $content->delete();

        return redirect()->route('courses.sections.index', $course)
            ->with('success', __('messages.content_deleted_successfully'));
    }
}