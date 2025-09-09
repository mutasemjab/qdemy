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
             
            ]);

        } catch (\Exception $e) {
            return $this->error_response('حدث خطأ أثناء جلب الباكدجات: ' . $e->getMessage(), null);
        }
    }
}