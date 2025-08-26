<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;
    CONST BUNNY_PATH = 'courses_contents';

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
        return app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en'];
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        return  $this->attributes['file_path'] ? env('BUNNY_PREVIEW_DOMAIN') . $this->attributes['file_path'] : null;
    }
    public function getFilePathAttribute()
    {
        return  $this->attributes['file_path'] ? env('BUNNY_PREVIEW_DOMAIN') . $this->attributes['file_path'] : null;
    }

    /**
     * Get video URL in bunny if video_type == 'bunny'
    */
    public function getVideoUrlAttribute()
    {
        return  $this->attributes['video_type'] == 'bunny' ? env('BUNNY_PREVIEW_DOMAIN') . $this->attributes['video_url'] : $this->attributes['video_url'];
    }

    public function content_user_progress()
    {
        return $this->hasOne(ContentUserProgress::class)->where('user_id',auth('user')->user()?->id);
    }
    public function getIsCompletedAttribute()
    {
        return  $this->content_user_progress?->completed;
    }
    public function getWatchedTimeAttribute()
    {
        return  $this->content_user_progress?->watch_time;
    }

    public function getLastWatchedTimeAttribute()
    {
        $video_duration = $this->video_duration;
        $watch_time     = $this->content_user_progress?->watch_time;

        if($watch_time && $video_duration && $watch_time > $video_duration) return ($watch_time % $video_duration) / $video_duration;
        return $watch_time;
    }


    /**
     * Get original video URL
    */
    public function getOriginalVideoUrlAttribute()
    {
        return  $this->attributes['video_url'];
    }


    /**
     * Get original file path
    */
    public function getOriginalFilePathAttribute()
    {
        return  $this->attributes['file_path'];
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
