<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    use HasFactory;

    protected $table = 'package_categories';
     protected $guarded = [];

      protected $casts = [
        'package_id' => 'integer',
        'category_id' => 'integer',
        'subject_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relationship with Category
     */
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
     * Scope for specific package
     */
    public function scopeForPackage($query, $packageId)
    {
        return $query->where('package_id', $packageId);
    }

    /**
     * Scope for specific category
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for specific subject
     */
    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Get combination display name
     */
    public function getCombinationNameAttribute()
    {
        $categoryName = $this->category ? $this->category->name_ar : 'Unknown Category';
        $subjectName = $this->subject ? $this->subject->name_ar : 'Unknown Subject';
        
        return $categoryName . ' - ' . $subjectName;
    }

    /**
     * Check if this combination already exists for a package
     */
    public static function combinationExists($packageId, $categoryId, $subjectId)
    {
        return self::where('package_id', $packageId)
                  ->where('category_id', $categoryId)
                  ->where('subject_id', $subjectId)
                  ->exists();
    }

    /**
     * Get statistics for package categories
     */
    public static function getStatistics()
    {
        return [
            'total_combinations' => self::count(),
            'unique_packages' => self::distinct('package_id')->count('package_id'),
            'unique_categories' => self::distinct('category_id')->count('category_id'),
            'unique_subjects' => self::distinct('subject_id')->count('subject_id'),
        ];
    }

    /**
     * Get combinations for a specific package with details
     */
    public static function getPackageCombinations($packageId)
    {
        return self::where('package_id', $packageId)
                  ->with(['category.parent', 'subject'])
                  ->get();
    }

    /**
     * Bulk create combinations for a package
     */
    public static function createCombinations($packageId, array $categories, array $subjects)
    {
        $combinations = [];
        
        foreach ($categories as $categoryId) {
            foreach ($subjects as $subjectId) {
                // Check if combination doesn't already exist
                if (!self::combinationExists($packageId, $categoryId, $subjectId)) {
                    $combinations[] = [
                        'package_id' => $packageId,
                        'category_id' => $categoryId,
                        'subject_id' => $subjectId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        if (!empty($combinations)) {
            self::insert($combinations);
        }
        
        return count($combinations);
    }

    /**
     * Delete all combinations for a package
     */
    public static function deletePackageCombinations($packageId)
    {
        return self::where('package_id', $packageId)->delete();
    }
}
