<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Package;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{
    public function index()
    {
      $banner = Banner::first();
      $products= Product::where('status',1)->get();
      $packages= Package::where('status',1)->get();
      $page = Page::where('type',1)->first();
      $locale = App::getLocale();

    //   {{$locale === 'ar' ? $subjectTeacher->name : $subjectTeacher->foreign_name}}
    //   {{ asset('assets/admin/uploads/' . $product->productImages->first()->photo) }}

        return view('user.home',compact('banner','products','page','locale','packages'));
    }

    public function customer_service()
    {
      $locale = App::getLocale();
      $page = Page::where('type',2)->first();
      return view('user.customer_services',compact('page','locale'));
    }
    
    public function mission()
    {
      $locale = App::getLocale();
      $page = Page::where('type',4)->first();
      return view('user.mission_values',compact('page','locale'));
    }
   
    public function policy()
    {
      $locale = App::getLocale();
      $page = Page::where('type',3)->first();
      return view('user.privacy',compact('page','locale'));
    }

}
