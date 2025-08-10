<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;

    protected $guarded = [];

      protected $casts = [
        'is_free' => 'integer',
        'order' => 'integer',
        'video_duration' => 'integer',
    ];

    /**
     * Get the course that owns the content.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the section that owns the content.
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    /**
     * Get content title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('assets/admin/uploads/' . $this->file_path) : null;
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->video_duration) {
            return null;
        }
        
        return gmdate('H:i:s', $this->video_duration);
    }

    /**
     * Check if content is video
     */
    public function isVideo()
    {
        return $this->content_type === 'video';
    }

    /**
     * Check if content is PDF
     */
    public function isPdf()
    {
        return $this->content_type === 'pdf';
    }

    /**
     * Check if content is quiz
     */
    public function isQuiz()
    {
        return $this->content_type === 'quiz';
    }

    /**
     * Check if content is assignment
     */
    public function isAssignment()
    {
        return $this->content_type === 'assignment';
    }

    

}
