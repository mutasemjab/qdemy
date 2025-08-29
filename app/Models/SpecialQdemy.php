<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialQdemy extends Model
{
    use HasFactory;
    
     protected $fillable = [
        'title_ar',
        'title_en',
    ];

    /**
     * Get the title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }
}
