<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\CourseContent;
use App\Models\ContentUserProgress;
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
                    'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : null,
                    'description_ar' => $course->teacher->description_ar,
                    'social_media' => [
                        'facebook' => $course->teacher->facebook,
                        'instagram' => $course->teacher->instagram,
                        'youtube' => $course->teacher->youtube,
                        'whatsapp' => $course->teacher->whataspp
                    ]
                ] : null,
                'category' => $course->category ? [
                    'id' => $course->category->id,
                    'name_ar' => $course->category->name_ar,
                    'name_en' => $course->category->name_en,
                    'color' => $course->category->color,
                    'icon' => $course->category->icon
                ] : null,
                'is_enrolled' => $is_enrolled,
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at
            ];

            // Add sections and contents
            $courseData['main_sections'] = $mainSections ? $mainSections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                    'description' => $section->description,
                    'sort_order' => $section->sort_order,
                    'is_active' => $section->is_active,
                    'children' => $section->children ? $section->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'title' => $child->title,
                            'description' => $child->description,
                            'sort_order' => $child->sort_order,
                            'is_active' => $child->is_active
                        ];
                    }) : []
                ];
            }) : [];

            $courseData['contents'] = $contents ? $contents->map(function ($content) {
                return [
                    'id' => $content->id,
                    'title' => $content->title,
                    'description' => $content->description,
                    'content_type' => $content->content_type,
                    'is_free' => $content->is_free,
                    'sort_order' => $content->sort_order,
                    'duration' => $content->duration,
                    'file_path' => $content->file_path,
                    'video_url' => $content->video_url,
                    'section_id' => $content->section_id
                ];
            }) : [];

            $courseData['free_content'] = $freeContents ? [
                'id' => $freeContents->id,
                'title' => $freeContents->title,
                'description' => $freeContents->description,
                'content_type' => $freeContents->content_type,
                'video_url' => $freeContents->video_url,
                'file_path' => $freeContents->file_path,
                'duration' => $freeContents->duration
            ] : null;

            // If user is enrolled, calculate progress
            if ($is_enrolled && $user) {
                $calculateCourseProgress = $this->calculateCourseProgress($user->id, $course->id);

                $courseData['user_progress'] = [
                    'course_progress' => $calculateCourseProgress['course_progress'],
                    'completed_videos' => $calculateCourseProgress['completed_videos'],
                    'watching_videos' => $calculateCourseProgress['watching_videos'],
                    'total_videos' => $calculateCourseProgress['total_videos']
                ];

                // Add exams if available
                $exams = $course->exams;
                $courseData['exams'] = $exams ? $exams->map(function ($exam) {
                    return [
                        'id' => $exam->id,
                        'title' => $exam->title,
                        'description' => $exam->description,
                        'duration' => $exam->duration,
                        'total_marks' => $exam->total_marks,
                        'passing_marks' => $exam->passing_marks,
                        'is_active' => $exam->is_active
                    ];
                }) : [];
            } else {
                $courseData['user_progress'] = null;
                $courseData['exams'] = null;
            }

            return $this->success_response('Course details retrieved successfully', $courseData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve course details: ' . $e->getMessage(), null);
        }
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
     * Get courses by subject/category
     */
    public function coursesBySubject(Request $request, $subjectId)
    {
        try {
            $subject = Category::findOrFail($subjectId);

            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $courses = Course::where('category_id', $subject->id)
                ->with(['teacher', 'category'])
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
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : null
                    ] : null,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name_ar' => $course->category->name_ar,
                        'name_en' => $course->category->name_en,
                        'color' => $course->category->color,
                        'icon' => $course->category->icon
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
                    'color' => $subject->color
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
    public function internationalProgramSubjects(Request $request, $program = null)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $courses = $this->courseRepository->internationalProgramSubjects($program)->paginate($perPage);

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
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : null
                    ] : null,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name_ar' => $course->category->name_ar,
                        'name_en' => $course->category->name_en,
                        'color' => $course->category->color,
                        'icon' => $course->category->icon
                    ] : null
                ];
            });

            $responseData = [
                'program_title' => 'International Program',
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
    public function universitiesProgramSubjects(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $courses = $this->courseRepository->universitiesProgramSubjects()->paginate($perPage);

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
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : null
                    ] : null,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name_ar' => $course->category->name_ar,
                        'name_en' => $course->category->name_en,
                        'color' => $course->category->color,
                        'icon' => $course->category->icon
                    ] : null
                ];
            });

            $responseData = [
                'program_title' => 'Universities and Colleges Program',
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
            $categoryId = $request->get('category_id');
            $teacherId = $request->get('teacher_id');
            $search = $request->get('search');
            $sortBy = $request->get('sort_by', 'latest'); // latest, price_low, price_high, name

            $query = Course::with(['teacher', 'category']);

            // Apply filters
            if ($categoryId) {
                $query->where('category_id', $categoryId);
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
                        'photo' => $course->teacher->photo ? asset('assets/admin/uploads/' . $course->teacher->photo) : null
                    ] : null,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name_ar' => $course->category->name_ar,
                        'name_en' => $course->category->name_en,
                        'color' => $course->category->color,
                        'icon' => $course->category->icon
                    ] : null
                ];
            });

            $responseData = [
                'courses' => $coursesData,
                'filters' => [
                    'category_id' => $categoryId,
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
