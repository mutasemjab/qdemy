<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Package;
use App\Models\Category;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:package-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:package-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:package-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:package-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of packages
     */
    public function index(Request $request)
    {
        $query = Package::with('categories');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $packages = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:class,lesson',
            'how_much_course_can_select' => 'required|integer|min:1',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $packageData = $request->only([
            'name', 'price', 'description', 'status', 'type', 'how_much_course_can_select'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $packageData['image'] = uploadImage('assets/admin/uploads', $request->image);
        }

        $package = Package::create($packageData);

        // Attach selected categories
        if ($request->filled('categories')) {
            $package->categories()->attach($request->categories);
        }

        return redirect()->route('packages.index')
            ->with('success', __('messages.Package created successfully'));
    }

    /**
     * Display the specified package
     */
    public function show(Package $package)
    {
        $package->load('categories.parent');
        
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit(Package $package)
    {
        $package->load('categories');
        
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:class,lesson',
            'how_much_course_can_select' => 'required|integer|min:1',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $packageData = $request->only([
            'name', 'price', 'description', 'status', 'type', 'how_much_course_can_select'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($package->image) {
                $filePath = base_path('assets/admin/uploads/' . $package->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $packageData['image'] = uploadImage('assets/admin/uploads', $request->image);
        }

        $package->update($packageData);

        // Sync selected categories
        $package->categories()->sync($request->categories ?? []);

        return redirect()->route('packages.index')
            ->with('success', __('messages.Package updated successfully'));
    }

    /**
     * Remove the specified package
     */
    public function destroy(Package $package)
    {
        // Delete image if exists
        if ($package->image) {
            $filePath = base_path('assets/admin/uploads/' . $package->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', __('messages.Package deleted successfully'));
    }

    /**
     * Toggle package status
     */
    public function toggleStatus(Package $package)
    {
        $package->update(['status' => $package->status === 'active' ? 'inactive' : 'active']);
        
        $status = $package->status === 'active' ? 'activated' : 'deactivated';
        return back()->with('success', __("messages.Package {$status} successfully"));
    }

    /**
     * Get categories by type (AJAX)
     */
    public function getCategoriesByType(Request $request)
    {
        $type = $request->get('type');
        
        if (!in_array($type, ['class', 'lesson'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $categories = Category::with('parent')
            ->where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'parent_id', 'icon', 'color'])
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name_ar' => $category->name_ar,
                    'name_en' => $category->name_en,
                    'display_name' => $category->parent 
                        ? $category->parent->name_ar . ' >> ' . $category->name_ar
                        : $category->name_ar,
                    'icon' => $category->icon,
                    'color' => $category->color,
                    'parent_name' => $category->parent ? $category->parent->name_ar : null
                ];
            });

        return response()->json($categories);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'packages' => 'required|array',
            'packages.*' => 'exists:packages,id'
        ]);

        $packages = Package::whereIn('id', $request->packages);

        switch ($request->action) {
            case 'activate':
                $packages->update(['status' => 'active']);
                $message = __('messages.Packages activated successfully');
                break;
                
            case 'deactivate':
                $packages->update(['status' => 'inactive']);
                $message = __('messages.Packages deactivated successfully');
                break;
                
            case 'delete':
                // Delete images for all packages
                $packageList = $packages->get();
                foreach ($packageList as $package) {
                    if ($package->image) {
                        $filePath = base_path('assets/admin/uploads/' . $package->image);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
                $packages->delete();
                $message = __('messages.Packages deleted successfully');
                break;
        }

        return back()->with('success', $message);
    }
}