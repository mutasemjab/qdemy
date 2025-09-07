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

    
    // Get user progress for this course
    public function calculateCourseProgress($userId = null)
    {
        $user_id = $userId ? $userId : auth_student()?->id;
        // Get all video contents for the course
        $totalVideos = CourseContent::where('course_id', $this->id)
            ->where('content_type', 'video')
            ->count();
        // Get user progress for this course
        $completedVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $user_id)
            ->where('course_contents.course_id', $this->id)
            ->where('course_contents.content_type', 'video')
            ->where('content_user_progress.completed', true)
            ->count();
        return $courseProgress = ($completedVideos / $totalVideos) * 100;
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
