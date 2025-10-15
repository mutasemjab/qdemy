<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\MinisterialYearsQuestion;
use App\Models\Subject;
use App\Traits\SubjectCategoryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MinisterialYearsQuestionController extends Controller
{
    use SubjectCategoryTrait;
    
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
        $ministerialQuestions = MinisterialYearsQuestion::with(['category', 'subject'])
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
            
        $subjects = Subject::active()
            ->ordered()
            ->get();
            
        return view('admin.ministerial_questions.create', compact('parentCategories', 'subjects'));
    }

    
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
            $ministerialYear = MinisterialYearsQuestion::create($data);
            
            if ($request->hasFile('pdf')) {
                // Add logging to debug
                Log::info('Processing PDF upload for ministerial question', [
                    'ministerial_id' => $ministerialYear->id,
                    'file_name' => $request->file('pdf')->getClientOriginalName(),
                    'file_size' => $request->file('pdf')->getSize()
                ]);
                
                $upload_response = BunnyHelper()->upload(
                    $request->file('pdf'), 
                    MinisterialYearsQuestion::BUNNY_PATH . '/' . $ministerialYear->id
                );
                
                // Check if response is valid
                if (!$upload_response) {
                    Log::error('Upload response is null for ministerial question');
                    DB::rollback();
                    return redirect()->route('ministerial-questions.index')
                        ->with('error', __('messages.file_upload_failed'));
                }
                
                $upload_response_data = $upload_response->getData();
                
                Log::info('Ministerial upload response received', [
                    'ministerial_id' => $ministerialYear->id,
                    'success' => $upload_response_data->success ?? false,
                    'file_path' => $upload_response_data->file_path ?? null,
                    'response_data' => json_encode($upload_response_data)
                ]);
                
                if(isset($upload_response_data->success) && $upload_response_data->success && isset($upload_response_data->file_path)) {
                    $ministerialYear->pdf = $upload_response_data->file_path;
                    $ministerialYear->pdf_size = $upload_response_data->data->file_size ?? null;
                    
                    // Use custom display name if provided, otherwise use original file name
                    if (!$ministerialYear->display_name) {
                        $ministerialYear->display_name = $upload_response_data->data->original_name ?? 'Uploaded PDF';
                    }
                    
                    if($ministerialYear->save()){
                        // Verify the file actually exists after upload
                        if (BunnyHelper()->exists($ministerialYear->pdf)) {
                            DB::commit();
                            Log::info('Ministerial question created successfully', [
                                'ministerial_id' => $ministerialYear->id, 
                                'pdf_path' => $ministerialYear->pdf
                            ]);
                            return redirect()->route('ministerial-questions.index')
                                ->with('success', __('messages.ministerial_question_created_successfully'));
                        } else {
                            Log::error('File upload succeeded but file verification failed for ministerial', [
                                'pdf_path' => $ministerialYear->pdf
                            ]);
                            DB::rollback();
                            return redirect()->route('ministerial-questions.index')
                                ->with('error', __('messages.file_upload_verification_failed'));
                        }
                    } else {
                        Log::error('Failed to save ministerial question after upload');
                        DB::rollback();
                        return redirect()->route('ministerial-questions.index')
                            ->with('error', __('messages.some_thing_wont_wrong'));
                    }
                } else {
                    $error_message = $upload_response_data->message ?? __('messages.file_upload_failed');
                    Log::error('Ministerial upload failed', ['error_message' => $error_message]);
                    DB::rollback();
                    return redirect()->route('ministerial-questions.index')
                        ->with('error', $error_message);
                }
            } else {
                Log::error('No PDF file found in ministerial request');
                DB::rollback();
                return redirect()->route('ministerial-questions.index')
                    ->with('error', __('messages.no_file_uploaded'));
            }
        } catch (\Exception $e) {
            Log::error('Exception in ministerial store method', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();
            return redirect()->route('ministerial-questions.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MinisterialYearsQuestion $ministerialQuestion)
    {
        $ministerialQuestion->load(['category', 'subject']);
        return view('admin.ministerial_questions.show', compact('ministerialQuestion'));
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

        $subjects = Subject::active()
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

        return view('admin.ministerial_questions.edit', compact(
            'ministerialQuestion', 
            'parentCategories', 
            'subjects',
            'selectedParent', 
            'selectedChild'
        ));
    }


    public function update(Request $request, MinisterialYearsQuestion $ministerialQuestion)
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
            Log::info('Starting ministerial question update', [
                'ministerial_id' => $ministerialQuestion->id,
                'has_new_file' => $request->hasFile('pdf')
            ]);

            $oldFile = $ministerialQuestion->pdf; // Store old file path before update

            if ($request->hasFile('pdf')) {
                Log::info('Processing new PDF upload for ministerial update', [
                    'ministerial_id' => $ministerialQuestion->id,
                    'file_name' => $request->file('pdf')->getClientOriginalName(),
                    'file_size' => $request->file('pdf')->getSize()
                ]);

                $upload_response = BunnyHelper()->upload(
                    $request->file('pdf'), 
                    MinisterialYearsQuestion::BUNNY_PATH . '/' . $ministerialQuestion->id
                );

                if (!$upload_response) {
                    Log::error('Upload response is null during ministerial update');
                    DB::rollback();
                    return redirect()->route('ministerial-questions.index')
                        ->with('error', __('messages.file_upload_failed'));
                }

                $upload_response_data = $upload_response->getData();
                
                Log::info('Ministerial update upload response received', [
                    'ministerial_id' => $ministerialQuestion->id,
                    'success' => $upload_response_data->success ?? false,
                    'file_path' => $upload_response_data->file_path ?? null
                ]);

                if (isset($upload_response_data->success) && $upload_response_data->success && isset($upload_response_data->file_path)) {
                    $data['pdf'] = $upload_response_data->file_path;
                    $data['pdf_size'] = $upload_response_data->data->file_size ?? null;

                    // Keep existing display name if not provided in form and no new file name
                    if (!$data['display_name']) {
                        $data['display_name'] = $upload_response_data->data->original_name ?? $ministerialQuestion->display_name;
                    }

                    // Verify the new file exists before proceeding
                    if (!BunnyHelper()->exists($data['pdf'])) {
                        Log::error('New file upload succeeded but verification failed during ministerial update', [
                            'ministerial_id' => $ministerialQuestion->id,
                            'pdf_path' => $data['pdf']
                        ]);
                        DB::rollback();
                        return redirect()->route('ministerial-questions.index')
                            ->with('error', __('messages.file_upload_verification_failed'));
                    }

                    Log::info('New ministerial file uploaded and verified successfully', [
                        'ministerial_id' => $ministerialQuestion->id,
                        'new_file_path' => $data['pdf']
                    ]);

                } else {
                    $error_message = $upload_response_data->message ?? __('messages.file_upload_failed');
                    Log::error('Upload failed during ministerial update', [
                        'ministerial_id' => $ministerialQuestion->id,
                        'error_message' => $error_message
                    ]);
                    DB::rollback();
                    return redirect()->route('ministerial-questions.index')
                        ->with('error', $error_message);
                }
            } else {
                // If no new file uploaded, keep existing display_name if not provided
                if (!$data['display_name']) {
                    $data['display_name'] = $ministerialQuestion->display_name;
                }
            }

            // Update the ministerial question
            $updateResult = $ministerialQuestion->update($data);

            if (!$updateResult) {
                Log::error('Failed to update ministerial question in database', [
                    'ministerial_id' => $ministerialQuestion->id
                ]);
                DB::rollback();
                return redirect()->route('ministerial-questions.index')
                    ->with('error', __('messages.some_thing_wont_wrong'));
            }

            // Delete old file only after successful update and if new file was uploaded
            if ($request->hasFile('pdf') && $oldFile && isset($data['pdf']) && $oldFile !== $data['pdf']) {
                try {
                    $deleteResult = BunnyHelper()->delete($oldFile);
                    Log::info('Old ministerial file deletion result', [
                        'ministerial_id' => $ministerialQuestion->id,
                        'old_file_path' => $oldFile,
                        'delete_result' => $deleteResult
                    ]);
                } catch (\Exception $deleteException) {
                    // Log the error but don't fail the update
                    Log::warning('Failed to delete old ministerial file during update', [
                        'ministerial_id' => $ministerialQuestion->id,
                        'old_file_path' => $oldFile,
                        'error' => $deleteException->getMessage()
                    ]);
                }
            }

            DB::commit();
            Log::info('Ministerial question updated successfully', ['ministerial_id' => $ministerialQuestion->id]);

            return redirect()->route('ministerial-questions.index')
                ->with('success', __('messages.ministerial_question_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Exception during ministerial question update', [
                'ministerial_id' => $ministerialQuestion->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();
            
            return redirect()->route('ministerial-questions.index')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(MinisterialYearsQuestion $ministerialQuestion)
    {
        DB::beginTransaction();
        try {
            Log::info('Starting ministerial question deletion', [
                'ministerial_id' => $ministerialQuestion->id,
                'has_pdf' => !empty($ministerialQuestion->pdf),
                'pdf_path' => $ministerialQuestion->pdf
            ]);

            $oldFile = $ministerialQuestion->pdf;

            // Delete the ministerial question from database first
            $deleteResult = $ministerialQuestion->delete();

            if (!$deleteResult) {
                Log::error('Failed to delete ministerial question from database', [
                    'ministerial_id' => $ministerialQuestion->id
                ]);
                DB::rollback();
                return redirect()->route('ministerial-questions.index')
                    ->with('error', __('messages.some_thing_wont_wrong'));
            }

            // Delete PDF file after successful database deletion
            if ($oldFile) {
                try {
                    // Check if file exists before attempting deletion
                    if (BunnyHelper()->exists($oldFile)) {
                        $fileDeleteResult = BunnyHelper()->delete($oldFile);
                        Log::info('Ministerial PDF file deletion result', [
                            'ministerial_id' => $ministerialQuestion->id,
                            'file_path' => $oldFile,
                            'delete_result' => $fileDeleteResult
                        ]);
                    } else {
                        Log::info('Ministerial PDF file does not exist, skipping deletion', [
                            'ministerial_id' => $ministerialQuestion->id,
                            'file_path' => $oldFile
                        ]);
                    }
                } catch (\Exception $fileDeleteException) {
                    // Log the error but don't fail the deletion since DB record is already deleted
                    Log::warning('Failed to delete ministerial PDF file during deletion', [
                        'ministerial_id' => $ministerialQuestion->id,
                        'file_path' => $oldFile,
                        'error' => $fileDeleteException->getMessage()
                    ]);
                }
            }

            // Also try to delete the entire folder for this ministerial question
            try {
                $folderPath = MinisterialYearsQuestion::BUNNY_PATH . '/' . $ministerialQuestion->id;
                BunnyHelper()->deleteFiles($folderPath);
                Log::info('Ministerial question folder cleaned up', [
                    'ministerial_id' => $ministerialQuestion->id,
                    'folder_path' => $folderPath
                ]);
            } catch (\Exception $folderDeleteException) {
                Log::warning('Failed to clean up ministerial question folder', [
                    'ministerial_id' => $ministerialQuestion->id,
                    'error' => $folderDeleteException->getMessage()
                ]);
            }

            DB::commit();
            Log::info('Ministerial question deleted successfully', ['ministerial_id' => $ministerialQuestion->id]);

            return redirect()->route('ministerial-questions.index')
                ->with('success', __('messages.ministerial_question_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Exception during ministerial question deletion', [
                'ministerial_id' => $ministerialQuestion->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();

            return redirect()->route('ministerial-questions.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Download PDF file
     */
    public function downloadPdf(MinisterialYearsQuestion $ministerialQuestion)
    {
        if (!$ministerialQuestion->pdf || !$ministerialQuestion->pdfExists()) {
            abort(404, __('messages.file_not_found'));
        }
        
        try {
            // Get file content from Bunny storage
            $fileContent = BunnyHelper()->getFileContent($ministerialQuestion->pdf);
            
            if (!$fileContent) {
                Log::error('Failed to get ministerial file content from Bunny', [
                    'pdf_path' => $ministerialQuestion->pdf
                ]);
                abort(404, __('messages.file_not_found'));
            }
            
            // Increment download count
            $ministerialQuestion->increment('download_count');
            
            // Prepare filename
            $filename = $ministerialQuestion->display_name ?? 'ministerial-question.pdf';
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
            Log::error('Error downloading ministerial PDF', [
                'ministerial_id' => $ministerialQuestion->id,
                'pdf_path' => $ministerialQuestion->pdf,
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
    public function toggleStatus(MinisterialYearsQuestion $ministerialQuestion)
    {
        $ministerialQuestion->update([
            'is_active' => !$ministerialQuestion->is_active
        ]);

        $status = $ministerialQuestion->is_active ? __('messages.activated') : __('messages.deactivated');
        
        return response()->json([
            'success' => true,
            'message' => __('messages.ministerial_question') . ' ' . $status,
            'is_active' => $ministerialQuestion->is_active
        ]);
    }
}