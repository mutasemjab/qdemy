<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\ContentUserProgress;
use App\Models\Exam;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    use Responses;

    /**
     * Get course progress for authenticated user
     */
    public function getCourseProgress(Request $request, $courseId)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return $this->error_response('Unauthorized', null, 401);
            }

            $course = Course::findOrFail($courseId);
            
            // Check if user is enrolled
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();
            
            if (!$isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            $progress = $course->calculateCourseProgress($user->id);

            return $this->success_response('Course progress retrieved successfully', $progress);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve course progress: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update video progress
     */
    public function updateVideoProgress(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return $this->error_response('Unauthorized', null, 401);
            }

            $validator = Validator::make($request->all(), [
                'course_content_id' => 'required|exists:course_contents,id',
                'watch_time' => 'required|integer|min:0',
                'completed' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors(), 422);
            }

            $courseContent = CourseContent::findOrFail($request->course_content_id);
            
            // Check if user is enrolled in the course
            $isEnrolled = $courseContent->course->enrollments()
                ->where('user_id', $user->id)
                ->exists();
            
            if (!$isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            // Determine if completed based on watch time (90% threshold)
            $completed = $request->has('completed') 
                ? $request->completed 
                : ($courseContent->video_duration && $request->watch_time >= $courseContent->video_duration * 0.9);

            $progress = ContentUserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_content_id' => $request->course_content_id,
                    'exam_id' => null, // Ensure this is video progress
                ],
                [
                    'watch_time' => $request->watch_time,
                    'completed' => $completed,
                    'viewed_at' => now(),
                    'score' => null,
                    'percentage' => null,
                    'is_passed' => false,
                ]
            );

            // Get updated course progress
            $courseProgress = $courseContent->course->calculateCourseProgress($user->id);

            return $this->success_response('Video progress updated successfully', [
                'progress' => [
                    'id' => $progress->id,
                    'course_content_id' => $progress->course_content_id,
                    'watch_time' => $progress->watch_time,
                    'completed' => $progress->completed,
                    'viewed_at' => $progress->viewed_at,
                ],
                'course_progress' => $courseProgress,
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update video progress: ' . $e->getMessage(), null);
        }
    }

    /**
     * Mark content as completed (for PDFs, documents, etc.)
     */
    public function markContentCompleted(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return $this->error_response('Unauthorized', null, 401);
            }

            $validator = Validator::make($request->all(), [
                'course_content_id' => 'required|exists:course_contents,id',
            ]);

            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors(), 422);
            }

            $courseContent = CourseContent::findOrFail($request->course_content_id);
            
            // Check if user is enrolled
            $isEnrolled = $courseContent->course->enrollments()
                ->where('user_id', $user->id)
                ->exists();
            
            if (!$isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            $progress = ContentUserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_content_id' => $request->course_content_id,
                    'exam_id' => null,
                ],
                [
                    'completed' => true,
                    'viewed_at' => now(),
                ]
            );

            // Get updated course progress
            $courseProgress = $courseContent->course->calculateCourseProgress($user->id);

            return $this->success_response('Content marked as completed', [
                'progress' => [
                    'id' => $progress->id,
                    'course_content_id' => $progress->course_content_id,
                    'completed' => $progress->completed,
                    'viewed_at' => $progress->viewed_at,
                ],
                'course_progress' => $courseProgress,
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to mark content as completed: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get detailed progress for a course (videos and exams breakdown)
     */
    public function getCourseDetailedProgress(Request $request, $courseId)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return $this->error_response('Unauthorized', null, 401);
            }

            $course = Course::with(['contents', 'exams'])->findOrFail($courseId);
            
            // Check if user is enrolled
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();
            
            if (!$isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            // Get all progress records for this course
            $allProgress = ContentUserProgress::where('user_id', $user->id)
                ->where(function($query) use ($course) {
                    // Video progress
                    $query->whereIn('course_content_id', $course->contents->pluck('id'))
                          ->whereNull('exam_id');
                })
                ->orWhere(function($query) use ($course) {
                    // Exam progress
                    $query->whereIn('exam_id', $course->exams->pluck('id'))
                          ->whereNull('course_content_id');
                })
                ->get();

            // Separate video and exam progress
            $videoProgress = $allProgress->where('course_content_id', '!=', null)->map(function ($progress) {
                return [
                    'id' => $progress->id,
                    'content_id' => $progress->course_content_id,
                    'watch_time' => $progress->watch_time,
                    'completed' => $progress->completed,
                    'viewed_at' => $progress->viewed_at,
                ];
            })->values();

            $examProgress = $allProgress->where('exam_id', '!=', null)->map(function ($progress) {
                return [
                    'id' => $progress->id,
                    'exam_id' => $progress->exam_id,
                    'exam_attempt_id' => $progress->exam_attempt_id,
                    'completed' => $progress->completed,
                    'score' => $progress->score,
                    'percentage' => $progress->percentage,
                    'is_passed' => $progress->is_passed,
                    'viewed_at' => $progress->viewed_at,
                ];
            })->values();

            // Get overall progress
            $overallProgress = $course->calculateCourseProgress($user->id);

            return $this->success_response('Detailed course progress retrieved successfully', [
                'overall_progress' => $overallProgress,
                'video_progress_details' => $videoProgress,
                'exam_progress_details' => $examProgress,
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve detailed progress: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get progress summary for all enrolled courses
     */
    public function getAllCoursesProgress(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return $this->error_response('Unauthorized', null, 401);
            }

            $enrolledCourses = $user->enrollments()
                ->with('course')
                ->get()
                ->map(function ($enrollment) use ($user) {
                    $course = $enrollment->course;
                    $progress = $course->calculateCourseProgress($user->id);
                    
                    return [
                        'course_id' => $course->id,
                        'course_title_ar' => $course->title_ar,
                        'course_title_en' => $course->title_en,
                        'course_photo' => $course->photo_url,
                        'progress' => $progress,
                        'enrolled_at' => $enrollment->created_at,
                    ];
                });

            return $this->success_response('All courses progress retrieved successfully', [
                'courses' => $enrolledCourses,
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve all courses progress: ' . $e->getMessage(), null);
        }
    }
}