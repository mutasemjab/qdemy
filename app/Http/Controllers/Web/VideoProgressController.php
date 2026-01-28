<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ContentUserProgress;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoProgressController extends Controller
{
    public function updateVideoProgress(Request $request)
    {
        $user = auth_student();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'no signed in user']);
        }
        $request->validate([
            'content_id' => 'required|exists:course_contents,id',
            'watch_time' => 'required|integer|min:0',
            'completed'  => 'boolean'
        ]);

        $user = Auth::user();
        $contentId = $request->content_id;
        $completed = $request->boolean('completed');

        // Get the content
        $content = CourseContent::find($contentId);
        if (!$content) {
            return response()->json(['success' => false, 'message' => 'Content not found']);
        }

        // Check sequential course restriction
        if ($content->course->is_sequential && !$this->isPreviousContentCompleted($user->id, $content)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.must_complete_previous_lesson')
            ], 403);
        }

        // لان الفيديو قد يسجل كمكتمل قبل انتهاء مدته حسب ال .env.COMPLETED_WATCHING_COURSES
        $watchTime  = ($completed && $content->video_duration > $request->watch_time) ? $content->video_duration : $request->watch_time;

        // Check if user is enrolled in the course
        $isEnrolled = $this->checkUserEnrollment($user->id, $content->course_id);
        if (!$isEnrolled && $content->is_free != 1) {
            return response()->json(['success' => false, 'message' => 'Not enrolled']);
        }

        // Check if this lesson has an exam linked to it
        $hasLinkedExam = $content->exams()->where('is_active', 1)->exists();

        // Get existing progress to check exam completion
        $existingProgress = ContentUserProgress::where('user_id', $user->id)
            ->where('course_content_id', $contentId)
            ->first();

        // Determine if lesson is complete:
        // - If lesson has exam: complete only if BOTH video AND exam are done
        // - If lesson has no exam: complete when video is done
        $lessonCompleted = $completed;
        if ($hasLinkedExam && $completed) {
            // Check if exam was already completed
            $isExamCompleted = $existingProgress && $existingProgress->exam_id !== null;
            $lessonCompleted = $isExamCompleted; // Only complete if exam is also done
        }

        // Update or create progress record
        $progress = ContentUserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_content_id' => $contentId
            ],
            [
                'watch_time' => $watchTime,
                'completed' => $lessonCompleted, // Lesson completion (considers exam if linked)
                'viewed_at' => now()
            ]
        );

        // Calculate course progress
        $courseProgress = $this->calculateCourseProgress($user->id, $content->course_id);

        return response()->json([
            'success' => true,
            'completed' => $completed,
            'progress' => $courseProgress
        ]);
    }

    public function markVideoComplete(Request $request)
    {
        $user = auth_student();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'no signed in user']);
        }
        $request->validate([
            'content_id' => 'required|exists:course_contents,id'
        ]);

        $user = Auth::user();
        $contentId = $request->content_id;

        // Get the content
        $content = CourseContent::find($contentId);
        if (!$content) {
            return response()->json(['success' => false, 'message' => 'Content not found']);
        }

        // Check sequential course restriction
        if ($content->course->is_sequential && !$this->isPreviousContentCompleted($user->id, $content)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.must_complete_previous_lesson')
            ], 403);
        }

        // Check if user is enrolled
        $isEnrolled = $this->checkUserEnrollment($user->id, $content->course_id);
        if (!$isEnrolled && $content->is_free != 1) {
            return response()->json(['success' => false, 'message' => 'Not enrolled']);
        }

        // Check if this lesson has an exam linked to it
        $hasLinkedExam = $content->exams()->where('is_active', 1)->exists();

        // Get existing progress to check exam completion
        $existingProgress = ContentUserProgress::where('user_id', $user->id)
            ->where('course_content_id', $contentId)
            ->first();

        // Determine if lesson is complete:
        // - If lesson has exam: complete only if BOTH video AND exam are done
        // - If lesson has no exam: complete when video is done
        $lessonCompleted = true;
        if ($hasLinkedExam) {
            // Check if exam was already completed
            $isExamCompleted = $existingProgress && $existingProgress->exam_id !== null;
            $lessonCompleted = $isExamCompleted; // Only complete if exam is also done
        }

        // Mark as complete
        $progress = ContentUserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_content_id' => $contentId
            ],
            [
                'watch_time' => $content->video_duration ?: 0,
                'completed' => $lessonCompleted, // Lesson completion (considers exam if linked)
                'viewed_at' => now()
            ]
        );

        // Calculate course progress
        $courseProgress = $this->calculateCourseProgress($user->id, $content->course_id);

        return response()->json([
            'success' => true,
            'progress' => $courseProgress
        ]);
    }

    private function isPreviousContentCompleted($userId, CourseContent $currentContent)
    {
        // Get previous content based on order
        $previousContent = CourseContent::where('course_id', $currentContent->course_id)
            ->where('order', '<', $currentContent->order)
            ->orderBy('order', 'desc')
            ->first();

        // If there's no previous content, current content is allowed (it's the first one)
        if (!$previousContent) {
            return true;
        }

        // Check if previous content is completed
        return ContentUserProgress::where('user_id', $userId)
            ->where('course_content_id', $previousContent->id)
            ->where('completed', true)
            ->exists();
    }

    private function checkUserEnrollment($userId, $courseId)
    {
        // Add your enrollment check logic here
        return true;
    }

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
}
