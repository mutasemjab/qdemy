<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\BankQuestion;
use App\Models\Category;
use Illuminate\Http\Request;

class BankQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:bank-question-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:bank-question-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:bank-question-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:bank-question-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankQuestions = BankQuestion::with('category.parent')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.bank_questions.index', compact('bankQuestions'));
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
        return view('admin.bank_questions.create', compact('parentCategories'));
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

        BankQuestion::create($data);

        return redirect()->route('bank-questions.index')
            ->with('success', __('messages.bank_question_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BankQuestion $bankQuestion)
    {
        $bankQuestion->load('category.parent');
        return view('admin.bank_questions.show', compact('bankQuestion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankQuestion $bankQuestion)
    {
        $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();

        // Get the parent and child category for the current bank question
        $selectedParent = null;
        $selectedChild = null;
        
        if ($bankQuestion->category) {
            if ($bankQuestion->category->parent_id) {
                $selectedParent = $bankQuestion->category->parent_id;
                $selectedChild = $bankQuestion->category_id;
            } else {
                $selectedParent = $bankQuestion->category_id;
            }
        }

        return view('admin.bank_questions.edit', compact('bankQuestion', 'parentCategories', 'selectedParent', 'selectedChild'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankQuestion $bankQuestion)
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
            if ($bankQuestion->pdf) {
                $filePath = base_path('assets/admin/uploads/pdfs/' . $bankQuestion->pdf);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['pdf'] = uploadImage('assets/admin/uploads/pdfs', $request->pdf);
        } else {
            $data['pdf'] = $bankQuestion->pdf;
        }

        $bankQuestion->update($data);

        return redirect()->route('bank-questions.index')
            ->with('success', __('messages.bank_question_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankQuestion $bankQuestion)
    {
        // Delete PDF file
        if ($bankQuestion->pdf) {
            $filePath = base_path('assets/admin/uploads/pdfs/' . $bankQuestion->pdf);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $bankQuestion->delete();

        return redirect()->route('bank-questions.index')
            ->with('success', __('messages.bank_question_deleted_successfully'));
    }

    /**
     * Download PDF file
     */
    public function downloadPdf(BankQuestion $bankQuestion)
    {
        if (!$bankQuestion->pdf) {
            abort(404);
        }

        $filePath = base_path('assets/admin/uploads/pdfs/' . $bankQuestion->pdf);
        
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