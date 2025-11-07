<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class AboutController extends Controller
{
    public function index()
    {
        return view('web.about');
    }

}