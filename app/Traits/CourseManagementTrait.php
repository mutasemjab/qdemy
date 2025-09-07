<?php

namespace App\Traits;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseContent;
use App\Helpers\BunnyHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



trait CourseManagementTrait
{
    use Responses;

    /**
     * Check if request is from API
     */
    protected function isApiRequest()
    {
        return request()->is('api/*') || request()->expectsJson();
    }

    /**
     * Return appropriate response based on request type
     */
    protected function successResponse($message, $data = null, $statusCode = 200)
    {
        if ($this->isApiRequest()) {
            return $this->success_response($message, $data)->setStatusCode($statusCode);
        }
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Return appropriate error response based on request type
     */
    protected function errorResponse($message, $data = null, $statusCode = 400)
    {
        if ($this->isApiRequest()) {
            return $this->error_response($message, $data)->setStatusCode($statusCode);
        }
        
        return redirect()->back()->with('error', $message)->withInput();
    }

    /**
     * Return validation error response
     */
    protected function validationErrorResponse($message, $errors, $statusCode = 422)
    {
        if ($this->isApiRequest()) {
            return response()->json([
                'status' => false,
                'message' => $message,
                'data' => $errors
            ], $statusCode);
        }
        
        return redirect()->back()->withErrors($errors)->withInput();
    }

    /**
     * Store a new course
     */
    public function storeCourse(Request $request, $isAdmin = false)
    {
        // Validate the request
        $validator = $this->validateCourseRequest($request, $isAdmin);
        
        if ($validator->fails()) {
            return $this->validationErrorResponse(
                __('messages.validation_error'),
                $validator->errors()
            );
        }

        DB::beginTransaction();
        
        try {
            $courseData = $this->prepareCourseData($request, $isAdmin);
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoUpload = uploadImage('assets/admin/uploads', $request->photo);
                $courseData['photo'] =  $photoUpload;
               
            }

            $course = Course::create($courseData);

            // Handle sections if provided
            if ($request->has('sections') && is_array($request->sections)) {
                $this->createCourseSections($course->id, $request->sections);
            }

            // Handle contents if provided
            if ($request->has('contents') && is_array($request->contents)) {
                $this->createCourseContents($course, $request->contents);
            }

            DB::commit();

            $courseData = $course->load(['teacher', 'subject', 'sections', 'contents']);
            
            return $this->successResponse(
                __('messages.course_created_successfully'),
                $courseData,
                201
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.course_creation_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update an existing course
     */
    public function updateCourse(Request $request, Course $course, $isAdmin = false)
    {
        // Check permissions
        if (!$isAdmin && $course->teacher_id !== auth()->user()->teacher->id) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        // Validate the request
        $validator = $this->validateCourseRequest($request, $isAdmin, $course->id);
        
        if ($validator->fails()) {
            return $this->validationErrorResponse(
                __('messages.validation_error'),
                $validator->errors()
            );
        }

        DB::beginTransaction();
        
        try {
            $courseData = $this->prepareCourseData($request, $isAdmin, $course);
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($course->photo) {
                    BunnyHelper()->delete($course->photo);
                }
                
                $photoUpload = uploadImage('assets/admin/uploads', $request->photo);
                $courseData['photo'] = $photoUpload;
            }

            $course->update($courseData);

            // Handle sections update if provided
            if ($request->has('sections')) {
                $this->updateCourseSections($course->id, $request->sections);
            }

            // Handle contents update if provided
            if ($request->has('contents')) {
                $this->updateCourseContents($course, $request->contents);
            }

            DB::commit();

            $courseData = $course->fresh()->load(['teacher', 'subject', 'sections', 'contents']);
            
            return $this->successResponse(
                __('messages.course_updated_successfully'),
                $courseData
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.course_update_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store course content (video, PDF, etc.)
     */
    public function storeCourseContent(Request $request, Course $course)
    {
        // Check permissions
        if (!$this->canManageCourse($course)) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        $validator = $this->validateContentRequest($request);
        
        if ($validator->fails()) {
            return $this->validationErrorResponse(
                __('messages.validation_error'),
                $validator->errors()
            );
        }

        DB::beginTransaction();
        
        try {
            $contentData = $request->only([
                'title_en', 'title_ar', 'content_type', 'is_free', 
                'order', 'video_type', 'video_url', 'video_duration', 
                'pdf_type', 'section_id'
            ]);
            
            $contentData['course_id'] = $course->id;

            // Handle file uploads based on content type
            $uploadResult = $this->handleContentFileUpload($request, $course);
            
            if (!$uploadResult['success']) {
                return $this->errorResponse($uploadResult['message']);
            }

            $contentData = array_merge($contentData, $uploadResult['data']);
            
            $content = CourseContent::create($contentData);

            DB::commit();

            return $this->successResponse(
                __('messages.content_created_successfully'),
                $content,
                201
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.content_creation_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update course content
     */
    public function updateCourseContent(Request $request, CourseContent $content)
    {
        // Check permissions
        if (!$this->canManageCourse($content->course)) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        $validator = $this->validateContentRequest($request, $content->id);
        
        if ($validator->fails()) {
            return $this->validationErrorResponse(
                __('messages.validation_error'),
                $validator->errors()
            );
        }

        DB::beginTransaction();
        
        try {
            $contentData = $request->only([
                'title_en', 'title_ar', 'content_type', 'is_free', 
                'order', 'video_type', 'video_url', 'video_duration', 
                'pdf_type', 'section_id'
            ]);

            // Handle file uploads if new files are provided
            if ($request->hasFile('upload_video') || $request->hasFile('file_path')) {
                // Delete old files
                $this->deleteContentFiles($content);
                
                $uploadResult = $this->handleContentFileUpload($request, $content->course);
                
                if (!$uploadResult['success']) {
                    return $this->errorResponse($uploadResult['message']);
                }

                $contentData = array_merge($contentData, $uploadResult['data']);
            }
            
            $content->update($contentData);

            DB::commit();

            return $this->successResponse(
                __('messages.content_updated_successfully'),
                $content->fresh()
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.content_update_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a new course section
     */
    public function storeCourseSection(Request $request, Course $course)
    {
        // Check permissions
        if (!$this->canManageCourse($course)) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        $validator = $this->validateSectionRequest($request, $course);
        
        if ($validator->fails()) {
            return $this->validationErrorResponse(
                __('messages.validation_error'),
                $validator->errors()
            );
        }

        DB::beginTransaction();
        
        try {
            $section = $course->sections()->create([
                'title_en' => $request->title_en,
                'title_ar' => $request->title_ar,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();

            return $this->successResponse(
                __('messages.section_created_successfully'),
                $section,
                201
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.section_creation_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update a course section
     */
    public function updateCourseSection(Request $request, Course $course, CourseSection $section)
    {
        // Check permissions
        if (!$this->canManageCourse($course)) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        // Ensure section belongs to this course
        if ($section->course_id !== $course->id) {
            return $this->errorResponse(
                __('messages.section_not_found'),
                null,
                404
            );
        }

        $validator = $this->validateSectionRequest($request, $course, $section->id);
        
        if ($validator->fails()) {
            return $this->validationErrorResponse(
                __('messages.validation_error'),
                $validator->errors()
            );
        }

        DB::beginTransaction();
        
        try {
            $section->update([
                'title_en' => $request->title_en,
                'title_ar' => $request->title_ar,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();

            return $this->successResponse(
                __('messages.section_updated_successfully'),
                $section->fresh()
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.section_update_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete a course section
     */
    public function deleteCourseSection(Course $course, CourseSection $section)
    {
        // Check permissions
        if (!$this->canManageCourse($course)) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        // Ensure section belongs to this course
        if ($section->course_id !== $course->id) {
            return $this->errorResponse(
                __('messages.section_not_found'),
                null,
                404
            );
        }

        // Check if section has contents
        if ($section->contents()->count() > 0) {
            return $this->errorResponse(
                __('messages.section_has_contents_cannot_delete'),
                null,
                422
            );
        }

        // Check if section has child sections
        if ($section->children()->count() > 0) {
            return $this->errorResponse(
                __('messages.section_has_children_cannot_delete'),
                null,
                422
            );
        }

        DB::beginTransaction();
        
        try {
            $section->delete();

            DB::commit();

            return $this->successResponse(
                __('messages.section_deleted_successfully')
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.section_deletion_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete course content
     */
    public function deleteCourseContent(CourseContent $content)
    {
        $course = $content->course;

        // Check permissions
        if (!$this->canManageCourse($course)) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        DB::beginTransaction();
        
        try {
            // Delete files from Bunny CDN
            $this->deleteContentFiles($content);
            
            // Delete the content record
            $content->delete();

            DB::commit();

            return $this->successResponse(
                __('messages.content_deleted_successfully')
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.content_deletion_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete course and all related files
     */
    public function deleteCourse(Course $course)
    {
        if (!$this->canManageCourse($course)) {
            return $this->errorResponse(
                __('messages.unauthorized_action'),
                null,
                403
            );
        }

        DB::beginTransaction();
        
        try {
            // Delete course photo
            if ($course->photo) {
                BunnyHelper()->delete($course->photo);
            }

            // Delete all course content files
            foreach ($course->contents as $content) {
                $this->deleteContentFiles($content);
            }

            // Delete entire course folder from Bunny CDN
            BunnyHelper()->deleteFiles(CourseContent::BUNNY_PATH . '/' . $course->id);

            // Delete course (cascade will handle related records)
            $course->delete();

            DB::commit();

            return $this->successResponse(
                __('messages.course_deleted_successfully')
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            return $this->errorResponse(
                __('messages.course_deletion_failed'),
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Validate course request
     */
    protected function validateCourseRequest(Request $request, $isAdmin = false, $courseId = null)
    {
        $rules = [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'subject_id' => 'required|exists:subjects,id',
            'photo' => $courseId ? 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        if ($isAdmin) {
            // Admin can assign teacher - validate that user exists and has role_name = 'teacher'
            $rules['teacher_id'] = [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || $user->role_name !== 'teacher') {
                        $fail(__('validation.teacher_invalid'));
                    }
                }
            ];
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Validate content request
     */
    protected function validateContentRequest(Request $request, $contentId = null)
    {
        $rules = [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_type' => 'required|in:video,pdf,quiz,assignment',
            'is_free' => 'required|in:1,2',
            'order' => 'required|integer|min:0',
            'section_id' => 'sometimes|exists:course_sections,id'
        ];

        // Content type specific validations
        if ($request->content_type === 'video') {
            $rules['video_type'] = 'required|in:youtube,bunny';
            
            if ($request->video_type === 'youtube') {
                $rules['video_url'] = 'required|url';
            } else {
                $rules['upload_video'] = $contentId ? 'sometimes|file|mimes:mp4,avi,mov,wmv|max:512000' : 'required|file|mimes:mp4,avi,mov,wmv|max:512000';
            }
            
            $rules['video_duration'] = 'sometimes|integer|min:1';
        } else {
            $rules['pdf_type'] = 'required|in:homework,worksheet,notes,other';
            $rules['file_path'] = $contentId ? 'sometimes|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240';
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Validate section request
     */
    protected function validateSectionRequest(Request $request, Course $course, $sectionId = null)
    {
        $rules = [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:course_sections,id',
                function ($attribute, $value, $fail) use ($course, $sectionId) {
                    if ($value) {
                        // Check parent belongs to same course
                        $parentSection = CourseSection::find($value);
                        if (!$parentSection || $parentSection->course_id !== $course->id) {
                            $fail(__('validation.invalid_parent_section'));
                        }
                        
                        // Check not setting as own parent (for updates)
                        if ($sectionId && $value == $sectionId) {
                            $fail(__('validation.section_cannot_be_parent_of_itself'));
                        }
                    }
                }
            ]
        ];

        return Validator::make($request->all(), $rules);
    }

    /**
     * Prepare course data for storage
     */
    protected function prepareCourseData(Request $request, $isAdmin = false, $course = null)
    {
        $data = $request->only([
            'title_en', 'title_ar', 'description_en', 
            'description_ar', 'selling_price', 'subject_id'
        ]);

        if ($isAdmin) {
            // Admin can assign any teacher (teacher_id should refer to users.id where role_name = 'teacher')
            $data['teacher_id'] = $request->teacher_id;
        } else {
            // For teacher users, use their own user ID
            $data['teacher_id'] = auth()->user()->id;
        }

        return $data;
    }

    /**
     * Handle content file upload
     */
    protected function handleContentFileUpload(Request $request, Course $course)
    {
        $data = [
            'video_url' => null,
            'video_type' => null,
            'video_duration' => null,
            'file_path' => null,
            'pdf_type' => null
        ];

        try {
            if ($request->hasFile('upload_video') && $request->content_type === 'video') {
                $uploadResponse = BunnyHelper()->upload(
                    $request->upload_video,
                    CourseContent::BUNNY_PATH . '/' . $course->id
                );
                
                $uploadResponseData = $uploadResponse->getData();
                
                if ($uploadResponseData->success && $uploadResponseData->file_path) {
                    $data['video_url'] = $uploadResponseData->file_path;
                    $data['video_type'] = 'bunny';
                    
                    if ($request->has('video_duration')) {
                        $data['video_duration'] = $request->video_duration;
                    }
                } else {
                    return [
                        'success' => false,
                        'message' => __('messages.video_upload_failed')
                    ];
                }
            }

            if ($request->hasFile('file_path') && $request->content_type !== 'video') {
                $uploadResponse = BunnyHelper()->upload(
                    $request->file_path,
                    CourseContent::BUNNY_PATH . '/' . $course->id
                );
                
                $uploadResponseData = $uploadResponse->getData();
                
                if ($uploadResponseData->success && $uploadResponseData->file_path) {
                    $data['file_path'] = $uploadResponseData->file_path;
                    $data['pdf_type'] = $request->pdf_type;
                } else {
                    return [
                        'success' => false,
                        'message' => __('messages.file_upload_failed')
                    ];
                }
            }

            // Handle YouTube videos
            if ($request->content_type === 'video' && $request->video_type === 'youtube') {
                $data['video_url'] = $request->video_url;
                $data['video_type'] = 'youtube';
                $data['video_duration'] = $request->video_duration ?? null;
            }

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('messages.file_upload_error') . ': ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create course sections
     */
    protected function createCourseSections($courseId, $sections)
    {
        foreach ($sections as $sectionData) {
            $sectionData['course_id'] = $courseId;
            CourseSection::create($sectionData);
        }
    }

    /**
     * Update course sections
     */
    protected function updateCourseSections($courseId, $sections)
    {
        // This is a simplified version - you might want to implement more sophisticated logic
        // to handle updates, deletions, and additions
        CourseSection::where('course_id', $courseId)->delete();
        $this->createCourseSections($courseId, $sections);
    }

    /**
     * Create course contents
     */
    protected function createCourseContents(Course $course, $contents)
    {
        foreach ($contents as $contentData) {
            $contentData['course_id'] = $course->id;
            
            // Handle file uploads for each content
            $request = new Request($contentData);
            $uploadResult = $this->handleContentFileUpload($request, $course);
            
            if ($uploadResult['success']) {
                $contentData = array_merge($contentData, $uploadResult['data']);
                CourseContent::create($contentData);
            }
        }
    }

    /**
     * Update course contents
     */
    protected function updateCourseContents(Course $course, $contents)
    {
        // This is a simplified version - implement more sophisticated logic as needed
        CourseContent::where('course_id', $course->id)->delete();
        $this->createCourseContents($course, $contents);
    }

    /**
     * Delete content files from Bunny CDN
     */
    protected function deleteContentFiles(CourseContent $content)
    {
        if ($content->video_url && $content->video_type === 'bunny') {
            BunnyHelper()->delete($content->video_url);
        }
        
        if ($content->file_path) {
            BunnyHelper()->delete($content->file_path);
        }
    }

    /**
     * Check if user can manage course
     */
    protected function canManageCourse(Course $course)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Check if user is admin (from admins table) - you'll need to implement this check
        // This assumes you have a way to check if current user is an admin
        if (auth()->guard('admin')->check()) {
            return true;
        }
        
        // For teachers, they can only manage their own courses
        if ($user->role_name === 'teacher') {
            return $course->teacher_id === $user->id;
        }
        
        return false;
    }
}