<?php

namespace App\Observers;

use App\Models\Course;
use App\Services\FollowerNotificationService;

class CourseObserver
{
    public function created(Course $course)
    {
        // Only notify if course is active and has a teacher
        if ($course->teacher_id && $course->is_active) {
            FollowerNotificationService::notifyNewCourse($course);
        }
    }
}