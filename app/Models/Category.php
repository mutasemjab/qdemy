<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors (parent, grandparent, etc.)
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors->prepend($parent);
            $parent = $parent->parent;
        }
        
        return $ancestors;
    }

    /**
     * Get the full breadcrumb path
     */
    public function getBreadcrumbAttribute()
    {
        $breadcrumb = $this->ancestors()->pluck('name_ar')->toArray();
        $breadcrumb[] = $this->name_ar;
        
        return implode(' > ', $breadcrumb);
    }

    /**
     * Check if category has children
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get depth level in hierarchy
     */
    public function getDepthAttribute()
    {
        return $this->ancestors()->count();
    }

    /**
     * Scope for root categories (no parent)
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get categories by parent
     */
    public function scopeByParent($query, $parentId = null)
    {
        return $query->where('parent_id', $parentId);
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
    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : ($this->name_en ?: $this->name_ar);
    }

    /**
     * Get localized description based on app locale
     */
    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->description_ar : ($this->description_en ?: $this->description_ar);
    }

    /**
     * Get category tree as nested array
     */
    public static function getTree($parentId = null)
    {
        return static::with('children')
            ->where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name_ar' => $category->name_ar,
                    'name_en' => $category->name_en,
                    'icon' => $category->icon,
                    'color' => $category->color,
                    'depth' => $category->depth,
                    'children' => $category->hasChildren() ? static::getTree($category->id) : []
                ];
            });
    }

    /**
     * Get flattened list of all categories with indentation
     */
    public static function getFlatList($parentId = null, $prefix = '')
    {
        $categories = collect();
        
        $items = static::where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();

        foreach ($items as $item) {
            $categories->push([
                'id' => $item->id,
                'name' => $prefix . $item->name_ar,
                'depth' => substr_count($prefix, '-- '),
                'category' => $item
            ]);

            if ($item->hasChildren()) {
                $categories = $categories->merge(static::getFlatList($item->id, $prefix . '-- '));
            }
        }

        return $categories;
    }


    

}
