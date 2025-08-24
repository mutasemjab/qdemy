<?php

namespace App\Http\Controllers\User;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use Illuminate\Support\Facades\DB;
use App\Models\ContentUserProgress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VideoProgressController extends Controller
{
    public function updateVideoProgress(Request $request)
    {
        $user = auth('user')->user();
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

        // لان الفيديو قد يسجل كمكتمل قبل انتهاء مدته حسب ال .env.COMPLETED_WATCHING_COURSES
        $watchTime  = ($completed && $content->video_duration > $request->watch_time) ? $content->video_duration : $request->watch_time;

        // اذا
        // $$completed = ($completed && $content->video_duration > $request->watch_time) ? $content->video_duration : $request->watch_time;

        // Check if user is enrolled in the course
        $isEnrolled = $this->checkUserEnrollment($user->id, $content->course_id);
        if (!$isEnrolled && $content->is_free != 1) {
            return response()->json(['success' => false, 'message' => 'Not enrolled']);
        }

        // $content = ContentUserProgress::where('user_id',$user->id)->where('course_content_id',$contentId)->first();
        // if (!$content->) {
        //     return response()->json(['success' => false, 'message' => 'Content not found']);
        // }
        // Update or create progress record
        $progress = ContentUserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_content_id' => $contentId
            ],
            [
                'watch_time' => $watchTime,
                'completed' => $completed,
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
        $user = auth('user')->user();
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

        // Check if user is enrolled
        $isEnrolled = $this->checkUserEnrollment($user->id, $content->course_id);
        if (!$isEnrolled && $content->is_free != 1) {
            return response()->json(['success' => false, 'message' => 'Not enrolled']);
        }

        // Mark as complete
        $progress = ContentUserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_content_id' => $contentId
            ],
            [
                'watch_time' => $content->video_duration ?: 0,
                'completed' => true,
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

    private function checkUserEnrollment($userId, $courseId)
    {
        // Add your enrollment check logic here
        // This should check if user is enrolled in the course
        // For now, returning true - replace with actual logic
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
