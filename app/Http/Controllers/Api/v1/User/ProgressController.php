<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\ContentUserProgress;
use App\Models\Course;
use App\Models\CourseContent;
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

            return $this->success_response('Course progress retrieved successfully', $progress);

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
                'video_completed' => 'boolean',
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
            // Priority: 1) explicit video_completed flag from request (most reliable)
            //           2) completed flag from request (backward compatibility)
            //           3) watch_time threshold (for Bunny videos)
            $videoCompleted = $request->has('video_completed')
                ? $request->video_completed
                : ($request->has('completed') 
                    ? $request->completed 
                    : ($courseContent->video_duration && $request->watch_time >= $courseContent->video_duration * 0.9));

           

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
                    'video_completed' => false,
                ]
            );

            

            // Update video-related fields (preserve exam data if present)
            $progress->watch_time = $request->watch_time;
            $progress->video_completed = $videoCompleted; // NEW: track video completion separately
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
                'video_completed' => $videoCompleted,
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
                    'video_completed' => false,
                ]
            );

            // Mark content as viewed
            $progress->viewed_at = now();
            // For non-video content, set a flag to indicate it was read/viewed
            // Using watch_time = 1 as indicator that content was consumed
            if (! $progress->watch_time) {
                $progress->watch_time = 1;
            }
            
            // Mark video as completed
            $progress->video_completed = true;

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

                // Video is completed if:
                // 1. video_completed flag is true (NEW - most reliable), OR
                // 2. watch_time reached 90% of video duration (FALLBACK)
                $isVideoCompleted = false;
                
                // Primary: use video_completed flag
                if ($progress->video_completed) {
                    $isVideoCompleted = true;
                } elseif ($videoDuration && $progress->watch_time) {
                    // Fallback: check if 90% watched
                    $isVideoCompleted = $progress->watch_time >= $videoDuration * 0.9;
                } elseif ($progress->watch_time > 0 && !$videoDuration) {
                    // Non-video content (PDF, etc.) or YouTube without duration
                    $isVideoCompleted = true;
                }

                return [
                    'id' => $progress->id,
                    'content_id' => $progress->course_content_id,
                    'watch_time' => $progress->watch_time,
                    'video_completed' => $isVideoCompleted,
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
