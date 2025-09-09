<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use App\Models\Subject;
use App\Traits\Responses;
use Illuminate\Http\Request;

class PackageAndOfferController extends Controller
{
    use Responses;

    /**
     * Display active packages with their categories
     * @param programm = category->id
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
                ->where(function($query) use($programm, $programmChilds) {
                    $query->where('type', 'class');
                    $query->whereHas('categories', function($query) use($programm, $programmChilds) {
                        if ($programm) {
                            $query->whereIn('categories.id', $programmChilds);
                        }
                        $query->select('packages.id', 'categories.id', 'categories.name_ar', 'categories.name_en', 'categories.type');
                    });
                })
                ->orWhere(function($query) use($programmChilds) {
                    $query->where('type', 'subject');
                    $query->whereHas('subjects', function($query) use($programmChilds) {
                        if ($programmChilds && count($programmChilds)) {
                            $query->whereHas('program', function($query) use($programmChilds) {
                                $query->whereIn('categories.id', $programmChilds);
                            });
                            $query->orWhereHas('grade', function($query) use($programmChilds) {
                                $query->whereIn('categories.id', $programmChilds);
                            });
                            $query->orWhereHas('semester', function($query) use($programmChilds) {
                                $query->whereIn('categories.id', $programmChilds);
                            });
                        }
                    });
                })
                ->with(['categories', 'subjects']) // Load relationships for API response
                ->get();

            return $this->success_response('تم جلب الباكدجات بنجاح', [
                'packages' => $packages,
                'programmes' => $programms,
                'current_programme' => $programm,
                'total_packages' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الباكدجات: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get package details with its categories/subjects and courses
     * @param Package $package
     * @param Category $clas
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Package $package, Category $clas = null)
    {
        try {
            $is_type_class = ($package->type == 'class');
            
            // get subjects from request or relation direct if $package->type == 'subject'
            $classes = $package->categories?->where('type', 'class');
            $subjects = [];
            $categoriesTree = collect();

            foreach ($classes as $ctg) {
                $categoriesTree = $categoriesTree->push(Category::getFlatList($ctg->id, '-- ', 'with_parent')->where('type', 'class'));
            }

            if ($is_type_class && $clas?->id) {
                $classChilds = CategoryRepository()->getAllSubChilds($clas->id, true, true)->pluck('id')->toArray();
                $subjects = Subject::where('is_active', true)
                    ->whereIn('programm_id', $classChilds)
                    ->orWhereIn('grade_id', $classChilds)
                    ->orWhereIn('semester_id', $classChilds)
                    ->orWhereHas('categories', function ($query) use($clas) {
                        $query->where('pivot_level', 'field');
                        $query->where('category_id', $clas->id);
                    })
                    ->with(['program', 'grade', 'semester']) // Load relationships
                    ->get();
            } elseif (!$is_type_class) {
                $subjects = $package->subjects()->with(['program', 'grade', 'semester'])->get();
            }

            $subjectsIds = $request->lessonsIds ?? [];

            // Load package with all necessary relationships
            $package->load(['categories', 'subjects', 'courses']);

            return $this->success_response('تم جلب تفاصيل الباكدج بنجاح', [
                'package' => new PackageResource($package),
                'categories_tree' => CategoryResource::collection($categoriesTree->flatten()),
                'classes' => CategoryResource::collection($classes),
                'subjects' => SubjectResource::collection($subjects),
                'current_class' => $clas ? new CategoryResource($clas) : null,
                'is_type_class' => $is_type_class,
                'subjects_ids' => $subjectsIds,
                'subjects_count' => is_array($subjects) ? count($subjects) : $subjects->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب تفاصيل الباكدج: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get packages by category (programme)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCategory(Request $request)
    {
        try {
            $categoryId = $request->input('category_id');
            
            if (!$categoryId) {
                return $this->error_response('معرف الفئة مطلوب', null);
            }

            $category = Category::find($categoryId);
            if (!$category) {
                return $this->error_response('الفئة غير موجودة', null);
            }

            // Get category children
            $categoryChilds = CategoryRepository()->getAllSubChilds($categoryId, true, true)->pluck('id')->toArray();

            $packages = Package::where('status', 'active')
                ->where(function($query) use($categoryChilds) {
                    $query->where('type', 'class');
                    $query->whereHas('categories', function($query) use($categoryChilds) {
                        $query->whereIn('categories.id', $categoryChilds);
                    });
                })
                ->orWhere(function($query) use($categoryChilds) {
                    $query->where('type', 'subject');
                    $query->whereHas('subjects', function($query) use($categoryChilds) {
                        $query->whereHas('program', function($query) use($categoryChilds) {
                            $query->whereIn('categories.id', $categoryChilds);
                        });
                        $query->orWhereHas('grade', function($query) use($categoryChilds) {
                            $query->whereIn('categories.id', $categoryChilds);
                        });
                        $query->orWhereHas('semester', function($query) use($categoryChilds) {
                            $query->whereIn('categories.id', $categoryChilds);
                        });
                    });
                })
                ->with(['categories', 'subjects'])
                ->get();

            return $this->success_response('تم جلب الباكدجات حسب الفئة بنجاح', [
                'packages' => $packages,
                'category' => $category,
                'total_packages' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الباكدجات: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get package subjects by class
     * @param Request $request
     * @param Package $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPackageSubjects(Request $request, Package $package)
    {
        try {
            $classId = $request->input('class_id');
            
            if ($package->type === 'class' && $classId) {
                $class = Category::find($classId);
                if (!$class) {
                    return $this->error_response('الفصل غير موجود', null);
                }

                $classChilds = CategoryRepository()->getAllSubChilds($classId, true, true)->pluck('id')->toArray();
                
                $subjects = Subject::where('is_active', true)
                    ->whereIn('programm_id', $classChilds)
                    ->orWhereIn('grade_id', $classChilds)
                    ->orWhereIn('semester_id', $classChilds)
                    ->orWhereHas('categories', function ($query) use($class) {
                        $query->where('pivot_level', 'field');
                        $query->where('category_id', $class->id);
                    })
                    ->with(['program', 'grade', 'semester', 'courses'])
                    ->get();
            } else {
                $subjects = $package->subjects()->with(['program', 'grade', 'semester', 'courses'])->get();
            }

            return $this->success_response('تم جلب المواد بنجاح', [
                'subjects' => $subjects,
                'package_type' => $package->type,
                'subjects_count' => $subjects->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب المواد: ' . $e->getMessage(), null);
        }
    }

    /**
     * Search packages
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('search');
            $type = $request->input('type'); // 'class' or 'subject'
            $categoryId = $request->input('category_id');

            if (!$searchTerm) {
                return $this->error_response('مصطلح البحث مطلوب', null);
            }

            $query = Package::where('status', 'active');

            // Apply search filters
            $query->where(function($q) use ($searchTerm) {
                $q->where('name_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description_en', 'LIKE', "%{$searchTerm}%");
            });

            // Filter by type if provided
            if ($type) {
                $query->where('type', $type);
            }

            // Filter by category if provided
            if ($categoryId) {
                $categoryChilds = CategoryRepository()->getAllSubChilds($categoryId, true, true)->pluck('id')->toArray();
                
                $query->where(function($q) use($categoryChilds) {
                    $q->where(function($subQuery) use($categoryChilds) {
                        $subQuery->where('type', 'class');
                        $subQuery->whereHas('categories', function($catQuery) use($categoryChilds) {
                            $catQuery->whereIn('categories.id', $categoryChilds);
                        });
                    })
                    ->orWhere(function($subQuery) use($categoryChilds) {
                        $subQuery->where('type', 'subject');
                        $subQuery->whereHas('subjects', function($subjQuery) use($categoryChilds) {
                            $subjQuery->whereHas('program', function($progQuery) use($categoryChilds) {
                                $progQuery->whereIn('categories.id', $categoryChilds);
                            });
                            $subjQuery->orWhereHas('grade', function($gradeQuery) use($categoryChilds) {
                                $gradeQuery->whereIn('categories.id', $categoryChilds);
                            });
                            $subjQuery->orWhereHas('semester', function($semQuery) use($categoryChilds) {
                                $semQuery->whereIn('categories.id', $categoryChilds);
                            });
                        });
                    });
                });
            }

            $packages = $query->with(['categories', 'subjects'])->get();

            return $this->success_response('تم البحث بنجاح', [
                'packages' => $packages,
                'search_term' => $searchTerm,
                'type_filter' => $type,
                'category_filter' => $categoryId,
                'total_results' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء البحث: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get all programmes (major categories)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgrammes()
    {
        try {
            $programmes = CategoryRepository()->getMajors();

            return $this->success_response('تم جلب البرامج بنجاح', [
                'programmes' => $programmes,
                'total_programmes' => $programmes->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب البرامج: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get package statistics
     * @param Package $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPackageStats(Package $package)
    {
        try {
            $stats = [
                'total_courses' => 0,
                'total_subjects' => 0,
                'total_categories' => 0,
                'package_type' => $package->type,
                'is_active' => $package->status === 'active'
            ];

            if ($package->type === 'class') {
                $stats['total_categories'] = $package->categories()->count();
                
                // Count courses through categories
                $categoryIds = $package->categories()->pluck('categories.id');
                $stats['total_courses'] = \App\Models\Course::whereHas('categories', function($query) use($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                })->count();
                
            } else {
                $stats['total_subjects'] = $package->subjects()->count();
                $stats['total_courses'] = $package->subjects()->withCount('courses')->get()->sum('courses_count');
            }

            return $this->success_response('تم جلب إحصائيات الباكدج بنجاح', $stats);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الإحصائيات: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get featured packages
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeatured(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            
            $packages = Package::where('status', 'active')
                ->where('is_featured', true) // Assuming you have a featured flag
                ->with(['categories', 'subjects'])
                ->limit($limit)
                ->get();

            return $this->success_response('تم جلب الباكدجات المميزة بنجاح', [
                'packages' => $packages,
                'total_featured' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الباكدجات المميزة: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get packages with pagination
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaginated(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $type = $request->input('type');
            $categoryId = $request->input('category_id');

            $query = Package::where('status', 'active');

            // Filter by type if provided
            if ($type) {
                $query->where('type', $type);
            }

            // Filter by category if provided
            if ($categoryId) {
                $categoryChilds = CategoryRepository()->getAllSubChilds($categoryId, true, true)->pluck('id')->toArray();
                
                $query->where(function($q) use($categoryChilds) {
                    $q->where(function($subQuery) use($categoryChilds) {
                        $subQuery->where('type', 'class');
                        $subQuery->whereHas('categories', function($catQuery) use($categoryChilds) {
                            $catQuery->whereIn('categories.id', $categoryChilds);
                        });
                    })
                    ->orWhere(function($subQuery) use($categoryChilds) {
                        $subQuery->where('type', 'subject');
                        $subQuery->whereHas('subjects', function($subjQuery) use($categoryChilds) {
                            $subjQuery->whereHas('program', function($progQuery) use($categoryChilds) {
                                $progQuery->whereIn('categories.id', $categoryChilds);
                            });
                            $subjQuery->orWhereHas('grade', function($gradeQuery) use($categoryChilds) {
                                $gradeQuery->whereIn('categories.id', $categoryChilds);
                            });
                            $subjQuery->orWhereHas('semester', function($semQuery) use($categoryChilds) {
                                $semQuery->whereIn('categories.id', $categoryChilds);
                            });
                        });
                    });
                });
            }

            $packages = $query->with(['categories', 'subjects'])
                             ->paginate($perPage);

            return $this->success_response('تم جلب الباكدجات بنجاح', [
                'packages' => $packages->items(),
                'pagination' => [
                    'current_page' => $packages->currentPage(),
                    'last_page' => $packages->lastPage(),
                    'per_page' => $packages->perPage(),
                    'total' => $packages->total(),
                    'from' => $packages->firstItem(),
                    'to' => $packages->lastItem(),
                    'has_more_pages' => $packages->hasMorePages()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الباكدجات: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get packages by multiple categories
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByMultipleCategories(Request $request)
    {
        try {
            $categoryIds = $request->input('category_ids', []);
            
            if (empty($categoryIds) || !is_array($categoryIds)) {
                return $this->error_response('معرفات الفئات مطلوبة', null);
            }

            $allCategoryChilds = [];
            foreach ($categoryIds as $categoryId) {
                $categoryChilds = CategoryRepository()->getAllSubChilds($categoryId, true, true)->pluck('id')->toArray();
                $allCategoryChilds = array_merge($allCategoryChilds, $categoryChilds);
            }
            
            $allCategoryChilds = array_unique($allCategoryChilds);

            $packages = Package::where('status', 'active')
                ->where(function($query) use($allCategoryChilds) {
                    $query->where('type', 'class');
                    $query->whereHas('categories', function($query) use($allCategoryChilds) {
                        $query->whereIn('categories.id', $allCategoryChilds);
                    });
                })
                ->orWhere(function($query) use($allCategoryChilds) {
                    $query->where('type', 'subject');
                    $query->whereHas('subjects', function($query) use($allCategoryChilds) {
                        $query->whereHas('program', function($query) use($allCategoryChilds) {
                            $query->whereIn('categories.id', $allCategoryChilds);
                        });
                        $query->orWhereHas('grade', function($query) use($allCategoryChilds) {
                            $query->whereIn('categories.id', $allCategoryChilds);
                        });
                        $query->orWhereHas('semester', function($query) use($allCategoryChilds) {
                            $query->whereIn('categories.id', $allCategoryChilds);
                        });
                    });
                })
                ->with(['categories', 'subjects'])
                ->get();

            return $this->success_response('تم جلب الباكدجات حسب الفئات بنجاح', [
                'packages' => $packages,
                'category_ids' => $categoryIds,
                'total_packages' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الباكدجات: ' . $e->getMessage(), null);
        }
    }
}