<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class BlogController extends Controller
{
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('web.blog-details', compact('blog'));
    }

}

