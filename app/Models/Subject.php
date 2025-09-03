<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];



    public function isOptional()
    {
        if($this->is_optional){
            return "<span class='badge bg-success'>".__('messages.optional')."</span>";
        }else{
            return "<span class='badge bg-primary'>".__('messages.non_optional')."</span>";
        }
    }

    public function isMinistry()
    {
        if($this->is_ministry){
            return "<span class='badge bg-success'>".__('messages.Ministry Subject')."</span>";
        }else{
            return "<span class='badge bg-primary'>".__('messages.School Subject')."</span>";
        }
    }

    /**
     * Scope for active categories
    */
    public function scopeActive($query,$withActiveParent = false)
    {
        if($withActiveParent){
            $query->whereDoesntHave('categories', function ($q) use($categoryId){
              $q->where('is_active',0);
            });
        }
        $query->where('is_active', true);
        return $query;
    }
    // Relations
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function fieldType()
    {
        return $this->belongsTo(Category::class, 'field_type_id');
    }
    /**
     * Get the category  porgramm that owns the course.
     */
    public function porgramm()
    {
        return $this->belongsTo(Category::class,'programm_id');
    }
    /**
     * Get the category  grade that owns the course.
     */
    public function grade()
    {
        return $this->belongsTo(Category::class,'grade_id');
    }
    /**
     * Get the category  semester that owns the course - if exists.
     */
    public function semester()
    {
        return $this->belongsTo(Category::class,'semester_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subjects')
            ->withPivot(['pivot_level', 'is_optional', 'is_ministry'])
            ->withTimestamps();
    }

    /**
     * Get the category  main programm that owns the course.
    */
    public function category_subjects()
    {
        return $this->hasMany(CategorySubject::class);
    }

    /**
     * Get the category  main programm that owns the course.
    */
    public function program()
    {
        return $this->belongsTo(Category::class,'programm_id');
    }

    /**
     * Scope to get subjects by grade
    */
    public function scopeByGrade($query, $gradeId = null)
    {
        return $query->where('grade_id', $gradeId);
    }

    /**
     * Scope to get subjects related categories
    */
    public function scopeByCategory($query, $categoryId = null)
    {
        return $query->whereHas('categories', function ($q) use($categoryId){
            $q->where('id','category_id');
        });
    }

    /**
     * Scope to get subjects by main programm
    */
    public function scopeByProgramm($query, $programmId = null)
    {
        return $query->whereHas('categories', function ($q) use($programmId){
            $q->where('id','programm_id');
        });
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_ar');
    }

    /**
     * Get localized name based on app locale
     */
    public function getHasOptionalSubjectAttribute()
    {
        $has = CategorySubject::where('subject_id',$this->id)
        ->where(function ($q) {
            $q->where('subject_id', $this->id);
            $q->where('pivot_level', 'field');
            $q->where('is_optional', true);
            $q->where('is_ministry', true);
        })->exists();
        return $has;
    }

    /**
     * Get localized name based on app locale
     */
    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['name_ar'] : ($this->attributes['name_en'] ?: $this->attributes['name_ar']);
    }

    /**
     * Get localized description based on app locale
     */
    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['description_ar'] : ($this->attributes['description_en'] ?: $this->attributes['description_ar']);
    }

    public function getSlugAttribute()
    {
        return Str::slug(app()->getLocale() === 'ar' ? $this->attributes['name_ar'] : $this->attributes['name_en']);
    }
}
