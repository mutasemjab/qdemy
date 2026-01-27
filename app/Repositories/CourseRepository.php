<?php
namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseContent;
use App\Models\CourseUser;
use App\Models\ContentUserProgress;

class CourseRepository
{
    public $model;
    public function __construct()
    {
        $this->model = new Course;
    }

    // query direct courses under courses
    // @param categoryId = Category.id
    // return collection
    public function getDirectCategoryCourses($subjectId)
    {
        $courses = [];
        $courses = Course::where('subject_id',$subjectId)->get();
        return $courses;
    }


    // user courses section

    // query enrollend courses for currnt login user
    public function userCourses($userId)
    {
        if(!$userId) return null;
        $coursesIds = CourseUser::where('user_id',$userId)->pluck('course_id')->toArray();
        if($coursesIds && count($coursesIds)) return Course::whereIn('id',$coursesIds);
        return null;
    }

    // query->get() enrollend courses for currnt login user
    public function getUserCourses($userId)
    {
        if(!$userId) return null;
        return $this->userCourses($userId)->get();
    }

    // query->get() enrollend courses ids for currnt login user
    public function getUserCoursesIds($userId)
    {
        if(!$userId) return [];
        return $this->userCourses($userId)?->pluck('id')->toArray() ?? [];
    }

    // Get total video duration for a course
    public function getTotalVideoHours($courseId)
    {
        $totalSeconds = CourseContent::where('course_id', $courseId)
            ->where('content_type', 'video')
            ->sum('video_duration');

        // Convert seconds to formatted time (H:i:s)
        $formattedDuration = gmdate('H:i:s', (int)$totalSeconds);

        return [
            'total_seconds' => (int)$totalSeconds,
            'formatted_duration' => $formattedDuration
        ];
    }

    // Get total watch time for a user
    // Always use actual watch_time - the actual time the user watched
    public function getUserWatchTime($courseId, $userId)
    {
        $allProgress = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->select('content_user_progress.watch_time')
            ->get();

        $totalWatchSeconds = 0;
        foreach ($allProgress as $progress) {
            // Always use actual watch_time - what the user actually watched
            $totalWatchSeconds += (int)$progress->watch_time;
        }

        // Convert seconds to formatted time (H:i:s)
        $formattedDuration = gmdate('H:i:s', $totalWatchSeconds);

        return [
            'total_seconds' => (int)$totalWatchSeconds,
            'formatted_duration' => $formattedDuration
        ];
    }

}
