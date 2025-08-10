<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $guarded = [];
     protected $casts = [
        'total_grade' => 'decimal:2',
        'passing_grade' => 'decimal:2',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'show_results_immediately' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the course that owns the exam.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who created the exam.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the questions for the exam.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
                    ->withPivot('order', 'grade')
                    ->orderBy('exam_questions.order')
                    ->withTimestamps();
    }

    /**
     * Get exam attempts.
     */
    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /**
     * Get exam title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get exam description based on current locale
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Check if exam is available now
     */
    public function isAvailable()
    {
        $now = Carbon::now();
        
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }
        
        return true;
    }

    /**
     * Calculate total grade from questions
     */
    public function calculateTotalGrade()
    {
        return $this->questions()->sum('exam_questions.grade');
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return __('messages.unlimited');
        }
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d', $hours, $minutes);
        }
        
        return sprintf('%d %s', $minutes, __('messages.minutes'));
    }
}
