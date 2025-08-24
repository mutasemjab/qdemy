<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
            'video_type'     => 'nullable|required_if:content_type,video|in:youtube,bunny',
            'video_url'   => 'nullable|required_if:video_type,youtube|url',
            'upload_video'   => 'nullable|file|required_if:video_type,bunny|max:10240',//mimes:video|
            'video_duration' => 'nullable|integer|min:0',
             // PDF fields
            'file_path' => 'nullable|required_if:content_type,pdf,quiz,assignment|file|mimes:pdf|max:10240',
            'pdf_type' => 'nullable|required_if:content_type,pdf|in:homework,worksheet,notes,other',
        ]);

        $data = $request->all();
        $data['course_id'] = $course->id;

        DB::beginTransaction();
        try {

            if ($request->hasFile('upload_video') && $request->content_type == 'video') {
                $upload_response = BunnyHelper()->upload($request->upload_video,CourseContent::BUNNY_PATH.'/'.$course->id);
                $upload_response_data = $upload_response->getData();
                if($upload_response_data->success && $upload_response_data->file_path){
                    $data['video_url'] = $upload_response_data->file_path;
                }
                $data['file_path']    = null;
                $data['pdf_type']     = null;
            }
            if ($request->hasFile('file_path') && $request->content_type != 'video') {
                $upload_response      = BunnyHelper()->upload($request->file_path,CourseContent::BUNNY_PATH.'/'.$course->id);
                $upload_response_data = $upload_response->getData();
                if($upload_response_data->success && $upload_response_data->file_path){
                    $data['file_path'] = $upload_response_data->file_path;
                }
                $data['video_url']      = null;
                $data['video_type']     = null;
                $data['video_duration'] = null;

            }
            unset($data['upload_video']);
            $content = CourseContent::create($data);

        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $error          = $e->getMessage();
            $message_status = 'error';
        }


        return redirect()->route('courses.sections.index', $course)
            ->with($message_status ?? 'success',$error ?? __('messages.content_created_successfully'));
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
            'title_en'   => 'required|string|max:255',
            'title_ar'   => 'required|string|max:255',
            'content_type' => 'required|in:video,pdf,quiz,assignment',
            'is_free'    => 'required|in:1,2',
            'order'      => 'required|integer|min:0',
            'section_id' => 'nullable|exists:course_sections,id',

            // Video fields
            'video_type'     => 'nullable|required_if:content_type,video|in:youtube,bunny',
            'video_url'   => 'nullable|required_if:video_type,youtube|url',
            'upload_video'   => 'nullable|file|required_if:video_type,bunny|max:10240',//mimes:video|
            'video_duration' => 'nullable|integer|min:0',
             // PDF fields
            'file_path' => 'nullable|required_if:content_type,pdf,quiz,assignment|file|mimes:pdf|max:10240',
            'pdf_type' => 'nullable|required_if:content_type,pdf|in:homework,worksheet,notes,other',
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try {

            if ($request->hasFile('upload_video') && $request->content_type == 'video') {

                $upload_response = BunnyHelper()->upload($request->upload_video,CourseContent::BUNNY_PATH.'/'.$course->id);
                $upload_response_data = $upload_response->getData();
                if($upload_response_data->success && $upload_response_data->file_path){
                    $data['video_url'] = $upload_response_data->file_path;
                    $oldVideo          = $content->original_video_url;
                    $oldFile           = $content->original_file_path;
                    if($oldVideo){ BunnyHelper()->delete($oldVideo); }
                    if($oldFile){ BunnyHelper()->delete($oldFile); }
                }
                $data['file_path']    = null;
                $data['pdf_type']     = null;
            }

            if ($request->hasFile('file_path') && $request->content_type != 'video') {
                $upload_response = BunnyHelper()->upload($request->file_path,CourseContent::BUNNY_PATH.'/'.$course->id);
                $upload_response_data = $upload_response->getData();

                if($upload_response_data->success && $upload_response_data->file_path){
                    $data['file_path'] = $upload_response_data->file_path;
                    $oldVideo          = $content->original_video_url;
                    $oldFile           = $content->original_file_path;
                    if($oldVideo){ BunnyHelper()->delete($oldVideo); }
                    if($oldFile){ BunnyHelper()->delete($oldFile); }
                }
                $data['video_url']      = null;
                $data['video_type']     = null;
                $data['video_duration'] = null;

            }

            unset($data['upload_video']);
            $content->update($data);

        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $error          = $e->getMessage();
            $message_status = 'error';
        }

        return redirect()->route('courses.sections.index', $course)
            ->with($message_status ?? 'success', $error ?? __('messages.content_updated_successfully'));
    }


    /**
     * Remove the specified content
    */
    public function destroyContent(Course $course, CourseContent $content)
    {
        BunnyHelper()->deleteFiles(CourseContent::BUNNY_PATH.'/'.$course->id);
        $content->delete();
        return redirect()->route('courses.sections.index', $course)
            ->with('success', __('messages.content_deleted_successfully'));
    }
}
