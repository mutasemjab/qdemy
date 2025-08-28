<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Package;
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
}
