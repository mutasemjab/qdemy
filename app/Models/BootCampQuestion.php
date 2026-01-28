<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BootCampQuestion extends Model
{
    use HasFactory;

     CONST BUNNY_PATH = 'boot_camp_questions';
     protected $guarded = [];

     public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship with Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the PDF path
     */
    public function getPdfPathAttribute()
    {
        return $this->attributes['pdf'] ? (env('BUNNY_PREVIEW_DOMAIN') . $this->attributes['pdf']) : null;
    }

    /**
     * Check if PDF exists
     */
    public function pdfExists()
    {
        return $this->pdf && BunnyHelper()->exists($this->pdf);
    }

    /**
     * Get category breadcrumb
     */
    public function getCategoryBreadcrumbAttribute()
    {
        if (!$this->category) {
            return __('messages.unknown_category');
        }

        return $this->category->breadcrumb;
    }

    /**
     * Get localized category name
     */
    public function getCategoryNameAttribute()
    {
        if (!$this->category) {
            return null;
        }

        return app()->getLocale() === 'ar'
            ? $this->category->name_ar
            : ($this->category->name_en ?? $this->category->name_ar);
    }

    /**
     * Get localized subject name
     */
    public function getSubjectNameAttribute()
    {
        if (!$this->subject) {
            return null;
        }

        return app()->getLocale() === 'ar'
            ? $this->subject->name_ar
            : ($this->subject->name_en ?? $this->subject->name_ar);
    }

    /**
     * Format file size for display
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->pdf_size) {
            return null;
        }

        // If pdf_size is already formatted (like "2.5 MB"), return as is
        if (preg_match('/\d+\.?\d*\s*(KB|MB|GB)/i', $this->pdf_size)) {
            return $this->pdf_size;
        }

        // If it's a number (bytes), format it
        if (is_numeric($this->pdf_size)) {
            $bytes = (int) $this->pdf_size;

            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' bytes';
            }
        }

        return $this->pdf_size;
    }

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    /**
     * Scope for filtering by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        if ($subjectId) {
            return $query->where('subject_id', $subjectId);
        }
        return $query;
    }

    /**
     * Scope for search functionality
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('display_name', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name_ar', 'like', "%{$search}%")
                               ->orWhere('name_en', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subject', function($subQuery) use ($search) {
                      $subQuery->where('name_ar', 'like', "%{$search}%")
                               ->orWhere('name_en', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
    }
}
