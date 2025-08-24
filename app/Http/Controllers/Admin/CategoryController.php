<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
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
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();

        return view('admin.categories.index', compact('rootCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parentCategories = Category::getFlatList();
        $parentId         = $request->get('parent_id');
        $tawjihi_optional_fields_ctg_ids = $this->tawjihi_optional_fields_ctg_ids;
        return view('admin.categories.create', compact('parentCategories', 'parentId','tawjihi_optional_fields_ctg_ids'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon'  => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
            'parent_id'   => 'nullable|exists:categories,id',
            'type'        => 'required|in:class,lesson,major',
            'is_optional' => 'nullable|required_if:type,lesson|in:0,1',
            'is_ministry' => 'nullable|required_if:type,lesson|in:0,1',
            'field_type'  => 'nullable|required_if:type,lesson|in:'.implode(',',FIELD_TYPE),
            'optional_form_field_type' => 'nullable|required_if:type,lesson|in:'.implode(',',OPTIONAL_FROM_FIELD_TYPE),
        ]);
        // tawjihi_program_subject
        $data = $request->all();
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        if ($data['type']  == 'lesson') { $data['level'] = 'tawjihi_program_subject';}

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', __('messages.Category created successfully'));
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
        // Get all categories except the current one and its descendants to prevent circular references
        $excludeIds = collect([$category->id]);
        $this->addDescendantIds($category, $excludeIds);

        $parentCategories = Category::whereNotIn('id', $excludeIds)->get();
        $parentCategories = $this->buildFlatList($parentCategories);
        $is_editable = $category->is_ediatble;
        $tawjihi_optional_fields_ctg_ids = $this->tawjihi_optional_fields_ctg_ids;
        return view('admin.categories.edit', compact('category', 'parentCategories','is_editable','tawjihi_optional_fields_ctg_ids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if(!$category->is_ediatble) return false;
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon'        => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:7',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
            'parent_id'   => 'nullable|exists:categories,id',
            'type'        => 'required|in:class,lesson,major',
            'is_optional' => 'nullable|required_if:type,lesson|in:0,1',
            'is_ministry' => 'nullable|required_if:type,lesson|in:0,1',
            'field_type'  => 'nullable|required_if:is_optional,1|required_if:type,lesson|in:'.implode(',',FIELD_TYPE),
            'optional_form_field_type' => 'nullable|required_if:is_optional,1|required_if:type,lesson|in:'.implode(',',OPTIONAL_FROM_FIELD_TYPE),
        ]);

        $data = $request->all();

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        if ($data['type']  == 'lesson') { $data['level'] = 'tawjihi_program_subject';}

        // Prevent circular reference
        if ($data['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => __('messages.Category cannot be its own parent')]);
        }

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', __('messages.Category updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has children
        if ($category->children()->count() > 0) {
            return back()->with('error', __('messages.Cannot delete category with subcategories'));
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', __('messages.Category deleted successfully'));
    }

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
     * Move category up in sort order
     */
    public function moveUp(Category $category)
    {
        $sibling = Category::where('parent_id', $category->parent_id)
            ->where('sort_order', '<', $category->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($sibling) {
            $tempOrder = $category->sort_order;
            $category->update(['sort_order' => $sibling->sort_order]);
            $sibling->update(['sort_order' => $tempOrder]);
        }

        return back()->with('success', __('messages.Category moved up successfully'));
    }

    /**
     * Move category down in sort order
     */
    public function moveDown(Category $category)
    {
        $sibling = Category::where('parent_id', $category->parent_id)
            ->where('sort_order', '>', $category->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($sibling) {
            $tempOrder = $category->sort_order;
            $category->update(['sort_order' => $sibling->sort_order]);
            $sibling->update(['sort_order' => $tempOrder]);
        }

        return back()->with('success', __('messages.Category moved down successfully'));
    }

    /**
     * Toggle category active status
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        return back()->with('success', __("messages.Category {$status} successfully"));
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
     * Get lessons only for AJAX requests (for connecting to other tables)
     */
    public function getLessons(Request $request)
    {
        $parentId = $request->get('parent_id');
        $query = Category::where('type', 'lesson')
            ->where('is_active', true);

        if ($parentId) {
            $query->where('parent_id', $parentId);
        }

        $lessons = $query->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'parent_id']);

        return response()->json($lessons);
    }

    /**
     * Get categories by type
     */
    public function getByType(Request $request, $type)
    {
        $request->validate([
            'type' => 'in:class,lesson,major'
        ]);

        $categories = Category::where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'parent_id', 'type']);

        return response()->json($categories);
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
     * Helper method to add descendant IDs recursively
     */
    private function addDescendantIds($category, &$excludeIds)
    {
        $children = $category->children;
        foreach ($children as $child) {
            $excludeIds->push($child->id);
            $this->addDescendantIds($child, $excludeIds);
        }
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
