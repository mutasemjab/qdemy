<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;
    const BUNNY_PATH = 'courses_contents';

    protected $guarded = [];

    protected $casts = [
        'is_free' => 'integer',
        'is_main_video' => 'integer',
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
     * Get file URL with token authentication
     */
    public function getFileUrlAttribute()
    {
        if (!$this->attributes['file_path']) {
            return null;
        }

        $url = env('BUNNY_PREVIEW_DOMAIN') . $this->attributes['file_path'];
        
        // Add token authentication if security key is configured
        if (env('BUNNY_TOKEN_KEY')) {
            return $this->generateSignedUrl($url);
        }

        return $url;
    }

    public function getFilePathAttribute()
    {
        return $this->attributes['file_path'] ? env('BUNNY_PREVIEW_DOMAIN') . $this->attributes['file_path'] : null;
    }

    /**
     * Get video URL in bunny if video_type == 'bunny' with token authentication
     */
    public function getVideoUrlAttribute()
    {
        if ($this->attributes['video_type'] == 'bunny') {
            $url = env('BUNNY_PREVIEW_DOMAIN') . $this->attributes['video_url'];
            
            // Add token authentication if security key is configured
            if (env('BUNNY_TOKEN_KEY')) {
                return $this->generateSignedUrl($url);
            }
            
            return $url;
        }
        
        return $this->attributes['video_url'];
    }

    /**
     * Generate signed URL for Bunny CDN token authentication
     * 
     * @param string $url The full URL to sign
     * @param int $expirationTime Expiration time in seconds (default: 1 hour)
     * @return string Signed URL with token
     */
    private function generateSignedUrl($url, $expirationTime = 3600)
    {
        $securityKey = env('BUNNY_TOKEN_KEY');
        
        if (!$securityKey) {
            return $url;
        }

        $expires = time() + $expirationTime;
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        
        // Create the signature base
        $hashableBase = $securityKey . $path . $expires;
        
        // Generate token
        $token = base64_encode(md5($hashableBase, true));
        $token = strtr($token, '+/', '-_');
        $token = str_replace('=', '', $token);
        
        // Append token and expiration to URL
        $separator = strpos($url, '?') === false ? '?' : '&';
        return $url . $separator . 'token=' . $token . '&expires=' . $expires;
    }

    public function content_user_progress()
    {
        return $this->hasOne(ContentUserProgress::class)->where('user_id', auth_student()?->id);
    }

    public function getIsCompletedAttribute()
    {
        return $this->content_user_progress?->completed;
    }

    public function getWatchedTimeAttribute()
    {
        return $this->content_user_progress?->watch_time;
    }

    public function getLastWatchedTimeAttribute()
    {
        $video_duration = $this->video_duration;
        $watch_time = $this->content_user_progress?->watch_time;

        if ($watch_time && $video_duration && $watch_time > $video_duration) {
            return ($watch_time % $video_duration) / $video_duration;
        }
        return $watch_time;
    }

    /**
     * Get original video URL (without token)
     */
    public function getOriginalVideoUrlAttribute()
    {
        return $this->attributes['video_url'];
    }

    /**
     * Get original file path (without domain)
     */
    public function getOriginalFilePathAttribute()
    {
        return $this->attributes['file_path'];
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