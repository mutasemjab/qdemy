<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    public function index()
    {
        return view('web.contact');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ], [
            'name.required' => __('front.Name is required'),
            'email.email' => __('front.Please enter a valid email'),
            'message.required' => __('front.Message is required'),
        ]);

      //  try {
            // Create contact record
            ContactUs::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->country_code . $request->phone,
                'message' => $request->message,
            ]);

            // Redirect back with success message
            return redirect()->back()->with('success', __('front.Your message has been sent successfully'));
       // } catch (\Exception $e) {
          //  return redirect()->back()->with('error', __('front.Something went wrong, please try again'))->withInput();
       // }
    }
}