<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnBoarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnBoardingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $onboardings = OnBoarding::latest()->paginate(10);
        return view('admin.onboardings.index', compact('onboardings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.onboardings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        OnBoarding::create($validated);

        return redirect()->route('onboardings.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(OnBoarding $onboarding)
    {
        return view('admin.onboardings.show', compact('onboarding'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OnBoarding $onboarding)
    {
        return view('admin.onboardings.edit', compact('onboarding'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OnBoarding $onboarding)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
          if ($onboarding->photo) {
                $filePath = base_path('assets/admin/uploads/' . $onboarding->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
             }
            $validated['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        $onboarding->update($validated);

        return redirect()->route('onboardings.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OnBoarding $onboarding)
    {
        // Delete photo file
         if ($onboarding->photo) {
                $filePath = base_path('assets/admin/uploads/' . $onboarding->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
        }

        $onboarding->delete();

        return redirect()->route('onboardings.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}