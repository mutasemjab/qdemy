<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (!empty($category->name_en) && empty($category->ctg_key)) {
                $category->ctg_key = Str::slug($category->name_en);
            }
        });
    }

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
        $breadcrumb[] = $this->attributes['name_ar'];

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
     * if withParent = true - ضع ال parent category علس راس القائمة
     */
    public static function getFlatList($parentId = null, $prefix = '',$withParent = false)
    {
        $categories = collect();

        $items = static::where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();

        if($withParent)    {
            $parentCategory = Category::find($parentId);
            $categories->push([
                'id' => $parentCategory->id,
                'type' => $parentCategory->type,
                'name' => $parentCategory->name_ar,
                'depth' => 0,
                'category' => $parentCategory
            ]);
        }

        foreach ($items as $item) {
            $categories->push([
                'id' => $item->id,
                'type' => $item->type,
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

    public function getSlugAttribute()
    {
        return Str::slug(app()->getLocale() === 'ar' ? $this->attributes['name_ar'] : $this->attributes['name_en']);
    }

     public function bankQuestions()
    {
        return $this->hasMany(BankQuestion::class);
    }
    
}
