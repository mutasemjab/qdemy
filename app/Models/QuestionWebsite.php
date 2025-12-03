<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionWebsite extends Model
{
    use HasFactory;
    protected $guarded = [];

     public function getQuestionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['question_ar'] : $this->attributes['question_en'];
    }

    public function getAnswerAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['answer_ar'] : $this->attributes['answer_en'];
    }
}
