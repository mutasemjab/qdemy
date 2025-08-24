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

    public function getSelectedOptionsAttribute()
    {
        return json_decode($this->attributes['selected_options']);
    }

    // Relationships
    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }


    // Methods
    public function getSelectedOptionsModels()
    {
        if (!$this->selected_options) {
            return collect();
        }
        return QuestionOption::whereIn('id', $this->selected_options)->get();
    }

    public function getAnswerDisplayAttribute()
    {
        if ($this->question->type === 'essay') {
            return $this->essay_answer;
        }

        if ($this->question->type === 'true_false') {
            return isset($this->selected_options[0])
                ? ($this->selected_options[0] ? 'صحيح' : 'خطأ')
                : 'لم يتم الإجابة';
        }

        if ($this->question->type === 'multiple_choice') {
            $options = $this->getSelectedOptionsModels();
            return $options->pluck('option')->join(', ');
        }

        return 'لم يتم الإجابة';
    }
}
