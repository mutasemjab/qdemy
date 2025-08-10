<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;
    protected $guarded = [];
     protected $casts = [
        'is_correct' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the question that owns the option.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get option text based on current locale
     */
    public function getOptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->option_ar : $this->option_en;
    }

    /**
     * Get option letter (A, B, C, D)
     */
    public function getLetterAttribute()
    {
        return chr(64 + $this->order); // A=65, B=66, etc.
    }
}
