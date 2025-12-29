<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Package;
use App\Models\Category;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $query = Package::with(['categories', 'subjects']);

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:class,subject',
            'how_much_course_can_select' => 'required|integer|min:1',
            'categories' => 'nullable|required_if:type,class|array|min:1', // Categories are required
            'categories.*' => 'exists:categories,id',
            'subjects' => 'nullable|required_if:type,subject|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        $packageData = $request->only([
            'name',
            'price',
            'description',
            'status',
            'type',
            'how_much_course_can_select'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $packageData['image'] = uploadImage('assets/admin/uploads', $request->image);
        }

        $package = Package::create($packageData);

        // Save categories and subjects
        $this->savePackageCategories(
            $package,
            $request->categories,
            $request->subjects ?? []
        );

        return redirect()->route('packages.index')
            ->with('success', __('messages.Package created successfully'));
    }





    /**
     * Display the specified package
     */
    public function show(Package $package)
    {
        $package->load(['categories.parent', 'subjects', 'packageCategories.category', 'packageCategories.subject']);

        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit(Package $package)
    {
        $package->load(['categories', 'subjects', 'packageCategories']);

        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:class,subject',
            'how_much_course_can_select' => 'required|integer|min:1',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        $packageData = $request->only([
            'name',
            'price',
            'description',
            'status',
            'type',
            'how_much_course_can_select'
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

        \DB::table('package_categories')->where('package_id', $package->id)->delete();

        // Save new categories and subjects
        $this->savePackageCategories(
            $package,
            $request->categories,
            $request->subjects ?? []
        );

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


    private function savePackageCategories($package, $categories, $subjects = [])
    {
        $packageCategoriesData = [];

        foreach ($categories as $categoryId) {
            if (!empty($subjects)) {
                // Create entry for each category-subject combination
                foreach ($subjects as $subjectId) {
                    $packageCategoriesData[] = [
                        'package_id' => $package->id,
                        'category_id' => $categoryId,
                        'subject_id' => $subjectId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            } else {
                // Create entry for category only (no specific subject)
                $packageCategoriesData[] = [
                    'package_id' => $package->id,
                    'category_id' => $categoryId,
                    'subject_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($packageCategoriesData)) {
            \DB::table('package_categories')->insert($packageCategoriesData);
        }
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

        if (!in_array($type, ['class', 'subject'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        if ($type == 'class') {
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

        $subjects = Subject::with(['grade','semester'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'icon', 'color'])
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name_ar' => $subject->name_ar,
                    'name_en' => $subject->name_en,
                    'display_name' => $subject->grade ? $subject->grade->breadcrumb  . ' >> ' . $subject->name_ar : ($subject->semester ? $subject->semester->breadcrumb  . ' >> ' . $subject->name_ar : $subject->name_ar),
                    'icon' => $subject->icon,
                    'color' => $subject->color,
                    'parent_name' => $subject->parent ? $subject->parent->name_ar : null
                ];
            });

        return response()->json($subjects);
    }


    /**
     * Get subjects by category (AJAX)
     */
    public function getSubjectsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        if (!$categoryId) {
            return response()->json(['error' => 'Category ID is required'], 400);
        }

        // Get subjects through the category_subjects pivot table using direct DB query
        $subjects = \DB::table('subjects')
            ->join('category_subjects', 'subjects.id', '=', 'category_subjects.subject_id')
            ->where('category_subjects.category_id', $categoryId)
            ->where('subjects.is_active', 1)
            ->select('subjects.id', 'subjects.name_ar', 'subjects.name_en', 'subjects.icon', 'subjects.color')
            ->orderBy('subjects.sort_order')
            ->orderBy('subjects.name_ar')
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => (int) $subject->id,
                    'name_ar' => $subject->name_ar,
                    'name_en' => $subject->name_en,
                    'icon' => $subject->icon,
                    'color' => $subject->color,
                ];
            });

        return response()->json($subjects);
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
