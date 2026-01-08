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
    // - For each lesson with exam linked: lesson_progress = (video_completed ? 50 : 0) + (exam_completed ? 50 : 0)
    // - For each lesson without exam: lesson_progress = (video_completed ? 100 : 0)
    // - Total course progress = average of all lessons
    public function calculateCourseProgress($userId = null)
    {
        $user_id = $userId ? $userId : auth_student()?->id;

        \Log::info('calculateCourseProgress START', [
            'course_id' => $this->id,
            'user_id' => $user_id,
        ]);

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

        // Get all video contents for this course
        $allVideos = CourseContent::where('course_id', $this->id)
            ->where('content_type', 'video')
            ->with('exams') // Load related exams
            ->get();

        $totalVideos = $allVideos->count();
        $completedVideos = 0;
        $watchingVideos = 0;
        $lessonsProgress = [];

        \Log::info('Total videos found', [
            'total_videos' => $totalVideos,
        ]);

        foreach ($allVideos as $video) {
            \Log::info('Processing video', [
                'video_id' => $video->id,
                'video_title_ar' => $video->title_ar,
            ]);

            // Check if this video/lesson has an exam linked to it
            $linkedExam = $video->exams()->where('is_active', 1)->first();

            // Get content progress for this lesson
            $contentProgress = ContentUserProgress::where('user_id', $user_id)
                ->where('course_content_id', $video->id)
                ->first();

            \Log::info('Content progress data', [
                'has_progress_record' => $contentProgress !== null,
                'watch_time' => $contentProgress?->watch_time,
                'completed' => $contentProgress?->completed,
                'video_duration' => $video->video_duration,
            ]);

            // Video is completed if:
            // 1. completed flag is true (PRIMARY indicator - works for YouTube and Bunny videos), OR
            // 2. watch_time reached 90% of video duration (SECONDARY indicator - for Bunny videos with tracking)
            $isVideoCompleted = false;
            if ($contentProgress) {
                // Primary indicator: completed flag
                if ($contentProgress->completed) {
                    $isVideoCompleted = true;
                    \Log::info('Video/content marked as completed (completed flag is true)', [
                        'watch_time' => $contentProgress->watch_time,
                        'video_duration' => $video->video_duration,
                    ]);
                } elseif ($video->video_duration && $contentProgress->watch_time) {
                    // Secondary: check if 90% watched (for Bunny videos with watch_time tracking)
                    $isVideoCompleted = $contentProgress->watch_time >= $video->video_duration * 0.9;
                    \Log::info('Video completion check (90% rule)', [
                        'watch_time' => $contentProgress->watch_time,
                        'threshold' => $video->video_duration * 0.9,
                        'isVideoCompleted' => $isVideoCompleted,
                    ]);
                }
            }

            if ($isVideoCompleted) {
                $completedVideos++;
            } elseif ($contentProgress && $contentProgress->watch_time > 0 && ! $isVideoCompleted) {
                $watchingVideos++;
            }

            // Calculate lesson progress
            if ($linkedExam) {
                // Lesson has exam: 50% video + 50% exam
                $lessonProgress = 0;

                // Add 50% if video is completed
                if ($isVideoCompleted) {
                    $lessonProgress += 50;
                }

                // Add 50% if exam is completed
                // Check if exam was taken by looking for exam_id in progress record
                // We check the content progress record which now contains exam_id after exam submission
                $isExamCompleted = $contentProgress && $contentProgress->exam_id == $linkedExam->id;

                if ($isExamCompleted) {
                    $lessonProgress += 50;
                }

                \Log::info('Lesson with exam progress', [
                    'exam_id' => $linkedExam->id,
                    'isVideoCompleted' => $isVideoCompleted,
                    'isExamCompleted' => $isExamCompleted,
                    'lessonProgress' => $lessonProgress,
                ]);

                $lessonsProgress[] = $lessonProgress;
            } else {
                // Lesson has NO exam: 100% video
                if ($isVideoCompleted) {
                    \Log::info('Lesson without exam - COMPLETED', [
                        'video_id' => $video->id,
                        'lessonProgress' => 100,
                    ]);
                    $lessonsProgress[] = 100;
                } else {
                    \Log::info('Lesson without exam - NOT COMPLETED', [
                        'video_id' => $video->id,
                        'lessonProgress' => 0,
                    ]);
                    $lessonsProgress[] = 0;
                }
            }
        }

        // Calculate overall course progress (average of all lessons)
        $totalProgress = count($lessonsProgress) > 0 ? array_sum($lessonsProgress) / count($lessonsProgress) : 0;

        // Get video progress percentage
        $videoProgress = $totalVideos > 0 ? ($completedVideos / $totalVideos) * 100 : 0;

        // Get total active exams for this course
        $totalExams = Exam::where('course_id', $this->id)
            ->where('is_active', 1)
            ->whereNotNull('course_content_id') // Only exams linked to lessons
            ->count();

        // Get completed exams
        $completedExams = 0;
        if ($totalExams > 0) {
            // Count progress records that have exam_id set (meaning exam was taken)
            $completedExams = ContentUserProgress::where('user_id', $user_id)
                ->whereIn('exam_id', Exam::where('course_id', $this->id)
                    ->where('is_active', 1)
                    ->whereNotNull('course_content_id')
                    ->pluck('id'))
                ->whereNotNull('exam_id')
                ->count();
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

        \Log::info('calculateCourseProgress END', [
            'lessons_progress' => $lessonsProgress,
            'total_progress' => round($totalProgress, 2),
            'video_progress' => round($videoProgress, 2),
            'completed_videos' => $completedVideos,
            'total_videos' => $totalVideos,
        ]);

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
