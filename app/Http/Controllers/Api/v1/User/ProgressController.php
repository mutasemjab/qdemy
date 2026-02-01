<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\ContentUserProgress;
use App\Models\Course;
use App\Models\CourseContent;
use App\Repositories\CourseRepository;
use App\Services\LessonCompletionService;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    use Responses;

    protected LessonCompletionService $completionService;

    public function __construct(LessonCompletionService $completionService)
    {
        $this->completionService = $completionService;
    }

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
            // completed=2 from mobile means user clicked "mark as completed" → set full duration
            $manualComplete = $request->has('completed') && $request->completed == 2;

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

            // Update watch_time: manual complete sets full duration, otherwise keep maximum
            if ($manualComplete && $courseContent->video_duration) {
                $progress->watch_time = $courseContent->video_duration;
            } else {
                $progress->watch_time = max($progress->watch_time, $request->watch_time);
            }
            $progress->viewed_at = now();

            // Completion is determined exclusively by the service — never set manually
            $linkedExam = $this->completionService->getLinkedExam($courseContent);
            $videoCompleted = $this->completionService->isVideoWatched($progress, $courseContent);
            $lessonCompleted = $this->completionService->isLessonCompleted($progress, $courseContent, $user->id);

            $progress->setCompletedFlag($lessonCompleted);
            $progress->save();

            $hasExamAttempt = $linkedExam
                ? $this->completionService->hasExamAttempt($linkedExam, $user->id)
                : false;

            // Calculate lesson progress
            $lessonProgress = $videoCompleted ? 100 : 0;
            if ($linkedExam) {
                $lessonProgress = 0;
                if ($videoCompleted) {
                    $lessonProgress += 50;
                }
                if ($hasExamAttempt) {
                    $lessonProgress += 50;
                }
            }

            // Get updated course progress
            $courseProgress = $courseContent->course->calculateCourseProgress($user->id);

            return $this->success_response('Video progress updated successfully', [
                'progress' => [
                    'id' => $progress->id,
                    'course_content_id' => $progress->course_content_id,
                    'watch_time' => $progress->watch_time,
                    'video_duration' => $courseContent->video_duration,
                    'viewed_at' => $progress->viewed_at,
                ],
                'video_completed' => $videoCompleted,
                'exam_completed' => $hasExamAttempt,
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

            $progress->viewed_at = now();

            if ($courseContent->isVideo() && $courseContent->video_duration) {
                // Video: set watch_time to full duration, let the service resolve completion
                $progress->watch_time = $courseContent->video_duration;
                $lessonCompleted = $this->completionService->isLessonCompleted($progress, $courseContent, $user->id);
                $progress->setCompletedFlag($lessonCompleted);
            } else {
                // Non-video content: mark as viewed/completed directly
                $progress->setCompletedFlag(true);
                if (! $progress->watch_time) {
                    $progress->watch_time = 1;
                }
            }

            $progress->save();

            // Derive all completion states from the service
            $linkedExam = $this->completionService->getLinkedExam($courseContent);

            $isContentCompleted = $courseContent->content_type === 'video'
                ? $this->completionService->isVideoWatched($progress, $courseContent)
                : (bool) $progress->completed;

            $hasExamAttempt = $linkedExam
                ? $this->completionService->hasExamAttempt($linkedExam, $user->id)
                : false;

            $lessonCompleted = $this->completionService->isLessonCompleted($progress, $courseContent, $user->id);

            // Calculate lesson progress
            $lessonProgress = $isContentCompleted ? 100 : 0;
            if ($linkedExam) {
                $lessonProgress = 0;
                if ($isContentCompleted) {
                    $lessonProgress += 50;
                }
                if ($hasExamAttempt) {
                    $lessonProgress += 50;
                }
            }

            // Get updated course progress
            $courseProgress = $courseContent->course->calculateCourseProgress($user->id);

            return $this->success_response('Content marked as completed', [
                'progress' => [
                    'id' => $progress->id,
                    'course_content_id' => $progress->course_content_id,
                    'watch_time' => $progress->watch_time,
                    'completed' => $progress->completed,
                    'viewed_at' => $progress->viewed_at,
                ],
                'content_completed' => $isContentCompleted,
                'exam_completed' => $hasExamAttempt,
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
            $videoProgress = $allProgress->map(function ($progress) use ($user) {
                $content = CourseContent::find($progress->course_content_id);
                if (! $content) {
                    return null;
                }

                $linkedExam = $this->completionService->getLinkedExam($content);
                $hasExam = $linkedExam !== null;

                $isContentCompleted = $content->content_type === 'video'
                    ? $this->completionService->isVideoWatched($progress, $content)
                    : (bool) $progress->completed;

                $hasExamAttempt = $linkedExam
                    ? $this->completionService->hasExamAttempt($linkedExam, $user->id)
                    : false;

                $lessonCompleted = $this->completionService->isLessonCompleted($progress, $content, $user->id);

                $lessonProgress = $isContentCompleted ? 100 : 0;
                if ($hasExam) {
                    $lessonProgress = 0;
                    if ($isContentCompleted) {
                        $lessonProgress += 50;
                    }
                    if ($hasExamAttempt) {
                        $lessonProgress += 50;
                    }
                }

                return [
                    'id' => $progress->id,
                    'content_id' => $progress->course_content_id,
                    'watch_time' => $progress->watch_time,
                    'video_duration' => $content->video_duration,
                    'completed' => $lessonCompleted,
                    'content_completed' => $isContentCompleted,
                    'has_exam' => $hasExam,
                    'exam_id' => $linkedExam?->id,
                    'exam_completed' => $hasExamAttempt,
                    'lesson_completed' => $lessonCompleted,
                    'lesson_progress' => $lessonProgress,
                    'viewed_at' => $progress->viewed_at,
                ];
            })->filter()->values();

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
