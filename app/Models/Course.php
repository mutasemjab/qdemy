<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'selling_price' => 'decimal:2',
    ];
       /**
     * Get content title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en'];
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['description_ar'] : $this->attributes['description_en'];
    }

    /**
     * Get the teacher (user) that owns the course
     * teacher_id refers to users.id where role_name = 'teacher'
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id')->where('role_name', 'teacher');
    }

    /**
     * Get the subject that owns the course
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exams()
    {
        return $this->HasMany(Exam::class);
    }

    /**
     * Get the sections for the course.
     */
    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    /**
     * Get the contents for the course.
     */
    public function contents()
    {
        return $this->hasMany(CourseContent::class);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('assets/admin/uploads/' . $this->photo) : asset('assets_front/images/course-image.jpg');
    }

    public function getSlugAttribute()
    {
        return Str::slug(app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en']);
    }


}
