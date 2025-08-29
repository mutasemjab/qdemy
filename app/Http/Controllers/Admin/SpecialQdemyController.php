<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialQdemy;
use Illuminate\Http\Request;

class SpecialQdemyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialQdemies = SpecialQdemy::latest()->paginate(PGN);
        return view('admin.special-qdemies.index', compact('specialQdemies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.special-qdemies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
        ]);

        SpecialQdemy::create($validated);

        return redirect()->route('special-qdemies.index')
            ->with('success', __('messages.created_successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecialQdemy $specialQdemy)
    {
        return view('admin.special-qdemies.edit', compact('specialQdemy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpecialQdemy $specialQdemy)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
        ]);

        $specialQdemy->update($validated);

        return redirect()->route('special-qdemies.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialQdemy $specialQdemy)
    {
        $specialQdemy->delete();

        return redirect()->route('special-qdemies.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}