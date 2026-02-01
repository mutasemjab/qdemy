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
        'is_passed' => 'boolean',
        'viewed_at' => 'datetime',
        'watch_time' => 'integer',
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    /** @var bool Guards against direct writes to the completed flag on persisted records. */
    protected bool $completionGuardEnabled = true;

    /**
     * Intercept attribute writes to prevent direct modification of the completed
     * flag on persisted records. Completion for video content must be resolved
     * exclusively through LessonCompletionService::updateCompletionStatus().
     *
     * New (not-yet-persisted) records are exempt so that firstOrCreate / create
     * defaults work without special handling.
     */
    public function setAttribute($key, $value)
    {
        if ($key === 'completed' && $this->completionGuardEnabled && $this->exists) {
            throw new \InvalidArgumentException(
                'The completed flag cannot be set directly on a persisted record. '
                . 'Use LessonCompletionService::updateCompletionStatus() for video content '
                . 'or ContentUserProgress::setCompletedFlag() for non-video content.'
            );
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Bypass the completion guard and write the flag.
     *
     * Called by LessonCompletionService (video content) or by controllers
     * handling explicit non-video completion.  No other code path should
     * reach the completed column on an existing record.
     */
    public function setCompletedFlag(bool $value): void
    {
        $this->completionGuardEnabled = false;
        $this->setAttribute('completed', $value);
        $this->completionGuardEnabled = true;
    }

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