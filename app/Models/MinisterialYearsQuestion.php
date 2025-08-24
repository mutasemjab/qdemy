<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinisterialYearsQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];
    const BUNNY_PATH = 'ministerial_years_questions';

     public function category()
    {
        return $this->belongsTo(Category::class);
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
        if (!$this->attributes['category']) {
            return __('messages.unknown_category');
        }

        return $this->attributes['category']->breadcrumb;
    }
}
