<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];
      protected $casts = [
        'grade' => 'decimal:2',
    ];

    /**
     * Get the course that owns the question.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who created the question.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the options for the question.
     */
    public function options()
    {
        // dd($this->hasMany(QuestionOption::class)->orderBy('order')->first());
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }
    public function getShuffledOptions()
    {
        return $this->options()->inRandomOrder()->get();
    }
    public function getCorrectOptions()
    {
        return $this->options()->where('is_correct', true)->get();
    }

    /**
     * Get the correct options.
     */
    public function correctOptions()
    {
        return $this->hasMany(QuestionOption::class)->where('is_correct', true);
    }

    /**
     * Get the exams that include this question.
     */
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')
                    ->withPivot('order', 'grade')
                    ->withTimestamps();
    }

    /**
     * Get question title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en'];
    }

    /**
     * Get question text based on current locale
     */
    public function getQuestionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['question_ar'] : $this->attributes['question_en'];
    }

    /**
     * Get explanation based on current locale
     */
    public function getExplanationAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['explanation_ar'] : $this->attributes['explanation_en'];
    }

    /**
     * Check if question is multiple choice
     */
    public function isMultipleChoice()
    {
        return $this->type === 'multiple_choice';
    }

    /**
     * Check if question is true/false
     */
    public function isTrueFalse()
    {
        return $this->type === 'true_false';
    }

    /**
     * Check if question is essay
     */
    public function isEssay()
    {
        return $this->type === 'essay';
    }
}
