<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageAndOfferController extends Controller
{
    public function packages_offers()
    {
        return view('web.packages-offers');
    }
}
