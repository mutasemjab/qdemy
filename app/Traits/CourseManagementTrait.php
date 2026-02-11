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
use Illuminate\Validation\Rule;



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

            // Use different message based on whether it's admin or teacher creating the course
            $message = $isAdmin
                ? __('messages.course_created_successfully')
                : __('messages.course_created_awaiting_review');

            return $this->successResponse(
                $message,
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
        if (!$isAdmin && $course->teacher_id !== auth()->user()->id) {
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
                'title_en',
                'title_ar',
                'content_type',
                'is_free',
                'is_main_video',
                'order',
                'video_type',
                'video_url',
                'video_duration',
                'pdf_type',
                'section_id'
            ]);

            $contentData['course_id'] = $course->id;

            // Auto-calculate order if not provided (default to max order + 1)
            if (!$request->filled('order') || $request->order === null) {
                $maxOrder = CourseContent::where('course_id', $course->id)->max('order') ?? 0;
                $contentData['order'] = $maxOrder + 1;
                \Log::info('Auto-calculated order for new content', [
                    'course_id' => $course->id,
                    'max_order' => $maxOrder,
                    'assigned_order' => $contentData['order'],
                ]);
            }

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
                'title_en',
                'title_ar',
                'content_type',
                'is_free',
                'is_main_video',
                'order',
                'video_type',
                'video_duration',
                'pdf_type',
                'section_id'
            ]);

            // Handle file uploads if new files are provided OR if bunny_video_path is provided
            if ($request->hasFile('upload_video') || $request->hasFile('file_path') || $request->filled('bunny_video_path')) {
                // Only delete old files if we're actually uploading new ones
                if ($request->hasFile('upload_video') || $request->filled('bunny_video_path')) {
                    $this->deleteContentFiles($content);
                } elseif ($request->hasFile('file_path')) {
                    $this->deleteContentFiles($content);
                }

                $uploadResult = $this->handleContentFileUpload($request, $content->course);

                if (!$uploadResult['success']) {
                    return $this->errorResponse($uploadResult['message']);
                }

                $contentData = array_merge($contentData, $uploadResult['data']);
            } else {
                // No new files - handle video_url for YouTube updates
                if ($request->content_type === 'video' && $request->video_type === 'youtube' && $request->filled('video_url')) {
                    $contentData['video_url'] = $request->video_url;
                }
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
                'order' => $request->order,
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
                'order' => $request->order,
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
            'commission_of_admin' => 'nullable|numeric',
            'subject_id' => 'required|exists:subjects,id',
            'is_sequential' => 'nullable|boolean',
            'photo' => $courseId ? 'sometimes|image|mimes:jpeg,png,jpg' : 'required|image|mimes:jpeg,png,jpg'
        ];

        $data = [
            'title_en' => $request->input('title_en'),
            'title_ar' => $request->input('title_ar'),
            'description_en' => $request->input('description_en'),
            'description_ar' => $request->input('description_ar'),
            'selling_price' => $request->input('selling_price'),
            'commission_of_admin' => $request->input('commission_of_admin'),
            'subject_id' => $request->input('subject_id'),
            'is_sequential' => $request->boolean('is_sequential'),
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }

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
            // Admin can set course status
            $rules['status'] = 'required|in:pending,accepted,rejected';

            $data['teacher_id'] = $request->input('teacher_id');
            $data['status'] = $request->input('status');
        }

        return Validator::make($data, $rules);
    }

    /**
     * Validate content request
     */
    /**
     * Validate content request
     */
    protected function validateContentRequest(Request $request, $contentId = null)
    {
        $rules = [
            'title_en'     => 'required|string|max:255',
            'title_ar'     => 'required|string|max:255',
            'content_type' => 'required|in:video,pdf,quiz,assignment',
            'is_free'      => 'required|in:1,2',
            'is_main_video' => 'required|in:1,2',
            'order'        => 'nullable|integer|min:1', // Optional - will auto-calculate if not provided. Min 1 to ensure valid sequence.
            'section_id'   => 'nullable|exists:course_sections,id'
        ];

        if ($request->content_type === 'video') {
            $rules['video_type'] = 'required|in:youtube,bunny';

            if ($request->video_type === 'youtube') {
                // YouTube requires video_url
                $rules['video_url'] = 'required|url';
            }

            if ($request->video_type === 'bunny') {
                // For Bunny: Either bunny_video_path (new upload) OR video_url (keep existing)
                // At least one must be present for create
                if (!$contentId) {
                    // CREATE: Must have bunny_video_path (from JavaScript upload) or video_url
                    $rules['bunny_video_path'] = 'required_without:video_url|nullable|string';
                    $rules['video_url'] = 'required_without:bunny_video_path|nullable|string';
                } else {
                    // EDIT: Optional - either bunny_video_path or video_url or neither (keep existing)
                    $rules['bunny_video_path'] = 'nullable|string';
                    $rules['video_url'] = 'nullable|string';
                }
            }

            $rules['video_duration'] = 'nullable|integer|min:1';
        } else {
            $rules['pdf_type'] = 'required|in:homework,worksheet,notes,other';
            $rules['file_path'] = $contentId
                ? 'nullable|file|mimes:pdf'
                : 'required|file|mimes:pdf';
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Validate section request
     */
    protected function validateSectionRequest(Request $request, Course $course, $sectionId = null)
    {
        // Build order unique rule - unique per course
        $orderRule = Rule::unique('course_sections', 'order')
            ->where('course_id', $course->id);

        // For updates, exclude the current section
        if ($sectionId) {
            $orderRule->ignore($sectionId);
        }

        $rules = [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'order' => ['required', 'integer', 'min:0', $orderRule],
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
            'title_en',
            'title_ar',
            'description_en',
            'description_ar',
            'commission_of_admin',
            'selling_price',
            'subject_id'
        ]);

        // Handle boolean checkbox properly
        $data['is_sequential'] = $request->boolean('is_sequential');

        if ($isAdmin) {
            // Admin can assign any teacher (teacher_id should refer to users.id where role_name = 'teacher')
            $data['teacher_id'] = $request->teacher_id;
            // Admin can set status explicitly
            $data['status'] = $request->status;
        } else {
            // For teacher users, use their own user ID
            $data['teacher_id'] = auth()->user()->id;
            // When teacher edits a course, it goes back to pending for admin review
            $data['status'] = 'pending';
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
            // ✅ Handle direct Bunny upload (when bunny_video_path is provided from edit form)
            if ($request->content_type === 'video' && $request->video_type === 'bunny' && $request->filled('bunny_video_path')) {
                $data['video_url'] = $request->bunny_video_path;
                $data['video_type'] = 'bunny';

                if ($request->has('video_duration')) {
                    $data['video_duration'] = $request->video_duration;
                }

                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            // ✅ Handle direct Bunny upload (when video_url is already provided from create form)
            if ($request->content_type === 'video' && $request->video_type === 'bunny' && $request->filled('video_url') && !$request->hasFile('upload_video')) {
                $data['video_url'] = $request->video_url;
                $data['video_type'] = 'bunny';

                if ($request->has('video_duration')) {
                    $data['video_duration'] = $request->video_duration;
                }

                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            // Handle old server-side upload (fallback)
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
