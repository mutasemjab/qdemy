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
        if($programm) $programmParentCtgs = CategoryRepository()->getAllSubChilds($programm?->id,true)->pluck('id')->toArray();

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

    /**
     * عرض صفحة الباقة
    */
    public function show(Request $request, $packageId, $clasId = null)
    {
        $package = Package::with(['categories' => function($query) {
            $query->where('is_active', 1);
        }])->findOrFail($packageId);

        $clas = null;
        $categoriesTree = [];
        $lessons = collect();
        $is_type_class = false;

        // Check if package type is class
        if ($package->type === 'class') {
            $is_type_class = true;

            // Get categories tree for class selector
            $categoriesTree = CategoryRepository()->getCategoriesTreeForPackage($package->id);

            if ($clasId) {
                $clas = Category::find($clasId);
                // Get lessons for selected class
                $lessons = CategoryRepository()->getLessonsForClass($clasId, $package->id);
            } else {
                // Get all lessons for package
                $lessons = CategoryRepository()->getAllLessonsForPackage($package->id);
            }
        } else {
            // For lesson type packages
            $lessons = $package->categories()->where('type', 'lesson')->get();
        }

        return view('web.package', compact('package', 'clas', 'categoriesTree', 'lessons', 'is_type_class'));
    }

    /**
     * تحديث السلة بكورسات الباقة
     */
    public function updatePackageCart(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id'
        ]);

        try {
            $packageId = $request->package_id;
            $courseIds = $request->courses;

            // Get package
            $package = Package::find($packageId);

            // Validate courses count
            if (count($courseIds) != $package->how_much_course_can_select) {
                return response()->json([
                    'success' => false,
                    'message' => "يجب اختيار {$package->how_much_course_can_select} كورسات بالضبط"
                ], 400);
            }

            $validOnePackgeOnlyOnCart = CartRepository()->validCartContent($packageId);
            if (!$validOnePackgeOnlyOnCart) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن الجمع بين الكورسات المنفردة والباكدجات ف الكارت و لا بين 2 باكدج .'
                ], 400);
            }

            CartRepository()->clearCart();
            CartRepository()->setPackageCart($packageId, $courseIds);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث السلة بنجاح',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث السلة',
                'e' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على محتويات السلة الحالية
     */
    public function getPackageCart(Request $request)
    {
        try {
            $cart = CartRepository()->getPackageCart();

            return response()->json([
                'success' => true,
                'cart' => $cart
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب محتويات السلة'
            ], 500);
        }
    }

}
