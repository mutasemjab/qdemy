<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'selling_price' => 'decimal:2',
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
        if(!$user_id) return null;
        return $this->hasOne(CourseUser::class)->where('user_id',$user_id);
    }


    public function enrollments()
    {
        return $this->hasMany(CourseUser::class, 'course_id');
    }
    
    // Get user progress for this course
    public function calculateCourseProgress($userId = null)
    {
        $user_id = $userId ? $userId : auth_student()?->id;
        
        if (!$user_id) {
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
        
        // Get total videos for this course
        $totalVideos = CourseContent::where('course_id', $this->id)
            ->where('content_type', 'video')
            ->count();
        
        // Get completed videos
        $completedVideos = 0;
        $watchingVideos = 0;
        
        if ($totalVideos > 0) {
            $completedVideos = ContentUserProgress::where('user_id', $user_id)
                ->whereIn('course_content_id', 
                    CourseContent::where('course_id', $this->id)
                        ->where('content_type', 'video')
                        ->pluck('id')
                )
                ->whereNull('exam_id') // Only video progress
                ->where('completed', true)
                ->count();
            
            $watchingVideos = ContentUserProgress::where('user_id', $user_id)
                ->whereIn('course_content_id', 
                    CourseContent::where('course_id', $this->id)
                        ->where('content_type', 'video')
                        ->pluck('id')
                )
                ->whereNull('exam_id')
                ->where('completed', false)
                ->where('watch_time', '>', 0)
                ->count();
        }
        
        $videoProgress = $totalVideos > 0 ? ($completedVideos / $totalVideos) * 100 : 0;
        
        // Get total exams for this course
        $totalExams = $this->exams()->where('is_active', 1)->count();
        
        // Get completed exams
        $completedExams = 0;
        
        if ($totalExams > 0) {
            $completedExams = ContentUserProgress::where('user_id', $user_id)
                ->whereIn('exam_id', $this->exams()->pluck('id'))
                ->whereNull('course_content_id') // Only exam progress
                ->where('completed', true)
                ->count();
        }
        
        $examProgress = $totalExams > 0 ? ($completedExams / $totalExams) * 100 : 0;
        
        // Calculate total progress (weighted average)
        // If course has both videos and exams, give 60% weight to videos, 40% to exams
        // If only videos or only exams, use 100% of that component
        $totalProgress = 0;
        if ($totalVideos > 0 && $totalExams > 0) {
            $totalProgress = ($videoProgress * 0.6) + ($examProgress * 0.4);
        } elseif ($totalVideos > 0) {
            $totalProgress = $videoProgress;
        } elseif ($totalExams > 0) {
            $totalProgress = $examProgress;
        }
        
        return [
            'total_progress' => round($totalProgress, 2),
            'video_progress' => round($videoProgress, 2),
            'exam_progress' => round($examProgress, 2),
            'completed_videos' => $completedVideos,
            'watching_videos' => $watchingVideos,
            'total_videos' => $totalVideos,
            'completed_exams' => $completedExams,
            'total_exams' => $totalExams,
        ];
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
        return $this->photo ? asset('assets/admin/uploads/' . $this->photo) : asset('assets_front/images/course-image.jpg');
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
        return $query->whereDoesntHave('subject',function ($query) {
            $query->where('is_active',0);
        });
    }

    public function scopeNotActive($query)
    {
        return $query->whereDoesntHave('subject',function ($query) {
            $query->where('is_active',1);
        });
    }

}
