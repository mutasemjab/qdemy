<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'selling_price' => 'decimal:2',
    ];
    
     public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the category that owns the course.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
        return $this->photo ? asset('assets/admin/uploads/' . $this->photo) : asset('images/default-course.jpg');
    }


}
