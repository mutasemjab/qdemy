<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\QuestionWebsite;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = QuestionWebsite::all();
        
        // Get distinct types from database
        $types = QuestionWebsite::select('type')->distinct()->pluck('type')->toArray();
        
        // Add 'all' at the beginning if not exists
        if (!in_array('all', $types)) {
            array_unshift($types, 'all');
        }
        
        // Map type values to labels
        $typeLabels = [
            'all' => __('front.All'),
            'register' => __('front.Registration'),
            'payment' => __('front.Payment'),
            'card' => __('front.Cards'),
            'courses' => __('front.Courses'),
            'technical' => __('front.Technical'),
            'privacy' => __('front.Privacy'),
            'account' => __('front.My Account'),
        ];
        
        $categories = array_map(function($type) use ($typeLabels) {
            return [
                'key' => $type,
                'label' => $typeLabels[$type] ?? ucfirst($type)
            ];
        }, $types);
        
        return view('web.faq', compact('faqs', 'categories'));
    }
}