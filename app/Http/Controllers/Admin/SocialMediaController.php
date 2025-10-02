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
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:51200', // 50MB max
        ]);

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = uploadImage('assets/admin/uploads', $request->video);
        }

        SocialMedia::create([
            'video' => $videoPath,
        ]);

        return redirect()->route('social-media.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function edit(SocialMedia $socialMedia)
    {
        if (!auth()->user()->can('socialMedia-edit')) {
            abort(403, __('messages.unauthorized'));
        }

        return view('admin.social-media.edit', compact('socialMedia'));
    }

    public function update(Request $request, SocialMedia $socialMedia)
    {
        if (!auth()->user()->can('socialMedia-edit')) {
            abort(403, __('messages.unauthorized'));
        }

        $request->validate([
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200', // 50MB max
        ]);

        if ($request->hasFile('video')) {
            if ($socialMedia->video) {
                $filePath = base_path('assets/admin/uploads/' . $socialMedia->video);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }


            $videoPath = uploadImage('assets/admin/uploads', $request->video);
            $socialMedia->update(['video' => $videoPath]);
        }

        return redirect()->route('social-media.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(SocialMedia $socialMedia)
    {
        if (!auth()->user()->can('socialMedia-delete')) {
            abort(403, __('messages.unauthorized'));
        }

        // Delete video file
        if ($socialMedia->video) {
           $filePath = base_path('assets/admin/uploads/' . $socialMedia->video);
           if (file_exists($filePath)) {
               unlink($filePath);
           }
        }

        $socialMedia->delete();

        return redirect()->route('social-media.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
