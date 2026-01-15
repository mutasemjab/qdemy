<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:setting-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:setting-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:setting-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:setting-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Setting::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('text_under_logo_in_footer', 'like', "%{$search}%");
            });
        }

        $settings = $query->paginate(15);

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'text_under_logo_in_footer' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'instagram' => 'required|string|max:255',
            'youtube' => 'required|string|max:255',
            'tiktok' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'google_play_link' => 'nullable|url|max:255',
            'app_store_link' => 'nullable|url|max:255',
            'hawawi_link' => 'nullable|url|max:255',
            'min_version_google_play' => 'nullable|string|max:255',
            'min_version_app_store' => 'nullable|string|max:255',
            'min_version_hawawi' => 'nullable|string|max:255',
            'number_of_course' => 'nullable|string|max:255',
            'number_of_teacher' => 'nullable|string|max:255',
            'number_of_viewing_hour' => 'nullable|string|max:255',
            'number_of_students' => 'nullable|string|max:255',
            'pos_commission_distribution' => 'required|in:50_50,100_teacher,100_platform',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = uploadImage('assets/admin/uploads', $request->logo);
        }

        Setting::create($data);

        return redirect()->route('settings.index')
            ->with('success', __('messages.Setting created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        return view('admin.settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'text_under_logo_in_footer' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'instagram' => 'required|string|max:255',
            'youtube' => 'required|string|max:255',
            'tiktok' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'google_play_link' => 'nullable|url|max:255',
            'app_store_link' => 'nullable|url|max:255',
            'hawawi_link' => 'nullable|url|max:255',
            'min_version_google_play' => 'nullable|string|max:255',
            'min_version_app_store' => 'nullable|string|max:255',
            'min_version_hawawi' => 'nullable|string|max:255',
            'number_of_course' => 'nullable|string|max:255',
            'number_of_teacher' => 'nullable|string|max:255',
            'number_of_viewing_hour' => 'nullable|string|max:255',
            'number_of_students' => 'nullable|string|max:255',
            'pos_commission_distribution' => 'required|in:50_50,100_teacher,100_platform',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
   
            $data['logo'] = uploadImage('assets/admin/uploads', $request->logo);
        } else {
            // Keep the current logo
            unset($data['logo']);
        }

        $setting->update($data);

        return redirect()->route('settings.index')
            ->with('success', __('messages.Setting updated successfully'));
    }

    
}