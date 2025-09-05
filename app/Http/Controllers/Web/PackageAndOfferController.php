<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Package;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageAndOfferController extends Controller
{
    /**
     * Display active packages with their categories
     * @param programm = category->id
     * @return \Illuminate\View\View
     */
    public function index(Category $programm = null)
    {
        //  categories where type = major (main programms)
        $programms   = CategoryRepository()->getMajors();

        // get all sub childs for passed category
        $programmChilds = null;
        if($programm) $programmChilds = CategoryRepository()->getAllSubChilds($programm?->id,true,true)->pluck('id')->toArray();

        // جلب الباكدجات النشطة مع الكاتيجوريز المرتبطة بها
        // في حالة تمرير programm تاتي بالبكدجات المرتبطة به وباحفاده فقط
        $packages = Package::where('status', 'active')
            ->where(function($query)use($programm,$programmChilds) {
                $query->where('type','class');
                $query->whereHas('categories' ,function($query)use($programm,$programmChilds) {
                    if($programm){
                        $query->whereIn('categories.id',$programmChilds);
                    }
                    $query->select('packages.id','categories.id', 'categories.name_ar', 'categories.name_en', 'categories.type');
               });
            })
            ->orWhere(function($query)use($programmChilds) {
                $query->where('type','subject');
                $query->whereHas('subjects' ,function($query)use($programmChilds) {
                    if($programmChilds && count($programmChilds)){
                        $query->whereHas('program' ,function($query)use($programmChilds) {
                            $query->whereIn('categories.id',$programmChilds);
                        });
                        $query->orWhereHas('grade' ,function($query)use($programmChilds) {
                            $query->whereIn('categories.id',$programmChilds);
                        });
                        $query->orWhereHas('semester' ,function($query)use($programmChilds) {
                            $query->whereIn('categories.id',$programmChilds);
                        });
                    }
                });
            })
            ->get();

        return view('web.packages-offers', compact('packages','programms','programm'));
    }


    // get packege and its (categories  || subjects) && courses
    // if $package->type == 'class' get package.categories by relation then query for related subject with categories
    // if $package->type == 'subject' get package.subject  by relation and no need to categories
    public function show(Request $request ,Package $package,Category $clas = null)
    {
        $is_type_class = ($package->type == 'class');
        // get subjects from requset or relation direct if $package->type == 'subject'
        $classes         = $package->categories?->where('type','class');
        $subjects         = [];
        $categoriesTree  = collect();

        foreach ($classes as $ctg) {
            $categoriesTree = $categoriesTree->push(Category::getFlatList($ctg->id,'-- ','with_parent')->where('type','class'));
        }

        if($is_type_class && $clas?->id){
            $classChilds = CategoryRepository()->getAllSubChilds($clas->id,true,true)->pluck('id')->toArray();
            $subjects     = Subject::where('is_active',true)
                            ->whereIn('programm_id',$classChilds)
                            ->orWhereIn('grade_id',$classChilds)
                            ->orWhereIn('semester_id',$classChilds)
                            ->get();
        }elseif(!$is_type_class){
            $subjects     = $package->subjects;
        }
        $subjectsIds      = $request->lessonsIds ?? [];

        return view('web.package', compact('categoriesTree','package','classes','subjects','clas','is_type_class'));
    }

}
