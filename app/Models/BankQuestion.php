<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankQuestion extends Model
{
    use HasFactory;

     protected $guarded = [];

     public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the PDF URL
     */
    public function getPdfUrlAttribute()
    {
        return $this->pdf ? asset('assets/admin/uploads/pdfs/' . $this->pdf) : null;
    }

    /**
     * Get the PDF path
     */
    public function getPdfPathAttribute()
    {
        return $this->pdf ? base_path('assets/admin/uploads/pdfs/' . $this->pdf) : null;
    }

    /**
     * Check if PDF exists
     */
    public function pdfExists()
    {
        return $this->pdf && file_exists($this->pdf_path);
    }

    /**
     * Get PDF file size in human readable format
     */
    public function getPdfSizeAttribute()
    {
        if (!$this->pdfExists()) {
            return null;
        }

        $size = filesize($this->pdf_path);
        
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    /**
     * Get display name based on category
     */
    public function getDisplayNameAttribute()
    {
        if (!$this->category) {
            return __('messages.unknown_category') . ' - ' . $this->created_at->format('Y-m-d');
        }

        $categoryName = $this->category->localized_name;
        
        // If category has parent, show full path
        if ($this->category->parent) {
            $categoryName = $this->category->parent->localized_name . ' > ' . $categoryName;
        }
        
        return $categoryName . ' - ' . $this->created_at->format('Y-m-d');
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
}
