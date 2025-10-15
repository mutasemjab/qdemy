<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use App\Models\BankQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Traits\SubjectCategoryTrait;
use Illuminate\Support\Facades\Log;

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
                // Add logging to debug
                Log::info('Processing PDF upload', [
                    'bank_id' => $bank->id,
                    'file_name' => $request->file('pdf')->getClientOriginalName(),
                    'file_size' => $request->file('pdf')->getSize()
                ]);
                
                $upload_response = BunnyHelper()->upload(
                    $request->file('pdf'), 
                    BankQuestion::BUNNY_PATH . '/' . $bank->id
                );
                
                // Check if response is valid
                if (!$upload_response) {
                    Log::error('Upload response is null');
                    DB::rollback();
                    return redirect()->route('bank-questions.index')
                        ->with('error', __('messages.file_upload_failed'));
                }
                
                $upload_response_data = $upload_response->getData();
                
                Log::info('Upload response received', [
                    'success' => $upload_response_data->success ?? false,
                    'file_path' => $upload_response_data->file_path ?? null,
                    'response_data' => json_encode($upload_response_data)
                ]);
                
                if(isset($upload_response_data->success) && $upload_response_data->success && isset($upload_response_data->file_path)) {
                    $bank->pdf = $upload_response_data->file_path;
                    $bank->pdf_size = $upload_response_data->data->file_size ?? null;
                    
                    // Use custom display name if provided, otherwise use original file name
                    if (!$bank->display_name) {
                        $bank->display_name = $upload_response_data->data->original_name ?? 'Uploaded PDF';
                    }
                    
                    if($bank->save()){
                        // Verify the file actually exists after upload
                        if (BunnyHelper()->exists($bank->pdf)) {
                            DB::commit();
                            Log::info('Bank question created successfully', ['bank_id' => $bank->id, 'pdf_path' => $bank->pdf]);
                            return redirect()->route('bank-questions.index')
                                ->with('success', __('messages.bank_question_created_successfully'));
                        } else {
                            Log::error('File upload succeeded but file verification failed', ['pdf_path' => $bank->pdf]);
                            DB::rollback();
                            return redirect()->route('bank-questions.index')
                                ->with('error', __('messages.file_upload_verification_failed'));
                        }
                    } else {
                        Log::error('Failed to save bank question after upload');
                        DB::rollback();
                        return redirect()->route('bank-questions.index')
                            ->with('error', __('messages.some_thing_wont_wrong'));
                    }
                } else {
                    $error_message = $upload_response_data->message ?? __('messages.file_upload_failed');
                    Log::error('Upload failed', ['error_message' => $error_message]);
                    DB::rollback();
                    return redirect()->route('bank-questions.index')
                        ->with('error', $error_message);
                }
            } else {
                Log::error('No PDF file found in request');
                DB::rollback();
                return redirect()->route('bank-questions.index')
                    ->with('error', __('messages.no_file_uploaded'));
            }
        } catch (\Exception $e) {
            Log::error('Exception in store method', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();
            return redirect()->route('bank-questions.index')
                ->with('error', $e->getMessage());
        }
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
            Log::info('Starting bank question update', [
                'bank_question_id' => $bankQuestion->id,
                'has_new_file' => $request->hasFile('pdf')
            ]);

            $oldFile = $bankQuestion->pdf; // Store old file path before update

            if ($request->hasFile('pdf')) {
                Log::info('Processing new PDF upload for update', [
                    'bank_question_id' => $bankQuestion->id,
                    'file_name' => $request->file('pdf')->getClientOriginalName(),
                    'file_size' => $request->file('pdf')->getSize()
                ]);

                $upload_response = BunnyHelper()->upload(
                    $request->file('pdf'), 
                    BankQuestion::BUNNY_PATH . '/' . $bankQuestion->id
                );

                if (!$upload_response) {
                    Log::error('Upload response is null during update');
                    DB::rollback();
                    return redirect()->route('bank-questions.index')
                        ->with('error', __('messages.file_upload_failed'));
                }

                $upload_response_data = $upload_response->getData();
                
                Log::info('Update upload response received', [
                    'bank_question_id' => $bankQuestion->id,
                    'success' => $upload_response_data->success ?? false,
                    'file_path' => $upload_response_data->file_path ?? null
                ]);

                if (isset($upload_response_data->success) && $upload_response_data->success && isset($upload_response_data->file_path)) {
                    $data['pdf'] = $upload_response_data->file_path;
                    $data['pdf_size'] = $upload_response_data->data->file_size ?? null;

                    // Keep existing display name if not provided in form and no new file name
                    if (!$data['display_name']) {
                        $data['display_name'] = $upload_response_data->data->original_name ?? $bankQuestion->display_name;
                    }

                    // Verify the new file exists before proceeding
                    if (!BunnyHelper()->exists($data['pdf'])) {
                        Log::error('New file upload succeeded but verification failed during update', [
                            'bank_question_id' => $bankQuestion->id,
                            'pdf_path' => $data['pdf']
                        ]);
                        DB::rollback();
                        return redirect()->route('bank-questions.index')
                            ->with('error', __('messages.file_upload_verification_failed'));
                    }

                    Log::info('New file uploaded and verified successfully', [
                        'bank_question_id' => $bankQuestion->id,
                        'new_file_path' => $data['pdf']
                    ]);

                } else {
                    $error_message = $upload_response_data->message ?? __('messages.file_upload_failed');
                    Log::error('Upload failed during update', [
                        'bank_question_id' => $bankQuestion->id,
                        'error_message' => $error_message
                    ]);
                    DB::rollback();
                    return redirect()->route('bank-questions.index')
                        ->with('error', $error_message);
                }
            } else {
                // If no new file uploaded, keep existing display_name if not provided
                if (!$data['display_name']) {
                    $data['display_name'] = $bankQuestion->display_name;
                }
            }

            // Update the bank question
            $updateResult = $bankQuestion->update($data);

            if (!$updateResult) {
                Log::error('Failed to update bank question in database', [
                    'bank_question_id' => $bankQuestion->id
                ]);
                DB::rollback();
                return redirect()->route('bank-questions.index')
                    ->with('error', __('messages.some_thing_wont_wrong'));
            }

            // Delete old file only after successful update and if new file was uploaded
            if ($request->hasFile('pdf') && $oldFile && isset($data['pdf']) && $oldFile !== $data['pdf']) {
                try {
                    $deleteResult = BunnyHelper()->delete($oldFile);
                    Log::info('Old file deletion result', [
                        'bank_question_id' => $bankQuestion->id,
                        'old_file_path' => $oldFile,
                        'delete_result' => $deleteResult
                    ]);
                } catch (\Exception $deleteException) {
                    // Log the error but don't fail the update
                    Log::warning('Failed to delete old file during update', [
                        'bank_question_id' => $bankQuestion->id,
                        'old_file_path' => $oldFile,
                        'error' => $deleteException->getMessage()
                    ]);
                }
            }

            DB::commit();
            Log::info('Bank question updated successfully', ['bank_question_id' => $bankQuestion->id]);

            return redirect()->route('bank-questions.index')
                ->with('success', __('messages.bank_question_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Exception during bank question update', [
                'bank_question_id' => $bankQuestion->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();
            
            return redirect()->route('bank-questions.index')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(BankQuestion $bankQuestion)
    {
        DB::beginTransaction();
        try {
            Log::info('Starting bank question deletion', [
                'bank_question_id' => $bankQuestion->id,
                'has_pdf' => !empty($bankQuestion->pdf),
                'pdf_path' => $bankQuestion->pdf
            ]);

            $oldFile = $bankQuestion->pdf;

            // Delete the bank question from database first
            $deleteResult = $bankQuestion->delete();

            if (!$deleteResult) {
                Log::error('Failed to delete bank question from database', [
                    'bank_question_id' => $bankQuestion->id
                ]);
                DB::rollback();
                return redirect()->route('bank-questions.index')
                    ->with('error', __('messages.some_thing_wont_wrong'));
            }

            // Delete PDF file after successful database deletion
            if ($oldFile) {
                try {
                    // Check if file exists before attempting deletion
                    if (BunnyHelper()->exists($oldFile)) {
                        $fileDeleteResult = BunnyHelper()->delete($oldFile);
                        Log::info('PDF file deletion result', [
                            'bank_question_id' => $bankQuestion->id,
                            'file_path' => $oldFile,
                            'delete_result' => $fileDeleteResult
                        ]);
                    } else {
                        Log::info('PDF file does not exist, skipping deletion', [
                            'bank_question_id' => $bankQuestion->id,
                            'file_path' => $oldFile
                        ]);
                    }
                } catch (\Exception $fileDeleteException) {
                    // Log the error but don't fail the deletion since DB record is already deleted
                    Log::warning('Failed to delete PDF file during bank question deletion', [
                        'bank_question_id' => $bankQuestion->id,
                        'file_path' => $oldFile,
                        'error' => $fileDeleteException->getMessage()
                    ]);
                }
            }

            // Also try to delete the entire folder for this bank question
            try {
                $folderPath = BankQuestion::BUNNY_PATH . '/' . $bankQuestion->id;
                BunnyHelper()->deleteFiles($folderPath);
                Log::info('Bank question folder cleaned up', [
                    'bank_question_id' => $bankQuestion->id,
                    'folder_path' => $folderPath
                ]);
            } catch (\Exception $folderDeleteException) {
                Log::warning('Failed to clean up bank question folder', [
                    'bank_question_id' => $bankQuestion->id,
                    'error' => $folderDeleteException->getMessage()
                ]);
            }

            DB::commit();
            Log::info('Bank question deleted successfully', ['bank_question_id' => $bankQuestion->id]);

            return redirect()->route('bank-questions.index')
                ->with('success', __('messages.bank_question_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Exception during bank question deletion', [
                'bank_question_id' => $bankQuestion->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();

            return redirect()->route('bank-questions.index')
                ->with('error', $e->getMessage());
        }
    }
 

    /**
     * Download PDF file
     */
   public function downloadPdf(BankQuestion $bankQuestion)
    {
        if (!$bankQuestion->pdf || !$bankQuestion->pdfExists()) {
            abort(404, __('messages.file_not_found'));
        }
        
        try {
            // Get file content from Bunny storage
            $fileContent = BunnyHelper()->getFileContent($bankQuestion->pdf);
            
            if (!$fileContent) {
                Log::error('Failed to get file content from Bunny', ['pdf_path' => $bankQuestion->pdf]);
                abort(404, __('messages.file_not_found'));
            }
            
            // Increment download count
            $bankQuestion->increment('download_count');
            
            // Prepare filename
            $filename = $bankQuestion->display_name ?? 'document.pdf';
            if (!str_ends_with(strtolower($filename), '.pdf')) {
                $filename .= '.pdf';
            }
            
            // Return file as download response
            return response($fileContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            Log::error('Error downloading PDF', [
                'bank_question_id' => $bankQuestion->id,
                'pdf_path' => $bankQuestion->pdf,
                'error' => $e->getMessage()
            ]);
            abort(500, __('messages.download_error'));
        }
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