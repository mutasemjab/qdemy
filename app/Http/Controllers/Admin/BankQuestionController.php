<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use App\Models\BankQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Traits\SubjectCategoryTrait;


class BankQuestionController extends Controller
{
    use SubjectCategoryTrait;

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
        $bankQuestions = BankQuestion::with(['category', 'subject'])
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
            
        $subjects = Subject::active()
            ->ordered()
            ->get();
            
        return view('admin.bank_questions.create', compact('parentCategories', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_category' => 'required|exists:categories,id',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'display_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'subject_id' => $request->subject_id,
            'display_name' => $request->display_name,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'download_count' => 0,
        ];

        DB::beginTransaction();
        try {
            $bank = BankQuestion::create($data);
            
            if ($request->hasFile('pdf')) {
                $upload_response = BunnyHelper()->upload($request->pdf, BankQuestion::BUNNY_PATH . '/' . $bank->id);
                $upload_response_data = $upload_response->getData();
                
                if($upload_response_data->success && $upload_response_data->file_path){
                    $bank->pdf = $upload_response_data->file_path;
                    $bank->pdf_size = $upload_response_data->data?->file_size;
                    
                    // Use custom display name if provided, otherwise use original file name
                    if (!$bank->display_name) {
                        $bank->display_name = $upload_response_data->data?->original_name;
                    }
                    
                    if($bank->save()){
                        DB::commit();
                        $message_status = 'success';
                        $message = __('messages.bank_question_created_successfully');
                    } else {
                        DB::rollback();
                        $message = __('messages.some_thing_wont_wrong');
                        $message_status = 'error';
                    }
                } else {
                    DB::rollback();
                    $message = __('messages.file_upload_failed');
                    $message_status = 'error';
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            $message_status = 'error';
        }

        return redirect()->route('bank-questions.index')
            ->with($message_status, $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(BankQuestion $bankQuestion)
    {
        $bankQuestion->load(['category', 'subject']);
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

        $subjects = Subject::active()
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

        return view('admin.bank_questions.edit', compact(
            'bankQuestion', 
            'parentCategories', 
            'subjects',
            'selectedParent', 
            'selectedChild'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankQuestion $bankQuestion)
    {
        $request->validate([
            'parent_category' => 'required|exists:categories,id',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'display_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'subject_id' => $request->subject_id,
            'display_name' => $request->display_name,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ];

        DB::beginTransaction();
        try {
            if ($request->hasFile('pdf')) {
                $upload_response = BunnyHelper()->upload($request->pdf, BankQuestion::BUNNY_PATH . '/'. $bankQuestion->id);
                $upload_response_data = $upload_response->getData();
                
                if($upload_response_data->success && $upload_response_data->file_path){
                    $data['pdf'] = $upload_response_data->file_path;
                    $data['pdf_size'] = $upload_response_data->data?->file_size;
                    
                    // Keep existing display name if not provided in form and no new file name
                    if (!$data['display_name']) {
                        $data['display_name'] = $upload_response_data->data?->original_name;
                    }
                    
                    $oldFile = $bankQuestion->pdf;
                    if($oldFile){ 
                        BunnyHelper()->delete($oldFile); 
                    }
                }
            }
            
            $bankQuestion->update($data);
            DB::commit();
            $message_status = 'success';
            $message = __('messages.bank_question_updated_successfully');
            
        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            $message_status = 'error';
        }

        return redirect()->route('bank-questions.index')
            ->with($message_status, $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankQuestion $bankQuestion)
    {
        DB::beginTransaction();
        try {
            // Delete PDF file
            if ($bankQuestion->pdf) {
                $oldFile = $bankQuestion->pdf;
                if($oldFile){ 
                    BunnyHelper()->delete($oldFile); 
                }
            }
            
            $bankQuestion->delete();
            DB::commit();
            
            $message_status = 'success';
            $message = __('messages.bank_question_deleted_successfully');
            
        } catch (\Exception $e) {
            DB::rollback();
            $message_status = 'error';
            $message = $e->getMessage();
        }

        return redirect()->route('bank-questions.index')
            ->with($message_status, $message);
    }

    /**
     * Download PDF file
     */
    public function downloadPdf(BankQuestion $bankQuestion)
    {
        if (!$bankQuestion->pdf || !$bankQuestion->pdfExists()) {
            abort(404, __('messages.file_not_found'));
        }
        
        // Increment download count
        $bankQuestion->increment('download_count');
        
        return response()->download(
            $bankQuestion->pdf_path, 
            $bankQuestion->display_name ?? 'document.pdf'
        );
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

    /**
     * Get subjects by category ID (AJAX)
     */
    public function getSubjectsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        if (!$categoryId) {
            return response()->json([]);
        }

        try {
            // Call the trait method that returns formatted data for API
            $subjects = $this->getSubjectsByCategoryForApi($categoryId);
            
            return response()->json($subjects->toArray());
            
        } catch (\Exception $e) {
            \Log::error('Error in getSubjectsByCategory controller: ' . $e->getMessage());
            \Log::error('Category ID: ' . $categoryId);
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([]);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(BankQuestion $bankQuestion)
    {
        $bankQuestion->update([
            'is_active' => !$bankQuestion->is_active
        ]);

        $status = $bankQuestion->is_active ? __('messages.activated') : __('messages.deactivated');
        
        return response()->json([
            'success' => true,
            'message' => __('messages.bank_question') . ' ' . $status,
            'is_active' => $bankQuestion->is_active
        ]);
    }
}