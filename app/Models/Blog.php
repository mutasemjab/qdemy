<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $guarded = [];

      public function getTitleAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en'];
    }

    /**
     * Get the description based on current locale
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->attributes['description_ar'] : $this->attributes['description_en'];
    }

}
