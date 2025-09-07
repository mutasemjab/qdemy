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
    public function isGraded()
    {
        return $this->status === 'completed';
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
        if ($this->status == 'in_progress' && $this->is_passed === null) {
            return '<span class="text-warning">'.__('front.قيد').' التصحيح</span>';
        }elseif ($this->status == 'abandoned') {
            return '<span class="text-warning">'.__('front.متروك').'</span>';
        }elseif($this->status = 'completed'){
            return $this->is_passed
                ? '<span class="text-success">'.__('front.نجح').'</span>'
                : '<span class="text-danger">'.__('front.رسب').'</span>';
        }
    }

    // public function attempt_actions()
    // {
    //     if($this->status === 'in_progress' && !$this->submitted_at){
    //         return "<a href='" . route('exam.show', ['exam' => $this->exam->id, 'slug' => $this->exam->slug]) 
    //         . "class='btn btn-sm btn-primary'> " . translate_lang('continue') . "</a>";
    //     }elseif($this->exam->show_results_immediately && in_array($this->status, ['completed'])){
    //         return "<a href='" . route('review.attempt', ['exam' => $this->exam->id, 'attempt' => $this->id]) 
    //         . "class='btn btn-sm btn-info'> " . translate_lang('review') . "</a>";
    //     }else{
    //         return  "-";
    //     }
    // }

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
