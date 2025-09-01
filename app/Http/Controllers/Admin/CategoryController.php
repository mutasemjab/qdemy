<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{

    public $tawjihi_optional_fields_ctg_ids;
    public function __construct()
    {
        $this->middleware('permission:category-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:category-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
        $this->tawjihi_optional_fields_ctg_ids = Category::where('level','tawjihi_scientific_fields')
                                                         ->orWhere('level','tawjihi_literary_fields')->pluck('id')->toArray() ?? [];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // For search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $categories = Category::with(['parent', 'children'])
                ->where(function ($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%");
                })
                ->orderBy('sort_order')
                ->orderBy('name_ar')
                ->paginate(20);

            return view('admin.categories.search-results', compact('categories'));
        }

        // Get root categories with their children for tree display
        $rootCategories = Category::whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->where('is_active', true)->orderBy('sort_order')->orderBy('name_ar');
            }])
            // ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();

        return view('admin.categories.index', compact('rootCategories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'descendants']);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Get validated data
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Check if is_active status changed
            $statusChanged = $category->is_active != $validated['is_active'];

            // Update category
            $category->update($validated);

            // If status changed, update all children
            if ($statusChanged) {
                $this->updateChildrenStatus($category->id, $validated['is_active']);
            }

            DB::commit();

            return redirect()
                ->route('categories.show', $category)
                ->with('success', __('messages.category_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.error_updating_category'));
        }
    }

    /**
     * Update all children categories status recursively
     */
    protected function updateChildrenStatus($parentId, $status)
    {
        // Get all children using the repository method
        $children = CategoryRepository()->getAllSubChilds($parentId,null,'with_parent');
        foreach ($children as $child) {
            $child->update(['is_active' => $status]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Category $category)
    // {
    //     // Check if category has children
    //     if ($category->children()->count() > 0) {
    //         return back()->with('error', __('messages.Cannot delete category with subcategories'));
    //     }

    //     $category->delete();

    //     return redirect()->route('categories.index')
    //         ->with('success', __('messages.Category deleted successfully'));
    // }

    /**
     * Get category tree as JSON for API or AJAX requests
     */
    public function tree(Request $request)
    {
        $parentId = $request->get('parent_id');
        $tree = Category::getTree($parentId);

        return response()->json($tree);
    }

    /**
     * Toggle category active status
     */
    public function toggleStatus(Category $category)
    {
        $status_changed = $category->is_active ? 'deactivated' : 'activated';
        $status = !$category->is_active;
        $this->updateChildrenStatus($category->id, $status);
        return back()->with('success', __("messages.Category {$status_changed} successfully"));
    }

    /**
     * Get subcategories for AJAX requests
     */
    public function getSubcategories(Request $request)
    {
        $parentId = $request->get('parent_id');
        $subcategories = Category::where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'type']);

        return response()->json($subcategories);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $categories = Category::whereIn('id', $request->categories);

        switch ($request->action) {
            case 'activate':
                $categories->update(['is_active' => true]);
                $message = __('messages.Categories activated successfully');
                break;

            case 'deactivate':
                $categories->update(['is_active' => false]);
                $message = __('messages.Categories deactivated successfully');
                break;

            case 'delete':
                // Check if any category has children
                $hasChildren = $categories->whereHas('children')->exists();
                if ($hasChildren) {
                    return back()->with('error', __('messages.Cannot delete categories with subcategories'));
                }
                $categories->delete();
                $message = __('messages.Categories deleted successfully');
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Helper method to build flat list for select options
     */
    private function buildFlatList($categories, $parentId = null, $prefix = '')
    {
        $result = collect();

        $items = $categories->where('parent_id', $parentId)->sortBy('sort_order');

        foreach ($items as $item) {
            $result->push([
                'id' => $item->id,
                'name' => $prefix . $item->name_ar,
                'depth' => substr_count($prefix, '-- '),
                'category' => $item,
                'type' => $item->type
            ]);

            $children = $categories->where('parent_id', $item->id);
            if ($children->isNotEmpty()) {
                $result = $result->merge($this->buildFlatList($categories, $item->id, $prefix . '-- '));
            }
        }

        return $result;
    }
}
