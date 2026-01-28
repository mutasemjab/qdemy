<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\ContentUserProgress;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Exam;
use App\Repositories\CourseRepository;
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

            if (! $user) {
                return $this->error_response('Unauthorized', null, 401);
            }

            $course = Course::findOrFail($courseId);

            // Check if user is enrolled
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();

            if (! $isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            $progress = $course->calculateCourseProgress($user->id);

            // Get course total hours and user watch time
            $courseRepository = new CourseRepository();
            $courseTotalHours = $courseRepository->getTotalVideoHours($courseId);
            $userWatchTime = $courseRepository->getUserWatchTime($courseId, $user->id);

            $progressData = array_merge($progress, [
                'course_total_duration_seconds' => $courseTotalHours['total_seconds'],
                'course_total_duration_formatted' => $courseTotalHours['formatted_duration'],
                'user_watched_duration_seconds' => $userWatchTime['total_seconds'],
                'user_watched_duration_formatted' => $userWatchTime['formatted_duration'],
            ]);

            return $this->success_response('Course progress retrieved successfully', $progressData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve course progress: '.$e->getMessage(), null);
        }
    }

    /**
     * Update video progress
     */
    public function updateVideoProgress(Request $request)
    {
        try {
            $user = $request->user();

            if (! $user) {
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

            // Validate watch_time doesn't exceed video duration
            if ($courseContent->video_duration && $request->watch_time > $courseContent->video_duration) {
                return $this->error_response('Watch time cannot exceed video duration', null, 422);
            }

            // Check if user is enrolled in the course
            $isEnrolled = $courseContent->course->enrollments()
                ->where('user_id', $user->id)
                ->exists();

            if (! $isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            // Determine if video is completed
            // completed=1 from mobile means normal watching (not truly completed) → treat as false
            // completed=2 from mobile means user clicked "mark as completed" → treat as true
            $manualComplete = $request->has('completed') && $request->completed == 2;

            $videoCompleted = $manualComplete
                || ($courseContent->video_duration && $request->watch_time >= $courseContent->video_duration * 0.9);

           

            // Get or create progress record (single record per user+content)
            $progress = ContentUserProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'course_content_id' => $request->course_content_id,
                ],
                [
                    'exam_id' => null,
                    'watch_time' => 0,
                    'completed' => false,
                ]
            );



            // Update video-related fields (preserve exam data if present)
            // If manual complete (completed=2), set watch_time to full video duration
            if ($manualComplete && $courseContent->video_duration) {
                $progress->watch_time = $courseContent->video_duration;
            } else {
                // Keep the maximum watch time (highest position reached in the video)
                $progress->watch_time = max($progress->watch_time, $request->watch_time);
            }
            $progress->viewed_at = now();
            $progress->save();

            // Check if this lesson has a linked exam
            $linkedExam = $courseContent->exams()->where('is_active', 1)->first();

            // Check if exam is completed (exam_id is set in the progress record)
            $isExamCompleted = $linkedExam && $progress->exam_id == $linkedExam->id;

            

            // Calculate lesson completion status
            // If lesson has exam: lesson is complete only when BOTH video AND exam are completed
            // If no exam: lesson is complete when video is completed
            $lessonCompleted = $videoCompleted; // Default: video completion = lesson completion
            $lessonProgress = $videoCompleted ? 100 : 0;

            if ($linkedExam) {
                // Lesson is complete only if both video AND exam are done
                $lessonCompleted = $videoCompleted && $isExamCompleted;

                // Calculate lesson progress: 50% video + 50% exam
                $lessonProgress = 0;
                if ($videoCompleted) {
                    $lessonProgress += 50;
                }
                if ($isExamCompleted) {
                    $lessonProgress += 50;
                }
            }

            // Update the completed flag on the record based on full lesson completion
            $progress->completed = $lessonCompleted;
            $progress->save();

           

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
                'exam_completed' => $isExamCompleted,
                'lesson_completed' => $lessonCompleted,
                'lesson_progress' => $lessonProgress,
                'has_exam' => $linkedExam !== null,
                'exam_id' => $linkedExam?->id,
                'course_progress' => $courseProgress,
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update video progress: '.$e->getMessage(), null);
        }
    }

    /**
     * Mark content as completed (for PDFs, documents, etc.)
     */
    public function markContentCompleted(Request $request)
    {
        try {
            $user = $request->user();

            if (! $user) {
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

            if (! $isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            // Get or create progress record (single record per user+content)
            $progress = ContentUserProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'course_content_id' => $request->course_content_id,
                ],
                [
                    'exam_id' => null,
                    'watch_time' => 0,
                    'completed' => false,
                ]
            );

            // Mark content as viewed
            $progress->viewed_at = now();

            // If content is a video, set watch_time to full video duration for completion
            if ($courseContent->isVideo() && $courseContent->video_duration) {
                $progress->watch_time = $courseContent->video_duration;
            } else {
                // For non-video content, set a flag to indicate it was read/viewed
                // Using watch_time = 1 as indicator that content was consumed
                if (! $progress->watch_time) {
                    $progress->watch_time = 1;
                }
            }

            // Check if this lesson has a linked exam
            $linkedExam = $courseContent->exams()->where('is_active', 1)->first();

            // Check if exam is completed (exam_id is set in the progress record)
            $isExamCompleted = $linkedExam && $progress->exam_id == $linkedExam->id;

            // Calculate lesson completion status
            // If lesson has exam: lesson is complete only when BOTH content AND exam are completed
            // If no exam: lesson is complete when content is completed
            $contentCompleted = true; // Content is now marked as completed
            $lessonCompleted = true;
            $lessonProgress = 100;

            if ($linkedExam) {
                // Lesson is complete only if both content AND exam are done
                $lessonCompleted = $isExamCompleted;

                // Calculate lesson progress: 50% content + 50% exam
                $lessonProgress = 50; // Content is done (50%)
                if ($isExamCompleted) {
                    $lessonProgress += 50;
                }
            }

            // Update the completed flag based on full lesson completion
            $progress->completed = $lessonCompleted;
            $progress->save();

            // Get updated course progress
            $courseProgress = $courseContent->course->calculateCourseProgress($user->id);

            return $this->success_response('Content marked as completed', [
                'progress' => [
                    'id' => $progress->id,
                    'course_content_id' => $progress->course_content_id,
                    'completed' => $progress->completed,
                    'viewed_at' => $progress->viewed_at,
                ],
                'content_completed' => $contentCompleted,
                'exam_completed' => $isExamCompleted,
                'lesson_completed' => $lessonCompleted,
                'lesson_progress' => $lessonProgress,
                'has_exam' => $linkedExam !== null,
                'exam_id' => $linkedExam?->id,
                'course_progress' => $courseProgress,
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to mark content as completed: '.$e->getMessage(), null);
        }
    }

    /**
     * Get detailed progress for a course (videos and exams breakdown)
     */
    public function getCourseDetailedProgress(Request $request, $courseId)
    {
        try {
            $user = $request->user();

            if (! $user) {
                return $this->error_response('Unauthorized', null, 401);
            }

            $course = Course::with(['contents', 'exams'])->findOrFail($courseId);

            // Check if user is enrolled
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();

            if (! $isEnrolled) {
                return $this->error_response('You are not enrolled in this course', null, 403);
            }

            // Get all progress records for this course's contents
            // Note: A single record can have both course_content_id AND exam_id
            // when user completes both video and linked exam
            $allProgress = ContentUserProgress::where('user_id', $user->id)
                ->whereIn('course_content_id', $course->contents->pluck('id'))
                ->get();

            // Build video progress from all records that have course_content_id
            $videoProgress = $allProgress->map(function ($progress) {
                $content = CourseContent::find($progress->course_content_id);
                $videoDuration = $content?->video_duration;

                // Video completion status from the progress record
                return [
                    'id' => $progress->id,
                    'content_id' => $progress->course_content_id,
                    'watch_time' => $progress->watch_time,
                    'lesson_completed' => $progress->completed,
                    'viewed_at' => $progress->viewed_at,
                ];
            })->values();

            // Build exam progress from records that have exam_id
            $examProgress = $allProgress->whereNotNull('exam_id')->map(function ($progress) {
                return [
                    'id' => $progress->id,
                    'content_id' => $progress->course_content_id,
                    'exam_id' => $progress->exam_id,
                    'exam_attempt_id' => $progress->exam_attempt_id,
                    'exam_completed' => true, // If exam_id is set, exam was completed
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
            return $this->error_response('Failed to retrieve detailed progress: '.$e->getMessage(), null);
        }
    }

    /**
     * Get progress summary for all enrolled courses
     */
    public function getAllCoursesProgress(Request $request)
    {
        try {
            $user = $request->user();

            if (! $user) {
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
            return $this->error_response('Failed to retrieve all courses progress: '.$e->getMessage(), null);
        }
    }
}
