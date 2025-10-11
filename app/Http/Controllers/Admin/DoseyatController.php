<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doseyat;
use App\Models\POS;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoseyatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:doseyat-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:doseyat-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:doseyat-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:doseyat-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Doseyat::with(['pos', 'teacher', 'category']);

        // Filter by POS
        if ($request->has('pos_id') && $request->pos_id) {
            $query->where('pos_id', $request->pos_id);
        }

        // Filter by Teacher
        if ($request->has('teacher_id') && $request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by Category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $doseyats = $query->latest()->paginate(15);
        $posList = POS::all();
        $teachers = Teacher::all();
        $categories = Category::where('parent_id', null)->get();

        return view('admin.doseyat.index', compact('doseyats', 'posList', 'teachers', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $posList = POS::all();
        $teachers = Teacher::all();
        $categories = Category::where('parent_id', null)->get();

        return view('admin.doseyat.create', compact('posList', 'teachers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pos_id' => 'nullable|exists:p_o_s,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        Doseyat::create($validated);

        return redirect()->route('doseyats.index')
            ->with('success', __('messages.doseyat_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Doseyat $doseyat)
    {
        $doseyat->load(['pos', 'teacher', 'category']);
        return view('admin.doseyat.show', compact('doseyat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doseyat $doseyat)
    {
        $posList = POS::all();
        $teachers = Teacher::all();
        $categories = Category::where('parent_id', null)->get();

        return view('admin.doseyat.edit', compact('doseyat', 'posList', 'teachers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doseyat $doseyat)
    {
        $validated = $request->validate([
            'pos_id' => 'nullable|exists:p_o_s,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($doseyat->photo) {
                $filePath = base_path('assets/admin/uploads/' . $doseyat->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $validated['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        $doseyat->update($validated);

        return redirect()->route('doseyats.index')
            ->with('success', __('messages.doseyat_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doseyat $doseyat)
    {
        // Delete photo
        if ($doseyat->photo) {
            $filePath = base_path('assets/admin/uploads/' . $doseyat->photo);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $doseyat->delete();

        return redirect()->route('doseyats.index')
            ->with('success', __('messages.doseyat_deleted_successfully'));
    }
}
