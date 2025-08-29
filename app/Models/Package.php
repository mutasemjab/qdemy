<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

     protected $guarded = [];

     protected $casts = [
        'price' => 'decimal:3',
        'how_much_course_can_select' => 'integer'
    ];

    /**
     * Get the categories associated with this package
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'package_categories');
    }

    /**
     * Scope to get active packages
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get packages by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->attributes['image'] ? asset('assets/admin/uploads/' . $this->image) : null;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->attributes['price'], 3) . ' ' . __('messages.Currency');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->attributes['status'] === 'active' ? 'badge-success' : 'badge-danger';
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeClassAttribute()
    {
        return $this->attributes['type'] === 'lesson' ? 'badge-success' : 'badge-info';
    }
}
