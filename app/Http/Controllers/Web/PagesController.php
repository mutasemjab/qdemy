<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function cards_order()
    {
        return view('web.cards-order');
    }

    public function community()
    {
        return view('web.community');
    }

    public function bank_questions()
    {
        return view('web.bank-questions');
    }

    public function ex_questions()
    {
        return view('web.ex');
    }

}
