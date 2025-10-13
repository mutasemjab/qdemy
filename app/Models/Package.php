<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends Model
{
    use HasFactory,LogsActivity;

     protected $guarded = [];

     protected static function boot()
    {
        parent::boot();

        // When package is deleted, delete the pivot records too
        static::deleting(function ($package) {
            $package->packageCategories()->delete();
        });
    }

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']) // Log all attributes, or specify: ['name', 'price', 'number_of_cards']
            ->logOnlyDirty() // Only log changed attributes
            ->dontSubmitEmptyLogs()
            ->useLogName('Package') // Custom log name
            ->setDescriptionForEvent(fn(string $eventName) => "Package has been {$eventName}");
    }

    /**
     * Relationship with package_categories pivot table
     */
    public function packageCategories()
    {
        return $this->hasMany(PackageCategory::class);
    }

    /**
     * Many-to-many relationship with categories through package_categories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'package_categories')
                   ->withTimestamps()
                   ->distinct();
    }

    /**
     * Many-to-many relationship with subjects through package_categories
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'package_categories')
                    ->withTimestamps()
                    ->distinct();
    }
    /**
     * Get all category-subject combinations for this package
     */
    public function getCategorySubjectCombinations()
    {
        return $this->packageCategories()
                   ->with(['category', 'subject'])
                   ->get();
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 3) . ' ' . __('messages.Currency');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('assets/admin/uploads/' . $this->image);
        }
        return asset('assets/admin/images/placeholder.jpg'); // Default placeholder
    }

    /**
     * Get status badge class for Bootstrap
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Get type badge class for Bootstrap
     */
    public function getTypeBadgeClassAttribute()
    {
        return match($this->type) {
            'class' => 'bg-primary',
            'subject' => 'bg-info',
            default => 'bg-secondary'
        };
    }

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive packages
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for packages by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for packages with specific categories
     */
    public function scopeWithCategories($query, array $categoryIds)
    {
        return $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        });
    }

    /**
     * Scope for packages with specific subjects
     */
    public function scopeWithSubjects($query, array $subjectIds)
    {
        return $query->whereHas('subjects', function ($q) use ($subjectIds) {
            $q->whereIn('subjects.id', $subjectIds);
        });
    }



    /**
     * Check if package has specific category
     */
    public function hasCategory($categoryId)
    {
        return $this->categories()->where('categories.id', $categoryId)->exists();
    }

    /**
     * Check if package has specific subject
     */
    public function hasSubject($subjectId)
    {
        return $this->subjects()->where('subjects.id', $subjectId)->exists();
    }


    /**
     * Create package with categories and subjects
     */
    public function attachCategorySubjectCombinations(array $categories, array $subjects)
    {
        foreach ($categories as $categoryId) {
            foreach ($subjects as $subjectId) {
                $this->packageCategories()->create([
                    'category_id' => $categoryId,
                    'subject_id' => $subjectId,
                ]);
            }
        }
    }

    /**
     * Update package categories and subjects
     */
    public function syncCategorySubjectCombinations(array $categories, array $subjects)
    {
        // Delete existing combinations
        $this->packageCategories()->delete();

        // Create new combinations
        $this->attachCategorySubjectCombinations($categories, $subjects);
    }
}
