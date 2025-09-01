<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Package;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class oldPackageAndOfferController extends Controller
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
        $programmParentCtgs = null;
        if($programm) $programmParentCtgs = CategoryRepository()->getAllSubChilds($programm?->id)->pluck('id')->toArray();

        // dd($programm,$programmParentCtgs);

        // جلب الباكدجات النشطة مع الكاتيجوريز المرتبطة بها
        // في حالة تمرير programm تاتي بالبكدجات المرتبطة به وباحفاده فقط
        $packages = Package::where('status', 'active')
            // ->with('categories')
            ->whereHas('categories' ,function($query)use($programm,$programmParentCtgs) {
                if($programm){
                    $query->whereIn('categories.id',$programmParentCtgs);
                }
                $query->select('packages.id','categories.id', 'categories.name_ar', 'categories.name_en', 'categories.type');
            })
            ->get();

        return view('web.packages-offers', compact('packages','programms','programm'));
    }


    // get packege and its categories(classes || lessons) && courses
    // if $package->type == 'class' get package.categories by relation then query for related subject with categories
    // if $package->type == 'lesson' get package.lessons by relation and no need to categories
    public function package(Request $request ,Package $package,Category $clas = null)
    {
        $is_type_class = ($package->type == 'class');
        // get subjects from requset or relation direct if $package->type == 'lesson'
        $classes         = $package->categories?->where('type','class');
        $lessons         = [];
        $categoriesTree  = collect();

        foreach ($classes as $ctg) {
            $categoriesTree = $categoriesTree->push(Category::getFlatList($ctg->id,'-- ','with_parent')->where('type','class'));
        }

        if($is_type_class && $clas?->id){
            $lessons     = Category::where('type','lesson')->where('parent_id',$clas->id)->get();
        }elseif(!$is_type_class){
            $lessons     = $package->categories?->where('type','lessons');
        }
        $lessonsIds      = $request->lessonsIds ?? [];
        $choosen_lessons = Category::whereIn('id',$lessonsIds)->get();

        return view('web.package', compact('categoriesTree','package','classes','lessons','choosen_lessons','clas','is_type_class'));
    }
}
