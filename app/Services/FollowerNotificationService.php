<?php

namespace App\Services;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FollowerNotificationService
{
    /**
     * Notify all followers of a teacher about new content
     */
    public static function notifyFollowers($teacherId, $title, $message, $screen = 'notification')
    {
        // Get all followers of this teacher
        $followers = User::whereHas('following', function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->whereNotNull('fcm_token')
        ->where('activate', 1)
        ->get();

        $successCount = 0;
        $failureCount = 0;

        foreach ($followers as $follower) {
            $result = AdminFCMController::sendMessage(
                $title,
                $message,
                $follower->fcm_token,
                $follower->id,
                $screen
            );

            if ($result) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        Log::info("Follower notifications sent for teacher ID $teacherId: $successCount successful, $failureCount failed");
        
        return [
            'success' => $successCount,
            'failure' => $failureCount,
            'total' => $followers->count()
        ];
    }

    /**
     * Notify followers about new course
     */
    public static function notifyNewCourse($course)
    {
        $teacherName = $course->teacher->name ?? 'Teacher';
        $title = __('New Course Available!');
        $message = __(':teacher has published a new course: :course', [
            'teacher' => $teacherName,
            'course' => $course->title
        ]);

        return self::notifyFollowers($course->teacher_id, $title, $message, 'course');
    }

    /**
     * Notify followers about new exam
     */
    public static function notifyNewExam($exam)
    {
        $teacherName = $exam->creator->name ?? 'Teacher';
        $title = __('New Exam Available!');
        $message = __(':teacher has created a new exam: :exam', [
            'teacher' => $teacherName,
            'exam' => $exam->title
        ]);

        return self::notifyFollowers($exam->created_by, $title, $message, 'exam');
    }

    /**
     * Notify followers about new doseyat
     */
    public static function notifyNewDoseyat($doseyat)
    {
        $teacherName = $doseyat->teacher->name ?? 'Teacher';
        $title = __('New Doseyat Available!');
        $message = __(':teacher has added new doseyat: :name', [
            'teacher' => $teacherName,
            'name' => $doseyat->name ?? 'New Doseyat'
        ]);

        // Get teacher user_id from teacher model
        $teacherUserId = $doseyat->teacher->user_id;
        
        return self::notifyFollowers($teacherUserId, $title, $message, 'doseyat');
    }

    /**
     * Notify followers about new post
     */
    public static function notifyNewPost($post)
    {
        $userName = $post->user->name ?? 'User';
        $title = __('New Post from :user', ['user' => $userName]);
        $message = substr($post->content ?? '', 0, 100) . '...';

        return self::notifyFollowers($post->user_id, $title, $message, 'post');
    }
}