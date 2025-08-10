<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\MinisterialYearsQuestion;
use App\Models\Category;
use Illuminate\Http\Request;

class MinisterialYearsQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ministerial-question-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:ministerial-question-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:ministerial-question-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:ministerial-question-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ministerialQuestions = MinisterialYearsQuestion::with('category.parent')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.ministerial_questions.index', compact('ministerialQuestions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();
        return view('admin.ministerial_questions.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_category' => 'required|exists:categories,id',
            'category_id' => 'required|exists:categories,id',
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $data = [
            'category_id' => $request->category_id,
        ];

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $data['pdf'] = uploadImage('assets/admin/uploads/pdfs', $request->pdf);
        }

        MinisterialYearsQuestion::create($data);

        return redirect()->route('ministerial-questions.index')
            ->with('success', __('messages.ministerial_question_created_successfully'));
    }

  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MinisterialYearsQuestion $ministerialQuestion)
    {
        $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();

        // Get the parent and child category for the current ministerial question
        $selectedParent = null;
        $selectedChild = null;
        
        if ($ministerialQuestion->category) {
            if ($ministerialQuestion->category->parent_id) {
                $selectedParent = $ministerialQuestion->category->parent_id;
                $selectedChild = $ministerialQuestion->category_id;
            } else {
                $selectedParent = $ministerialQuestion->category_id;
            }
        }

        return view('admin.ministerial_questions.edit', compact('ministerialQuestion', 'parentCategories', 'selectedParent', 'selectedChild'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MinisterialYearsQuestion $ministerialQuestion)
    {
        $request->validate([
            'parent_category' => 'required|exists:categories,id',
            'category_id' => 'required|exists:categories,id',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $data = [
            'category_id' => $request->category_id,
        ];

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            // Delete old PDF
            if ($ministerialQuestion->pdf) {
                $filePath = base_path('assets/admin/uploads/pdfs/' . $ministerialQuestion->pdf);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['pdf'] = uploadImage('assets/admin/uploads/pdfs', $request->pdf);
        } else {
            $data['pdf'] = $ministerialQuestion->pdf;
        }

        $ministerialQuestion->update($data);

        return redirect()->route('ministerial-questions.index')
            ->with('success', __('messages.ministerial_question_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MinisterialYearsQuestion $ministerialQuestion)
    {
        // Delete PDF file
        if ($ministerialQuestion->pdf) {
            $filePath = base_path('assets/admin/uploads/pdfs/' . $ministerialQuestion->pdf);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $ministerialQuestion->delete();

        return redirect()->route('ministerial-questions.index')
            ->with('success', __('messages.ministerial_question_deleted_successfully'));
    }

    /**
     * Download PDF file
     */
    public function downloadPdf(MinisterialYearsQuestion $ministerialQuestion)
    {
        if (!$ministerialQuestion->pdf) {
            abort(404);
        }

        $filePath = base_path('assets/admin/uploads/pdfs/' . $ministerialQuestion->pdf);
        
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }

    /**
     * Get child categories by parent ID (AJAX)
     */
    public function getChildCategories($parentId)
    {
        $categories = Category::where('parent_id', $parentId)
            ->active()
            ->ordered()
            ->get(['id', 'name_ar', 'name_en']);

        return response()->json($categories);
    }
}