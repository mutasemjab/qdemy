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
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        $duration = $this->attributes['duration'];

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

   // Methods
    public function passed()
    {
        if ($this->is_passed === null) {
            return '<span class="text-warning">قيد التصحيح</span>';
        }

        return $this->is_passed
            ? '<span class="text-success">نجح</span>'
            : '<span class="text-danger">رسب</span>';
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->submitted_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->submitted_at);
    }

    public function getRemainingTimeAttribute()
    {
        if (!$this->exam->duration_minutes || $this->status !== 'in_progress') {
            return null;
        }

        $elapsed = $this->started_at->diffInMinutes(now());
        $remaining = $this->exam->duration_minutes - $elapsed;

        return max(0, $remaining);
    }

    public function getProgressAttribute()
    {
        $total_questions = count($this->exam?->questions ?? []);
        $answered_questions = $this->answers()->count();

        if ($total_questions == 0) return 0;

        return round(($answered_questions / $total_questions) * 100, 2);
    }
}
