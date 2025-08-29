<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;

class ContactUsController extends Controller
{

    public function index()
    {

        $contactUs = ContactUs::latest()->paginate(PGN);
        return view('admin.contact-us.index', compact('contactUs'));
    }
}