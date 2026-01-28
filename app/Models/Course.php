<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'is_sequential' => 'boolean',
        'status' => 'string',
    ];

    /**
     * Get content title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en'];
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['description_ar'] : $this->attributes['description_en'];
    }

    /**
     * Get the teacher (user) that owns the course
     * teacher_id refers to users.id where role_name = 'teacher'
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id')->where('role_name', 'teacher');
    }

    /**
     * Get the subject that owns the course
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exams()
    {
        return $this->HasMany(Exam::class);
    }

    /**
     * Get the sections for the course.
     */
    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_users');
    }

    public function course_user()
    {
        $user_id = auth_student()?->id;
        if (! $user_id) {
            return null;
        }

        return $this->hasOne(CourseUser::class)->where('user_id', $user_id);
    }

    public function enrollments()
    {
        return $this->hasMany(CourseUser::class, 'course_id');
    }

    // Get user progress for this course
    // Formula:
    // - For video content: completed if watch_time >= 90% of video_duration
    // - For non-video content (files, etc.): uses completed flag
    // - For lesson with exam: completed if content is done + at least 1 exam attempt exists
    // - For lesson without exam: completed if content is done
    // - Total course progress = average of all lessons
    public function calculateCourseProgress($userId = null)
    {
        $user_id = $userId ? $userId : auth_student()?->id;

        if (! $user_id) {
            return [
                'total_progress' => 0,
                'video_progress' => 0,
                'exam_progress' => 0,
                'completed_videos' => 0,
                'watching_videos' => 0,
                'total_videos' => 0,
                'completed_exams' => 0,
                'total_exams' => 0,
            ];
        }

        // Get all contents for this course
        $allContents = CourseContent::where('course_id', $this->id)
            ->with('exams')
            ->get();

        // Filter videos for video-specific stats
        $allVideos = $allContents->where('content_type', 'video');
        $totalVideos = $allVideos->count();
        $completedVideos = 0;
        $watchingVideos = 0;
        $lessonsProgress = [];

        foreach ($allContents as $content) {

            // Check if this lesson has an exam linked to it
            $linkedExam = $content->exams()->where('is_active', 1)->first();

            // Get content progress for this lesson
            $contentProgress = ContentUserProgress::where('user_id', $user_id)
                ->where('course_content_id', $content->id)
                ->first();

            // Determine if content is completed based on type
            $isContentCompleted = false;
            if ($content->content_type === 'video') {
                // For video: completed if watch_time >= 90% of video_duration
                if ($contentProgress && $content->video_duration) {
                    $isContentCompleted = $contentProgress->watch_time >= $content->video_duration * 0.9;
                }
            } else {
                // For non-video (files, etc.): use completed flag
                $isContentCompleted = $contentProgress && $contentProgress->completed;
            }

            // Track video-specific stats
            if ($content->content_type === 'video') {
                if ($isContentCompleted) {
                    $completedVideos++;
                } elseif ($contentProgress && $contentProgress->watch_time > 0) {
                    $watchingVideos++;
                }
            }

            // Calculate lesson progress
            if ($linkedExam) {
                // Lesson has exam: 50% content + 50% exam
                $lessonProgress = 0;

                // Add 50% if content is completed
                if ($isContentCompleted) {
                    $lessonProgress += 50;
                }

                // Add 50% if exam has at least 1 completed attempt (regardless of pass/fail)
                $hasExamAttempt = ExamAttempt::where('exam_id', $linkedExam->id)
                    ->where('user_id', $user_id)
                    ->where('status', 'completed')
                    ->exists();

                if ($hasExamAttempt) {
                    $lessonProgress += 50;
                }

                $lessonsProgress[] = $lessonProgress;
            } else {
                // Lesson has NO exam: 100% content
                $lessonsProgress[] = $isContentCompleted ? 100 : 0;
            }
        }

        // Calculate overall course progress (average of all lessons)
        $totalProgress = count($lessonsProgress) > 0 ? array_sum($lessonsProgress) / count($lessonsProgress) : 0;

        // Get video progress percentage
        $videoProgress = $totalVideos > 0 ? ($completedVideos / $totalVideos) * 100 : 0;

        // Get total active exams for this course (only exams linked to lessons)
        $totalExams = Exam::where('course_id', $this->id)
            ->where('is_active', 1)
            ->whereNotNull('course_content_id')
            ->count();

        // Get completed exams (exams with at least 1 completed attempt)
        $completedExams = 0;
        if ($totalExams > 0) {
            $linkedExamIds = Exam::where('course_id', $this->id)
                ->where('is_active', 1)
                ->whereNotNull('course_content_id')
                ->pluck('id');

            $completedExams = ExamAttempt::where('user_id', $user_id)
                ->whereIn('exam_id', $linkedExamIds)
                ->where('status', 'completed')
                ->distinct('exam_id')
                ->count('exam_id');
        }

        $examProgress = $totalExams > 0 ? ($completedExams / $totalExams) * 100 : 0;

        $result = [
            'total_progress' => round($totalProgress, 2),
            'video_progress' => round($videoProgress, 2),
            'exam_progress' => round($examProgress, 2),
            'completed_videos' => $completedVideos,
            'watching_videos' => $watchingVideos,
            'total_videos' => $totalVideos,
            'completed_exams' => $completedExams,
            'total_exams' => $totalExams,
        ];

        return $result;
    }

    /**
     * Get the contents for the course.
     */
    public function contents()
    {
        return $this->hasMany(CourseContent::class);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('assets/admin/uploads/'.$this->photo) : asset('assets_front/images/course-image.jpg');
    }

    public function getSlugAttribute()
    {
        return Str::slug(app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en']);
    }

    public function getIsActiveAttribute()
    {
        return $this->subject ? $this->subject?->is_active : true;
    }

    public function getIsEnrolledAttribute()
    {
        $user = auth_student();

        return CourseUser::where('user_id', $user?->id)
            ->where('course_id', $this->id)
            ->exists();
    }

    public function scopeActive($query)
    {
        return $query->whereDoesntHave('subject', function ($query) {
            $query->where('is_active', 0);
        });
    }

    public function scopeNotActive($query)
    {
        return $query->whereDoesntHave('subject', function ($query) {
            $query->where('is_active', 1);
        });
    }
}
