<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use App\Models\Subject;
use App\Traits\Responses;
use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Repositories\CourseRepository;
use App\Repositories\SubjectRepository;

class PackageAndOfferController extends Controller
{
    use Responses;

    /**
     * Display active packages with their categories
     * @param Request $request
     * @param Category $programm = category->id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Category $programm = null)
    {
        try {
            // categories where type = major (main programmes)
            $programms = CategoryRepository()->getMajors();

            // get all sub childs for passed category
            $programmChilds = null;
            if ($programm) {
                $programmChilds = CategoryRepository()->getAllSubChilds($programm?->id, true, true)->pluck('id')->toArray();
            }

            // جلب الباكدجات النشطة مع الكاتيجوريز المرتبطة بها
            // في حالة تمرير programm تاتي بالبكدجات المرتبطة به وباحفاده فقط
            $packages = Package::where('status', 'active')
                ->where(function ($query) use ($programm, $programmChilds) {
                    $query->where('type', 'class');
                    $query->whereHas('categories', function ($query) use ($programm, $programmChilds) {
                        if ($programm) {
                            $query->whereIn('categories.id', $programmChilds);
                        }
                        $query->select('packages.id', 'categories.id', 'categories.name_ar', 'categories.name_en', 'categories.type');
                    });
                })
                ->orWhere(function ($query) use ($programmChilds) {
                    $query->where('type', 'subject');
                    $query->whereHas('subjects', function ($query) use ($programmChilds) {
                        if ($programmChilds && count($programmChilds)) {
                            $query->whereHas('program', function ($query) use ($programmChilds) {
                                $query->whereIn('categories.id', $programmChilds);
                            });
                            $query->orWhereHas('grade', function ($query) use ($programmChilds) {
                                $query->whereIn('categories.id', $programmChilds);
                            });
                            $query->orWhereHas('semester', function ($query) use ($programmChilds) {
                                $query->whereIn('categories.id', $programmChilds);
                            });
                        }
                    });
                })
                ->with(['categories' => function ($query) {
                    $query->with('parent');
                }, 'subjects'])
                ->get();

            // Format packages for API response
            $formattedPackages = $packages->map(function ($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => (float) $package->price,
                    'formatted_price' => sprintf('%g', $package->price),
                    'type' => $package->type,
                    'status' => $package->status,
                    'how_much_course_can_select' => $package->how_much_course_can_select,
                    'categories' => $package->categories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name_ar' => $category->name_ar,
                            'name_en' => $category->name_en,
                            'localized_name' => $category->localized_name,
                            'type' => $category->type,
                            'parent' => $category->parent ? [
                                'id' => $category->parent->id,
                                'name_ar' => $category->parent->name_ar,
                                'name_en' => $category->parent->name_en,
                                'localized_name' => $category->parent->localized_name,
                            ] : null
                        ];
                    }),
                    'subjects' => $package->subjects->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name_ar' => $subject->name_ar ?? '',
                            'name_en' => $subject->name_en ?? '',
                            'localized_name' => $subject->localized_name ?? '',
                        ];
                    })
                ];
            });


            return $this->success_response('تم جلب الباكدجات بنجاح', [
                'packages' => $formattedPackages,
            ]);
        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الباكدجات: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get package details with courses and subjects
     * @param Request $request
     * @param Package $package
     * @param Category $clas
     * @return \Illuminate\Http\JsonResponse
     */
  public function show(Request $request, Package $package, Category $clas = null)
    {
        try {
            $is_type_class = ($package->type == 'class');
            
            // Build complete nested structure with parent hierarchy
            $structure = collect();

            if ($is_type_class) {
                // Get package categories from package_categories pivot table
                $packageCategories = $package->categories()->with('parent')->get();
                
                // Group categories by their root parent to avoid duplicates
                $rootGroups = collect();
                
                foreach ($packageCategories as $category) {
                    $rootCategory = $this->findRootCategory($category);
                    
                    // Check if we already have this root category
                    if (!$rootGroups->contains('id', $rootCategory->id)) {
                        $categoryTree = $this->buildFullCategoryTree($rootCategory, $packageCategories, $clas);
                        $rootGroups->push($categoryTree);
                    }
                }
                
                $structure = $rootGroups;
            } else {
                // For subject type packages - get subjects through package_categories
                $packageSubjects = $package->subjects()->where('is_active', true)
                    ->with(['program.parent', 'grade.parent', 'semester.parent'])->get();
                
                // Group subjects by their root program
                $subjectsByRoot = $packageSubjects->groupBy(function($subject) {
                    $mainCategory = $subject->program ?? $subject->grade ?? $subject->semester;
                    if ($mainCategory) {
                        $rootCategory = $this->findRootCategory($mainCategory);
                        return $rootCategory->id;
                    }
                    return 'no_program';
                });

                foreach ($subjectsByRoot as $rootId => $subjects) {
                    if ($rootId !== 'no_program') {
                        $firstSubject = $subjects->first();
                        $mainCategory = $firstSubject->program ?? $firstSubject->grade ?? $firstSubject->semester;
                        $rootCategory = $this->findRootCategory($mainCategory);
                        
                        $structure->push([
                            'id' => $rootCategory->id,
                            'name_ar' => $rootCategory->name_ar,
                            'name_en' => $rootCategory->name_en,
                            'localized_name' => $rootCategory->localized_name,
                            'type' => $rootCategory->type,
                            'children' => collect(),
                            'subjects' => $this->formatSubjectsWithFullCourses($subjects)
                        ]);
                    }
                }
            }

            return $this->success_response('تم جلب تفاصيل الباقة بنجاح', [
                'package' => [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => (float) $package->price,
                    'formatted_price' => sprintf('%g', $package->price),
                    'type' => $package->type,
                    'how_much_course_can_select' => $package->how_much_course_can_select,
                ],
                'is_type_class' => $is_type_class,
                'structure' => $structure->values(),
                'selected_class' => $clas ? [
                    'id' => $clas->id,
                    'name_ar' => $clas->name_ar,
                    'name_en' => $clas->name_en,
                    'localized_name' => $clas->localized_name,
                ] : null
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب تفاصيل الباقة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Find the root (top-level) category
     */
    private function findRootCategory($category)
    {
        $current = $category;
        while ($current && $current->parent) {
            $current = $current->parent;
        }
        return $current;
    }

    /**
     * Build full category tree with all children recursively
     */
 /**
 * Build full category tree with all children recursively
 */
private function buildFullCategoryTree($rootCategory, $packageCategories, $selectedClass = null)
{
    $node = [
        'id' => $rootCategory->id,
        'name_ar' => $rootCategory->name_ar,
        'name_en' => $rootCategory->name_en,
        'localized_name' => $rootCategory->localized_name,
        'type' => $rootCategory->type,
        'children' => collect(),
        'subjects' => collect()
    ];

    // Check if this category itself is in the package
    $isPackageCategory = $packageCategories->contains('id', $rootCategory->id);

    // Get ALL direct children of this category
    $allChildren = Category::where('parent_id', $rootCategory->id)->get();
    
    if ($allChildren->count() > 0) {
        // If this category is in the package, show ALL children
        // Otherwise, only show children that lead to package categories
        if ($isPackageCategory) {
            $validChildren = $allChildren;
        } else {
            $validChildren = $allChildren->filter(function($child) use ($packageCategories) {
                return $this->hasPackageCategoryInHierarchy($child, $packageCategories);
            });
        }

        // Build children recursively
        if ($validChildren->count() > 0) {
            $node['children'] = $validChildren->map(function($child) use ($packageCategories, $selectedClass, $isPackageCategory) {
                // Pass down whether we're inside a package category
                return $this->buildChildCategoryNode($child, $packageCategories, $selectedClass, $isPackageCategory);
            })->values();
        }
    } else {
        // This is a leaf node - check if it's in package categories and add subjects
        if ($isPackageCategory) {
            $subjects = $this->getSubjectsForCategory($rootCategory, $selectedClass);
            $node['subjects'] = $this->formatSubjectsWithFullCourses($subjects);
        }
    }

    return $node;
}


/**
 * Build child category node recursively
 */
private function buildChildCategoryNode($category, $packageCategories, $selectedClass = null, $parentIsInPackage = false)
{
    $node = [
        'id' => $category->id,
        'name_ar' => $category->name_ar,
        'name_en' => $category->name_en,
        'localized_name' => $category->localized_name,
        'type' => $category->type,
        'children' => collect(),
        'subjects' => collect()
    ];

    // Check if this category itself is in the package
    $isPackageCategory = $packageCategories->contains('id', $category->id);
    
    // This category should be treated as "in package" if it or any parent is in package
    $shouldShowContent = $isPackageCategory || $parentIsInPackage;

    // Get direct children
    $children = Category::where('parent_id', $category->id)->get();
    
    if ($children->count() > 0) {
        // If this category or parent is in the package, show ALL children
        // Otherwise, only show children that lead to package categories
        if ($shouldShowContent) {
            $validChildren = $children;
        } else {
            $validChildren = $children->filter(function($child) use ($packageCategories) {
                return $this->hasPackageCategoryInHierarchy($child, $packageCategories);
            });
        }

        // Has valid children, build them recursively
        if ($validChildren->count() > 0) {
            $node['children'] = $validChildren->map(function($child) use ($packageCategories, $selectedClass, $shouldShowContent) {
                return $this->buildChildCategoryNode($child, $packageCategories, $selectedClass, $shouldShowContent);
            })->values();
        }
    } else {
        // Leaf node - if this or parent is in package, show subjects
        if ($shouldShowContent) {
            $subjects = $this->getSubjectsForCategory($category, $selectedClass);
            $node['subjects'] = $this->formatSubjectsWithFullCourses($subjects);
        }
    }

    return $node;
}

    /**
     * Get subjects connected to a category and its children
     */
    private function getSubjectsForCategory($category, $selectedClass = null)
    {
        $subjects = collect();

        try {
            // Get category IDs to search in
            $categoryIds = [$category->id];
            
            // Try to get children if repository is available
            try {
                $childrenIds = CategoryRepository()->getAllSubChilds($category->id, true, true)->pluck('id')->toArray();
                $categoryIds = array_merge($categoryIds, $childrenIds);
            } catch (\Exception $e) {
                // Continue with just the main category if children lookup fails
            }

            // Filter by selected class if provided
            if ($selectedClass) {
                try {
                    $classChildren = CategoryRepository()->getAllSubChilds($selectedClass->id, true, true)->pluck('id')->toArray();
                    $categoryIds = array_intersect($categoryIds, $classChildren);
                } catch (\Exception $e) {
                    // Continue without class filtering if it fails
                }
            }

            // Get subjects connected to these categories
            $subjects = Subject::where('is_active', true)
                ->where(function($query) use ($categoryIds) {
                    $query->whereIn('programm_id', $categoryIds)
                        ->orWhereIn('grade_id', $categoryIds)
                        ->orWhereIn('semester_id', $categoryIds);
                })
                ->get();

        } catch (\Exception $e) {
            // Return empty collection if there's an error
        }

        return $subjects;
    }

/**
 * Format subjects with their courses including full relations
 */
private function formatSubjectsWithFullCourses($subjects)
{
    if (!$subjects || $subjects->count() == 0) {
        return collect();
    }

    return collect($subjects)->map(function ($subject) {
        $subjectData = [
            'id' => $subject->id,
            'name_ar' => $subject->name_ar ?? '',
            'name_en' => $subject->name_en ?? '',
            'localized_name' => $subject->localized_name ?? '',
            'is_optional' => $subject->is_optional ?? 0,
            'courses' => collect()
        ];

        try {
            // Get courses with full relations
            $courses = CourseRepository()->getDirectCategoryCourses($subject->id);
            if ($courses && is_countable($courses) && count($courses) > 0) {
                $subjectData['courses'] = collect($courses)->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title ?? '',
                        'title_ar' => $course->title_ar ?? '',
                        'title_en' => $course->title_en ?? '',
                        'description' => $course->description ?? '',
                        'description_ar' => $course->description_ar ?? '',
                        'description_en' => $course->description_en ?? '',
                        'duration' => $course->duration ?? '',
                        'level' => $course->level ?? '',
                        'is_active' => $course->is_active ?? true,
                        'price' => isset($course->price) ? (float) $course->price : null,
                        'selling_price' => isset($course->selling_price) ? (float) $course->selling_price : null,
                        'photo' => $course->photo ?? null,
                        'photo_url' => $course->photo_url ?? null,
                        'slug' => $course->slug ?? '',
                        'teacher_id' => $course->teacher_id,
                        'subject_id' => $course->subject_id,
                        'created_at' => $course->created_at,
                        'updated_at' => $course->updated_at,
                        // Add teacher relation if available
                        'teacher' => $course->teacher ? [
                            'id' => $course->teacher->id,
                            'name' => $course->teacher->name,
                            'email' => $course->teacher->email,
                        ] : null,
                    ];
                });
            }
        } catch (\Exception $e) {
            // Leave courses empty if there's an error
            $subjectData['courses'] = collect();
        }

        return $subjectData;
    });
}

/**
 * Check if a category has any package category in its hierarchy (itself or descendants)
 */
private function hasPackageCategoryInHierarchy($category, $packageCategories)
{
    // Check if this category itself is in package categories
    if ($packageCategories->contains('id', $category->id)) {
        return true;
    }

    // Check if any descendant is in package categories
    try {
        $descendants = CategoryRepository()->getAllSubChilds($category->id, true, true);
        if ($descendants && $descendants->count() > 0) {
            foreach ($descendants as $descendant) {
                if ($packageCategories->contains('id', $descendant->id)) {
                    return true;
                }
            }
        }
    } catch (\Exception $e) {
        // If CategoryRepository fails, check direct children manually
        $children = Category::where('parent_id', $category->id)->get();
        foreach ($children as $child) {
            if ($this->hasPackageCategoryInHierarchy($child, $packageCategories)) {
                return true;
            }
        }
    }

    return false;
}

}
