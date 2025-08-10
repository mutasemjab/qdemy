<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    use HasFactory;
      protected $guarded = [];

      
    protected $casts = [
        'selected_options' => 'array',
        'is_correct' => 'boolean',
        'score' => 'decimal:2',
        'answered_at' => 'datetime',
    ];

    /**
     * Get the exam attempt for this answer.
     */
    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    /**
     * Get the question for this answer.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get selected option objects
     */
    public function getSelectedOptionObjectsAttribute()
    {
        if (!$this->selected_options) {
            return collect();
        }
        
        return QuestionOption::whereIn('id', $this->selected_options)->get();
    }
}
