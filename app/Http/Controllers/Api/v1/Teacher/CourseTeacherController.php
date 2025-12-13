<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Category;
use App\Models\User;
use App\Traits\CourseManagementTrait;
use App\Traits\SubjectCategoryTrait;
use App\Traits\Responses;
use Illuminate\Http\Request;

class CourseTeacherController extends Controller
{
    use CourseManagementTrait, SubjectCategoryTrait, Responses;

    /**
     * Get teacher's courses list
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $courses = Course::with(['subject:id,name_ar,name_en'])
                ->withCount('students') // Add this line to get enrolled students count
                ->where('teacher_id', $user->id)
                ->paginate(10);

            $coursesData = $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title_en' => $course->title_en,
                    'title_ar' => $course->title_ar,
                    'description_en' => $course->description_en,
                    'description_ar' => $course->description_ar,
                    'selling_price' => $course->selling_price,
                    'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                    'subject' => $course->subject ? [
                        'id' => $course->subject->id,
                        'name_ar' => $course->subject->name_ar,
                        'name_en' => $course->subject->name_en,
                    ] : null,
                    'sections_count' => $course->sections()->count(),
                    'contents_count' => $course->contents()->count(),
                    'enrolled_students_count' => $course->students_count, 
                    'created_at' => $course->created_at,
                    'updated_at' => $course->updated_at
                ];
            });

            return $this->success_response('Courses retrieved successfully', [
                'courses' => $coursesData,
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve courses: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get course details
     */
    public function show(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::with([
                'subject:id,name_ar,name_en',
                'sections.contents' => function($query) {
                    $query->orderBy('order');
                }
            ])
            ->where('teacher_id', $user->id)
            ->findOrFail($courseId);

            $courseData = [
                'id' => $course->id,
                'title_en' => $course->title_en,
                'title_ar' => $course->title_ar,
                'description_en' => $course->description_en,
                'description_ar' => $course->description_ar,
                'selling_price' => $course->selling_price,
                'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                'subject' => $course->subject ? [
                    'id' => $course->subject->id,
                    'name_ar' => $course->subject->name_ar,
                    'name_en' => $course->subject->name_en,
                ] : null,
                'sections' => $course->sections->map(function ($section) {
                    return [
                        'id' => $section->id,
                        'title_en' => $section->title_en,
                        'title_ar' => $section->title_ar,
                        'parent_id' => $section->parent_id,
                        'contents_count' => $section->contents->count(),
                        'contents' => $section->contents->map(function ($content) {
                            return [
                                'id' => $content->id,
                                'title_en' => $content->title_en,
                                'title_ar' => $content->title_ar,
                                'content_type' => $content->content_type,
                                'is_free' => $content->is_free,
                                'is_main_video' => $content->is_main_video,
                                'order' => $content->order,
                                'video_duration' => $content->video_duration,
                                'created_at' => $content->created_at
                            ];
                        })
                    ];
                }),
                'total_sections' => $course->sections->count(),
                'total_contents' => $course->contents->count(),
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at
            ];

            return $this->success_response('Course retrieved successfully', $courseData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve course: ' . $e->getMessage(), null);
        }
    }

   

    /**
     * Get subjects by category (for dynamic loading)
     */
    public function getSubjectsByCategory(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $categoryId = $request->get('category_id');
            
            if (!$categoryId) {
                return $this->success_response('Subjects retrieved successfully', []);
            }

            $subjects = $this->getSubjectsByCategoryForApi($categoryId);
            
            return $this->success_response('Subjects retrieved successfully', $subjects->toArray());
            
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve subjects: ' . $e->getMessage(), null);
        }
    }

    /**
     * Create a new course
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Use the trait method (isAdmin = false)
            return $this->storeCourse($request, false);

        } catch (\Exception $e) {
            return $this->error_response('Failed to create course: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update a course
     */
    public function update(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            // Use the trait method (isAdmin = false)
            return $this->updateCourse($request, $course, false);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update course: ' . $e->getMessage(), null);
        }
    }

    /**
     * Delete a course
     */
    public function destroy(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            // Use the trait method
            return $this->deleteCourse($course);

        } catch (\Exception $e) {
            return $this->error_response('Failed to delete course: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get enrolled students for a course
     */
    public function getEnrolledStudents(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $course = Course::where('teacher_id', $user->id)->findOrFail($courseId);

            // This assumes you have an enrollments table - adjust based on your structure
            $enrolledStudents = $course->enrollments()->with('user:id,name,email,phone,photo')->get()->map(function ($enrollment) {
                return [
                    'enrollment_id' => $enrollment->id,
                    'student' => [
                        'id' => $enrollment->user->id,
                        'name' => $enrollment->user->name,
                        'email' => $enrollment->user->email,
                        'phone' => $enrollment->user->phone,
                        'photo' => $enrollment->user->photo ? asset('assets/admin/uploads/' . $enrollment->user->photo) : null,
                    ],
                    'enrolled_at' => $enrollment->created_at,
                    'progress' => $enrollment->progress ?? 0
                ];
            });

            return $this->success_response('Enrolled students retrieved successfully', [
                'course_id' => $course->id,
                'course_title' => $course->title_en . ' / ' . $course->title_ar,
                'students' => $enrolledStudents,
                'total_students' => $enrolledStudents->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve enrolled students: ' . $e->getMessage(), null);
        }
    }

}