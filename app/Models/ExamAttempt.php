<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;
      protected $guarded = [];

      protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_passed' => 'boolean',
        'question_order' => 'array',
    ];

    /**
     * Get the exam for this attempt.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the user who made this attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for this attempt.
     */
    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    /**
     * Calculate duration of attempt
     */
    public function getDurationAttribute()
    {
        if (!$this->submitted_at) {
            return null;
        }
        
        return $this->started_at->diffInMinutes($this->submitted_at);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        $duration = $this->duration;
        
        if (!$duration) {
            return __('messages.in_progress');
        }
        
        $hours = floor($duration / 60);
        $minutes = $duration % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d', $hours, $minutes);
        }
        
        return sprintf('%d %s', $minutes, __('messages.minutes'));
    }

    /**
     * Check if attempt is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if attempt is in progress
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }
}
