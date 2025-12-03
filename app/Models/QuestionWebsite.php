<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionWebsite extends Model
{
    use HasFactory;
    protected $guarded = [];

     protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Get question based on current locale
    public function getQuestionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->question_ar : $this->question_en;
    }

    // Get answer based on current locale
    public function getAnswerAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->answer_ar : $this->answer_en;
    }

    // Scope to filter by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope to search
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('question_en', 'like', "%{$search}%")
              ->orWhere('question_ar', 'like', "%{$search}%")
              ->orWhere('answer_en', 'like', "%{$search}%")
              ->orWhere('answer_ar', 'like', "%{$search}%");
        });
    }
}
