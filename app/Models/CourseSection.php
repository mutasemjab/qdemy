<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $guarded = [];

     public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function exams()
    {
        return $this->HasMany(Exam::class,'section_id');
    }
    /**
     * Get the parent section.
     */
    public function parent()
    {
        return $this->belongsTo(CourseSection::class, 'parent_id');
    }

    /**
     * Get the child sections.
     */
    public function children()
    {
        return $this->hasMany(CourseSection::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the contents for the section.
     */
    public function contents()
    {
        return $this->hasMany(CourseContent::class, 'section_id')->orderBy('order');
    }
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en'];
    }
}
