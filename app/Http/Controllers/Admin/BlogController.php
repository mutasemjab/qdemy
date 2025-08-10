<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:blog-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:blog-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:blog-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'photo_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] =  uploadImage('assets/admin/uploads', $request->photo);
        }

        // Handle photo cover upload
        if ($request->hasFile('photo_cover')) {
           
            $data['photo_cover'] = uploadImage('assets/admin/uploads', $request->photo_cover);
        }

        Blog::create($data);

        return redirect()->route('blogs.index')
            ->with('success', __('messages.blog_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'photo_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($blog->photo) {
                $filePath = base_path('assets/admin/uploads/' . $blog->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }
        // Handle photo cover upload
        if ($request->hasFile('photo_cover')) {
            if ($blog->photo_cover) {
                $filePath = base_path('assets/admin/uploads/' . $blog->photo_cover);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['photo_cover'] = uploadImage('assets/admin/uploads', $request->photo_cover);
        }

        $blog->update($data);

        return redirect()->route('blogs.index')
            ->with('success', __('messages.blog_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Delete photos
        if ($blog->photo) {
            $filePath = base_path('assets/admin/uploads/' . $blog->photo);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if ($blog->photo_cover) {
            $filePath = base_path('assets/admin/uploads/' . $blog->photo_cover);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }


        $blog->delete();

        return redirect()->route('blogs.index')
            ->with('success', __('messages.blog_deleted_successfully'));
    }
}
