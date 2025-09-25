<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\POS;

class PagesController extends Controller
{

    public function download()
    {
        return view('web.download');
    }


   public function sale_point(Request $request)
    {
        $query = POS::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('country_name', 'LIKE', "%{$search}%");
            });
        }

        // Group by country/city
        $posGrouped = $query->get()->groupBy('country_name');

        return view('web.sale-point', compact('posGrouped'));
    }


      public function privacyPolicy()
    {
        $page = Page::getPrivacyPolicy();
        
        if (!$page) {
            abort(404);
        }

        return view('pages.privacy-policy', compact('page'));
    }

    /**
     * Display terms and conditions page
     */
    public function termsConditions()
    {
        $page = Page::getTermsConditions();
        
        if (!$page) {
            abort(404);
        }

        return view('pages.terms-conditions', compact('page'));
    }


}
