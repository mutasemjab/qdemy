<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankQuestion extends Model
{
    use HasFactory;

     CONST BUNNY_PATH = 'bank_question';
     protected $guarded = [];

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
        if (!$this->category) {
            return __('messages.unknown_category');
        }

        return $this->category->breadcrumb;
    }
}
