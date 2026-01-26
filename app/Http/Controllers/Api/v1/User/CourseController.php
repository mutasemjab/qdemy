<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\CourseContent;
use App\Models\ContentUserProgress;
use App\Models\Subject;
use App\Repositories\CourseRepository;
use App\Traits\Responses;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use Responses;

    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get course details with user progress
     */
    public function show(Request $request, Course $course, $slug = null)
    {
        try {
            $user = $request->user(); // authenticated user from token

            // Get course contents and sections
            $contents = $course->contents;
            $mainSections = $course->sections?->where('parent_id', null);
            $freeContents = $contents?->where('is_free', 1)->first();

            // Check if user is enrolled
            $user_enrollment_courses = $this->courseRepository->getUserCoursesIds($user?->id);
            $is_enrolled = $user ? in_array($course->id, $user_enrollment_courses) : false;

            // Get user's content progress for this course (keyed by content_id)
            $userContentProgress = collect(); // Empty collection by default
            if ($is_enrolled && $user) {
                $userContentProgress = ContentUserProgress::where('user_id', $user->id)
                    ->whereIn('course_content_id', $contents->pluck('id'))
                    ->get()
                    ->keyBy('course_content_id');
            }

            $courseData = [
                'id' => $course->id,
                'title_en' => $course->title_en,
                'title_ar' => $course->title_ar,
                'description_en' => $course->description_en,
                'description_ar' => $course->description_ar,
                'selling_price' => $course->selling_price,
                'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                'teacher' => $course->teacher ? [
                    'id' => $course->teacher->id,
                    'name' => $course->teacher->name,
                    'name_of_lesson' => $course->teacher->name_of_lesson,
                    'photo' => $course->teacher->photo,
                    'description_ar' => $course->teacher->description_ar,
                    'social_media' => [
                        'facebook' => $course->teacher->facebook,
                        'instagram' => $course->teacher->instagram,
                        'youtube' => $course->teacher->youtube,
                        'whatsapp' => $course->teacher->whataspp
                    ]
                ] : null,
                'subject' => $course->subject ? [
                    'id' => $course->subject->id,
                    'name_ar' => $course->subject->name_ar,
                    'name_en' => $course->subject->name_en,
                    'color' => $course->subject->color,
                    'icon' => $course->subject->icon,
                    'grade_info' => $course->subject->grade ? [
                        'id' => $course->subject->grade->id,
                        'name_ar' => $course->subject->grade->name_ar,
                        'name_en' => $course->subject->grade->name_en,
                        'level' => $course->subject->grade->level
                    ] : null,
                    'semester_info' => $course->subject->semester ? [
                        'id' => $course->subject->semester->id,
                        'name_ar' => $course->subject->semester->name_ar,
                        'name_en' => $course->subject->semester->name_en
                    ] : null,
                    'program_info' => $course->subject->program ? [
                        'id' => $course->subject->program->id,
                        'name_ar' => $course->subject->program->name_ar,
                        'name_en' => $course->subject->program->name_en
                    ] : null
                ] : null,
                'is_enrolled' => $is_enrolled,
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at
            ];

            // Build structured sections with nested contents and exams
            $courseData['sections'] = [];

            if ($mainSections) {
                foreach ($mainSections as $section) {
                    $sectionData = [
                        'id' => $section->id,
                        'title_ar' => $section->title_ar,
                        'title_en' => $section->title_en,
                        'subsections' => []
                    ];

                    // Get subsections (children)
                    if ($section->children) {
                        foreach ($section->children as $child) {
                            $childData = [
                                'id' => $child->id,
                                'title_ar' => $child->title_ar,
                                'title_en' => $child->title_en,
                                'contents' => [],
                                'exams' => []
                            ];

                            // Get contents for this subsection
                            $sectionContents = $contents?->where('section_id', $child->id);
                            if ($sectionContents) {
                                foreach ($sectionContents as $content) {
                                    $locked = false;
                                    $isCompleted = false;
                                    if ($is_enrolled && $user) {
                                        $locked = $this->isContentLocked($course, $content, $user->id);
                                        // Check if content is completed
                                        $progress = $userContentProgress->get($content->id);
                                        $isCompleted = $progress ? (bool) $progress->completed : false;
                                    }

                                    $childData['contents'][] = [
                                        'id' => $content->id,
                                        'title_ar' => $content->title_ar,
                                        'title_en' => $content->title_en,
                                        'content_type' => $content->content_type,
                                        'is_free' => $content->is_free,
                                        'is_main_video' => $content->is_main_video,
                                        'order' => $content->order,
                                        'video_duration' => $content->video_duration,
                                        'video_duration_formatted' => gmdate('H:i:s', (int)$content->video_duration),
                                        'video_type' => $content->video_type,
                                        'video_url' => $content->video_url,
                                        'file_path' => $content->file_path,
                                        'pdf_type' => $content->pdf_type,
                                        'locked' => $locked,
                                        'is_completed' => $isCompleted
                                    ];
                                }
                            }

                            // Get exams for this subsection (only if user is enrolled)
                            if ($is_enrolled && $user) {
                                $sectionExams = $course->exams()->where('section_id', $child->id)->get();
                                foreach ($sectionExams as $exam) {
                                    $childData['exams'][] = [
                                        'id' => $exam->id,
                                        'title_ar' => $exam->title_ar,
                                        'title_en' => $exam->title_en,
                                        'description_ar' => $exam->description_ar,
                                        'description_en' => $exam->description_en,
                                        'duration_minutes' => $exam->duration_minutes,
                                        'total_grade' => $exam->total_grade,
                                        'passing_grade' => $exam->passing_grade,
                                        'is_active' => $exam->is_active,
                                        'start_date' => $exam->start_date,
                                        'end_date' => $exam->end_date,
                                        'attempts_allowed' => $exam->attempts_allowed
                                    ];
                                }
                            }

                            $sectionData['subsections'][] = $childData;
                        }
                    }

                    // Also check for direct contents in main section (if any)
                    $directContents = $contents?->where('section_id', $section->id);
                    if ($directContents && $directContents->isNotEmpty()) {
                        $sectionData['contents'] = [];
                        foreach ($directContents as $content) {
                            $locked = false;
                            $isCompleted = false;
                            if ($is_enrolled && $user) {
                                $locked = $this->isContentLocked($course, $content, $user->id);
                                // Check if content is completed
                                $progress = $userContentProgress->get($content->id);
                                $isCompleted = $progress ? (bool) $progress->completed : false;
                            }

                            $sectionData['contents'][] = [
                                'id' => $content->id,
                                'title_ar' => $content->title_ar,
                                'title_en' => $content->title_en,
                                'content_type' => $content->content_type,
                                'is_free' => $content->is_free,
                                'is_main_video' => $content->is_main_video,
                                'order' => $content->order,
                                'video_duration' => $content->video_duration,
                                'video_duration_formatted' => gmdate('H:i:s', (int)$content->video_duration),
                                'video_type' => $content->video_type,
                                'video_url' => $content->video_url,
                                'file_path' => $content->file_path,
                                'pdf_type' => $content->pdf_type,
                                'locked' => $locked,
                                'is_completed' => $isCompleted
                            ];
                        }
                    }

                    // Get exams for main section (only if user is enrolled)
                    if ($is_enrolled && $user) {
                        $sectionData['exams'] = [];
                        $mainSectionExams = $course->exams()->where('section_id', $section->id)->get();
                        foreach ($mainSectionExams as $exam) {
                            $sectionData['exams'][] = [
                                'id' => $exam->id,
                                'title_ar' => $exam->title_ar,
                                'title_en' => $exam->title_en,
                                'description_ar' => $exam->description_ar,
                                'description_en' => $exam->description_en,
                                'duration_minutes' => $exam->duration_minutes,
                                'total_grade' => $exam->total_grade,
                                'passing_grade' => $exam->passing_grade,
                                'is_active' => $exam->is_active,
                                'start_date' => $exam->start_date,
                                'end_date' => $exam->end_date,
                                'attempts_allowed' => $exam->attempts_allowed
                            ];
                        }
                    }

                    $courseData['sections'][] = $sectionData;
                }
            }

            $courseData['free_content'] = $freeContents ? [
                'id' => $freeContents->id,
                'title_ar' => $freeContents->title_ar,
                'title_en' => $freeContents->title_en,
                'content_type' => $freeContents->content_type,
                'video_url' => $freeContents->video_url,
                'file_path' => $freeContents->file_path,
                'video_duration' => $freeContents->video_duration,
                'video_duration_formatted' => gmdate('H:i:s', (int)$freeContents->video_duration)
            ] : null;

            // If user is enrolled, calculate progress
            if ($is_enrolled && $user) {
                $calculateCourseProgress = $this->calculateCourseProgress($user->id, $course->id);
                $userWatchTime = $this->courseRepository->getUserWatchTime($course->id, $user->id);
                $courseTotalHours = $this->courseRepository->getTotalVideoHours($course->id);

                $courseData['user_progress'] = [
                    'course_progress' => $calculateCourseProgress['course_progress'],
                    'completed_videos' => $calculateCourseProgress['completed_videos'],
                    'watching_videos' => $calculateCourseProgress['watching_videos'],
                    'total_videos' => $calculateCourseProgress['total_videos'],
                    'course_total_duration_seconds' => $courseTotalHours['total_seconds'],
                    'course_total_duration_formatted' => $courseTotalHours['formatted_duration'],
                    'user_watched_duration_seconds' => $userWatchTime['total_seconds'],
                    'user_watched_duration_formatted' => $userWatchTime['formatted_duration']
                ];
            } else {
                $courseData['user_progress'] = null;
            }

            return $this->success_response('Course details retrieved successfully', $courseData);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve course details: ' . $e->getMessage(), null);
        }
    }

    /**
     * Check if content is locked for user
     * Returns true if locked, false if unlocked
     */
    private function isContentLocked($course, $content, $userId)
    {


        // If course is not sequential, all content is unlocked
        if (! $course->is_sequential) {


            return false;
        }

        // If content is free (is_free == 1), it's not locked
        // is_free values: 1 = مجاني (free), 2 = مدفوع (paid)
        if ($content->is_free == 1) {
           

            return false;
        }

        // First content in order is always unlocked
        if ($content->order <= 1) {
          

            return false;
        }

        // Get the previous content (highest order less than current order) in the same section
        $previousContent = CourseContent::where('course_id', $course->id)
            ->where('section_id', $content->section_id)
            ->where('order', '<', $content->order)
            ->orderBy('order', 'desc')
            ->first();

        // If no previous content, current content is unlocked
        if (! $previousContent) {

            return false;
        }

        
        // Check if user has completed the previous content
        $previousProgress = ContentUserProgress::where('user_id', $userId)
            ->where('course_content_id', $previousContent->id)
            ->first();

        

        // If no progress record or not completed, content is locked
        if (! $previousProgress || ! $previousProgress->completed) {

            return true;
        }


        return false;
    }

    /**
     * Calculate course progress for user
     */
    private function calculateCourseProgress($userId, $courseId)
    {
        // Get all video contents for the course
        $totalVideos = CourseContent::where('course_id', $courseId)
            ->where('content_type', 'video')
            ->count();

        if ($totalVideos == 0) {
            return [
                'course_progress' => 0,
                'completed_videos' => 0,
                'watching_videos' => 0,
                'total_videos' => 0
            ];
        }

        // Get user progress for this course
        $completedVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->where('content_user_progress.completed', true)
            ->count();

        $watchingVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->where('content_user_progress.completed', false)
            ->where('content_user_progress.watch_time', '>', 0)
            ->count();

        $courseProgress = ($completedVideos / $totalVideos) * 100;

        return [
            'course_progress' => round($courseProgress, 2),
            'completed_videos' => $completedVideos,
            'watching_videos' => $watchingVideos,
            'total_videos' => $totalVideos
        ];
    }

    /**
     * Get courses by subject
     */
    public function coursesBySubject(Request $request, $subjectId)
    {
        try {
            $subject = Subject::with(['grade', 'semester', 'program'])->findOrFail($subjectId);

            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $courses = Course::where('status', 'accepted')->where('subject_id', $subject->id)
                ->with(['teacher', 'subject.grade', 'subject.semester', 'subject.program'])
                ->latest()
                ->paginate($perPage);

            $coursesData = $courses->getCollection()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                    'description_en' => $course->description_en,
                    'description_ar' => $course->description_ar,
                    'selling_price' => $course->selling_price,
                    'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                    'created_at' => $course->created_at,
                    'teacher' => $course->teacher ? [
                        'id' => $course->teacher->id,
                        'name' => $course->teacher->name,
                        'name_of_lesson' => $course->teacher->name_of_lesson,
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : asset('assets_front/images/Profile-picture.jpg')
                    ] : null,
                    'subject' => $course->subject ? [
                        'id' => $course->subject->id,
                        'name_ar' => $course->subject->name_ar,
                        'name_en' => $course->subject->name_en,
                        'color' => $course->subject->color,
                        'icon' => $course->subject->icon
                    ] : null
                ];
            });

            $responseData = [
                'subject' => [
                    'id' => $subject->id,
                    'name_ar' => $subject->name_ar,
                    'name_en' => $subject->name_en,
                    'description_ar' => $subject->description_ar,
                    'description_en' => $subject->description_en,
                    'icon' => $subject->icon,
                    'color' => $subject->color,
                    'grade_info' => $subject->grade ? [
                        'id' => $subject->grade->id,
                        'name_ar' => $subject->grade->name_ar,
                        'name_en' => $subject->grade->name_en,
                        'level' => $subject->grade->level
                    ] : null,
                    'semester_info' => $subject->semester ? [
                        'id' => $subject->semester->id,
                        'name_ar' => $subject->semester->name_ar,
                        'name_en' => $subject->semester->name_en
                    ] : null,
                    'program_info' => $subject->program ? [
                        'id' => $subject->program->id,
                        'name_ar' => $subject->program->name_ar,
                        'name_en' => $subject->program->name_en
                    ] : null
                ],
                'courses' => $coursesData,
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                    'has_more_pages' => $courses->hasMorePages()
                ]
            ];

            return $this->success_response('Subject courses retrieved successfully', $responseData);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve subject courses: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get international program courses
     */
    public function internationalProgramCourses(Request $request, $program = null)
    {
        try {
            $perPage = $request->get('per_page', 10);

            // Get international program subjects, then get courses for those subjects
            $internationalProgram = \App\Models\Category::where('ctg_key', 'international-program')->first();

            if (!$internationalProgram) {
                return $this->error_response('International program not found', null);
            }

            $subjects = Subject::where('programm_id', $internationalProgram->id)
                ->where('is_active', true)
                ->pluck('id');

            $courses = Course::where('status', 'accepted')->whereIn('subject_id', $subjects)
                ->with(['teacher', 'subject'])
                ->latest()
                ->paginate($perPage);

            $coursesData = $courses->getCollection()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                    'description_en' => $course->description_en,
                    'description_ar' => $course->description_ar,
                    'selling_price' => $course->selling_price,
                    'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                    'created_at' => $course->created_at,
                    'teacher' => $course->teacher ? [
                        'id' => $course->teacher->id,
                        'name' => $course->teacher->name,
                        'name_of_lesson' => $course->teacher->name_of_lesson,
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : asset('assets_front/images/Profile-picture.jpg')
                    ] : null,
                    'subject' => $course->subject ? [
                        'id' => $course->subject->id,
                        'name_ar' => $course->subject->name_ar,
                        'name_en' => $course->subject->name_en,
                        'color' => $course->subject->color,
                        'icon' => $course->subject->icon
                    ] : null
                ];
            });

            $responseData = [
                'program' => [
                    'id' => $internationalProgram->id,
                    'name_ar' => $internationalProgram->name_ar,
                    'name_en' => $internationalProgram->name_en,
                    'ctg_key' => $internationalProgram->ctg_key
                ],
                'program_type' => $program,
                'courses' => $coursesData,
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                    'has_more_pages' => $courses->hasMorePages()
                ]
            ];

            return $this->success_response('International program courses retrieved successfully', $responseData);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve international program courses: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get universities program courses
     */
    public function universitiesProgramCourses(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            // Get universities program subjects, then get courses for those subjects
            $universitiesProgram = \App\Models\Category::where('ctg_key', 'universities-and-colleges-program')->first();

            if (!$universitiesProgram) {
                return $this->error_response('Universities program not found', null);
            }

            $subjects = Subject::where('programm_id', $universitiesProgram->id)
                ->where('is_active', true)
                ->pluck('id');

            $courses = Course::where('status', 'accepted')->whereIn('subject_id', $subjects)
                ->with(['teacher', 'subject'])
                ->latest()
                ->paginate($perPage);

            $coursesData = $courses->getCollection()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                    'description_en' => $course->description_en,
                    'description_ar' => $course->description_ar,
                    'selling_price' => $course->selling_price,
                    'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                    'created_at' => $course->created_at,
                    'teacher' => $course->teacher ? [
                        'id' => $course->teacher->id,
                        'name' => $course->teacher->name,
                        'name_of_lesson' => $course->teacher->name_of_lesson,
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : asset('assets_front/images/Profile-picture.jpg')
                    ] : null,
                    'subject' => $course->subject ? [
                        'id' => $course->subject->id,
                        'name_ar' => $course->subject->name_ar,
                        'name_en' => $course->subject->name_en,
                        'color' => $course->subject->color,
                        'icon' => $course->subject->icon
                    ] : null
                ];
            });

            $responseData = [
                'program' => [
                    'id' => $universitiesProgram->id,
                    'name_ar' => $universitiesProgram->name_ar,
                    'name_en' => $universitiesProgram->name_en,
                    'ctg_key' => $universitiesProgram->ctg_key
                ],
                'courses' => $coursesData,
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                    'has_more_pages' => $courses->hasMorePages()
                ]
            ];

            return $this->success_response('Universities program courses retrieved successfully', $responseData);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve universities program courses: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get all courses with filters
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $subjectId = $request->get('subject_id');
            $teacherId = $request->get('teacher_id');
            $search = $request->get('search');
            $sortBy = $request->get('sort_by', 'latest'); // latest, price_low, price_high, name

            $query = Course::where('status', 'accepted')->with(['teacher', 'subject']);

            // Apply filters
            if ($subjectId) {
                $query->where('subject_id', $subjectId);
            }

            if ($teacherId) {
                $query->where('teacher_id', $teacherId);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title_ar', 'like', "%{$search}%")
                        ->orWhere('title_en', 'like', "%{$search}%")
                        ->orWhere('description_ar', 'like', "%{$search}%")
                        ->orWhere('description_en', 'like', "%{$search}%");
                });
            }

            // Apply sorting
            switch ($sortBy) {
                case 'price_low':
                    $query->orderBy('selling_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('selling_price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('title_ar', 'asc');
                    break;
                default:
                    $query->latest();
            }

            $courses = $query->paginate($perPage);

            $coursesData = $courses->getCollection()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                    'description_en' => $course->description_en,
                    'description_ar' => $course->description_ar,
                    'selling_price' => $course->selling_price,
                    'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                    'created_at' => $course->created_at,
                    'teacher' => $course->teacher ? [
                        'id' => $course->teacher->id,
                        'name' => $course->teacher->name,
                        'name_of_lesson' => $course->teacher->name_of_lesson,
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : asset('assets_front/images/Profile-picture.jpg')
                    ] : null,
                    'subject' => $course->subject ? [
                        'id' => $course->subject->id,
                        'name_ar' => $course->subject->name_ar,
                        'name_en' => $course->subject->name_en,
                        'color' => $course->subject->color,
                        'icon' => $course->subject->icon
                    ] : null
                ];
            });

            $responseData = [
                'courses' => $coursesData,
                'filters' => [
                    'subject_id' => $subjectId,
                    'teacher_id' => $teacherId,
                    'search' => $search,
                    'sort_by' => $sortBy
                ],
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                    'has_more_pages' => $courses->hasMorePages()
                ]
            ];

            return $this->success_response('Courses retrieved successfully', $responseData);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve courses: ' . $e->getMessage(), null);
        }
    }
}
