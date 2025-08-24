<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use App\Models\BankQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

        DB::beginTransaction();
        try {
            $bank = BankQuestion::create($data);
            if ($request->hasFile('pdf')) {
                $upload_response = BunnyHelper()->upload($request->pdf, BankQuestion::BUNNY_PATH . '/' .$bank->id);
                $upload_response_data = $upload_response->getData();
                // dd($upload_response_data);
                if($upload_response_data->success && $upload_response_data->file_path){
                    $bank->pdf          = $upload_response_data->file_path;
                    $bank->pdf_size     = $upload_response_data->data?->file_size;
                    $bank->display_name = $upload_response_data->data?->original_name;
                    if($bank->save()){
                        DB::commit();
                    }else{
                        $error          = __('messages.some_thing_wont_wrong');
                        $message_status = 'error';
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $error          = $e->getMessage();
            $message_status = 'error';
        }

        return redirect()->route('bank-questions.index')
            ->with($message_status ?? 'success', $error ?? __('messages.bank_question_created_successfully'));
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
            'category_id'     => 'required|exists:categories,id',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $data = [
            'category_id' => $request->category_id,
        ];

        DB::beginTransaction();
        try {
            if ($request->hasFile('pdf')) {
                $upload_response = BunnyHelper()->upload($request->pdf,BankQuestion::BUNNY_PATH . '/'. $bankQuestion->id);
                $upload_response_data = $upload_response->getData();
                if($upload_response_data->success && $upload_response_data->file_path){
                    $data['pdf'] = $upload_response_data->file_path;
                    $data['pdf_size']     = $upload_response_data->data?->file_size;
                    $data['display_name'] = $upload_response_data->data?->original_name;
                    $oldFile     = $bankQuestion->pdf;
                    if($oldFile){ BunnyHelper()->delete($oldFile); }
                }
            }else {
                unset($data['pdf']);
            }
            $bankQuestion->update($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $error          = $e->getMessage();
            $message_status = 'error';
        }

        return redirect()->route('bank-questions.index')
            ->with($error ?? 'success',$message_status ?? __('messages.bank_question_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankQuestion $bankQuestion)
    {
        // Delete PDF file
        if ($bankQuestion->pdf) {
            $oldFile     = $bankQuestion->pdf;
            if($oldFile){ BunnyHelper()->delete($oldFile); }
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
       return response()->download($bankQuestion->pdf_path);
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