<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentUserProgress extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'completed' => 'boolean',
        'video_completed' => 'boolean',
        'is_passed' => 'boolean',
        'viewed_at' => 'datetime',
        'watch_time' => 'integer',
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with course content
     */
    public function courseContent()
    {
        return $this->belongsTo(CourseContent::class);
    }

    /**
     * Relationship with exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Relationship with exam attempt
     */
    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    /**
     * Scope for video progress only
     */
    public function scopeVideoProgress($query)
    {
        return $query->whereNotNull('course_content_id')->whereNull('exam_id');
    }

    /**
     * Scope for exam progress only
     */
    public function scopeExamProgress($query)
    {
        return $query->whereNotNull('exam_id')->whereNull('course_content_id');
    }

    /**
     * Check if this is a video progress record
     */
    public function isVideoProgress()
    {
        return $this->course_content_id !== null && $this->exam_id === null;
    }

    /**
     * Check if this is an exam progress record
     */
    public function isExamProgress()
    {
        return $this->exam_id !== null && $this->course_content_id === null;
    }
}