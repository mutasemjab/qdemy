<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ContentUserProgress;
use App\Models\CourseContent;
use App\Services\LessonCompletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoProgressController extends Controller
{
    protected LessonCompletionService $completionService;

    public function __construct(LessonCompletionService $completionService)
    {
        $this->completionService = $completionService;
    }
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
        if ($content->course->is_sequential && ! $this->isPreviousContentCompleted($user->id, $content)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.must_complete_previous_lesson'),
            ], 403);
        }

        // If client signals manual complete, honour it by setting watch_time to full duration
        $watchTime = ($completed && $content->video_duration > $request->watch_time)
            ? $content->video_duration
            : $request->watch_time;

        // Check if user is enrolled in the course
        $isEnrolled = $this->checkUserEnrollment($user->id, $content->course_id);
        if (! $isEnrolled && $content->is_free != 1) {
            return response()->json(['success' => false, 'message' => 'Not enrolled']);
        }

        // Get or create progress record
        $progress = ContentUserProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_content_id' => $contentId],
            ['watch_time' => 0, 'completed' => false]
        );

        // Keep the maximum watch_time reached
        $progress->watch_time = max($progress->watch_time, $watchTime);
        $progress->viewed_at = now();

        // Completion determined exclusively by the service
        $lessonCompleted = $this->completionService->isLessonCompleted($progress, $content, $user->id);
        $progress->setCompletedFlag($lessonCompleted);
        $progress->save();

        $courseProgress = $this->calculateCourseProgress($user->id, $content->course_id);

        return response()->json([
            'success' => true,
            'completed' => $this->completionService->isVideoWatched($progress, $content),
            'progress' => $courseProgress,
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
        if ($content->course->is_sequential && ! $this->isPreviousContentCompleted($user->id, $content)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.must_complete_previous_lesson'),
            ], 403);
        }

        // Check if user is enrolled
        $isEnrolled = $this->checkUserEnrollment($user->id, $content->course_id);
        if (! $isEnrolled && $content->is_free != 1) {
            return response()->json(['success' => false, 'message' => 'Not enrolled']);
        }

        // Get or create progress record
        $progress = ContentUserProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_content_id' => $contentId],
            ['watch_time' => 0, 'completed' => false]
        );

        // Set watch_time to full duration
        $progress->watch_time = $content->video_duration ?: 0;
        $progress->viewed_at = now();

        // Completion determined exclusively by the service
        $lessonCompleted = $this->completionService->isLessonCompleted($progress, $content, $user->id);
        $progress->setCompletedFlag($lessonCompleted);
        $progress->save();

        $courseProgress = $this->calculateCourseProgress($user->id, $content->course_id);

        return response()->json([
            'success' => true,
            'progress' => $courseProgress,
        ]);
    }

    private function isPreviousContentCompleted($userId, CourseContent $currentContent)
    {
        $previousContent = CourseContent::where('course_id', $currentContent->course_id)
            ->where('order', '<', $currentContent->order)
            ->orderBy('order', 'desc')
            ->first();

        if (! $previousContent) {
            return true;
        }

        $previousProgress = ContentUserProgress::where('user_id', $userId)
            ->where('course_content_id', $previousContent->id)
            ->first();

        if (! $previousProgress) {
            return false;
        }

        return $this->completionService->isLessonCompleted($previousProgress, $previousContent, $userId);
    }

    private function checkUserEnrollment($userId, $courseId)
    {
        // Add your enrollment check logic here
        return true;
    }

    private function calculateCourseProgress($userId, $courseId)
    {
        $totalVideos = CourseContent::where('course_id', $courseId)
            ->where('content_type', 'video')
            ->count();

        if ($totalVideos == 0) {
            return [
                'course_progress' => 0,
                'completed_videos' => 0,
                'watching_videos' => 0,
                'total_videos' => 0,
            ];
        }

        // Videos completed when watch_time >= 90% of video_duration
        $completedVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->whereRaw('content_user_progress.watch_time >= course_contents.video_duration * 0.9')
            ->count();

        // Videos in progress: watch_time > 0 but below the completion threshold
        $watchingVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->whereRaw('content_user_progress.watch_time < course_contents.video_duration * 0.9')
            ->where('content_user_progress.watch_time', '>', 0)
            ->count();

        $courseProgress = ($completedVideos / $totalVideos) * 100;

        return [
            'course_progress' => round($courseProgress, 2),
            'completed_videos' => $completedVideos,
            'watching_videos' => $watchingVideos,
            'total_videos' => $totalVideos,
        ];
    }
}
