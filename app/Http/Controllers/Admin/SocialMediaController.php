<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialMediaController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('socialMedia-table')) {
            abort(403, __('messages.unauthorized'));
        }

        $socialMedia = SocialMedia::latest()->paginate(10);
        return view('admin.social-media.index', compact('socialMedia'));
    }

    public function create()
    {
        if (!auth()->user()->can('socialMedia-add')) {
            abort(403, __('messages.unauthorized'));
        }

        return view('admin.social-media.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('socialMedia-add')) {
            abort(403, __('messages.unauthorized'));
        }

        $request->validate([
            'video' => 'required|url',
        ]);

        SocialMedia::create([
            'video' => $request->video,
        ]);

        return redirect()->route('social-media.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('socialMedia-edit')) {
            abort(403, __('messages.unauthorized'));
        }

        $social_medium = SocialMedia::findOrFail($id);
        return view('admin.social-media.edit', compact('social_medium'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('socialMedia-edit')) {
            abort(403, __('messages.unauthorized'));
        }

        $request->validate([
            'video' => 'required|url',
        ]);

        $social_medium = SocialMedia::findOrFail($id);
        $social_medium->update(['video' => $request->video]);

        return redirect()->route('social-media.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('socialMedia-delete')) {
            abort(403, __('messages.unauthorized'));
        }

        $social_medium = SocialMedia::findOrFail($id);
        $social_medium->delete();

        return redirect()->route('social-media.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
