<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use App\Models\BootCampQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Traits\SubjectCategoryTrait;
use Illuminate\Support\Facades\Log;

class BootCampQuestionController extends Controller
{
    use SubjectCategoryTrait;

    public function __construct()
    {
        $this->middleware('permission:boot-camp-question-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:boot-camp-question-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:boot-camp-question-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:boot-camp-question-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bootCampQuestions = BootCampQuestion::with(['category', 'subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.boot_camp_questions.index', compact('bootCampQuestions'));
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

        return view('admin.boot_camp_questions.create', compact('parentCategories', 'subjects'));
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
            'pdf' => 'required|file|mimes:pdf', // 10MB max
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
            $bootCamp = BootCampQuestion::create($data);

            if ($request->hasFile('pdf')) {
                // Add logging to debug
                Log::info('Processing PDF upload', [
                    'boot_camp_id' => $bootCamp->id,
                    'file_name' => $request->file('pdf')->getClientOriginalName(),
                    'file_size' => $request->file('pdf')->getSize()
                ]);

                $upload_response = BunnyHelper()->upload(
                    $request->file('pdf'),
                    BootCampQuestion::BUNNY_PATH . '/' . $bootCamp->id
                );

                // Check if response is valid
                if (!$upload_response) {
                    Log::error('Upload response is null');
                    DB::rollback();
                    return redirect()->route('admin.boot-camp-questions.index')
                        ->with('error', __('messages.file_upload_failed'));
                }

                $upload_response_data = $upload_response->getData();

                Log::info('Upload response received', [
                    'success' => $upload_response_data->success ?? false,
                    'file_path' => $upload_response_data->file_path ?? null,
                    'response_data' => json_encode($upload_response_data)
                ]);

                if(isset($upload_response_data->success) && $upload_response_data->success && isset($upload_response_data->file_path)) {
                    $bootCamp->pdf = $upload_response_data->file_path;
                    $bootCamp->pdf_size = $upload_response_data->data->file_size ?? null;

                    // Use custom display name if provided, otherwise use original file name
                    if (!$bootCamp->display_name) {
                        $bootCamp->display_name = $upload_response_data->data->original_name ?? 'Uploaded PDF';
                    }

                    if($bootCamp->save()){
                        // Verify the file actually exists after upload
                        if (BunnyHelper()->exists($bootCamp->pdf)) {
                            DB::commit();
                            Log::info('Boot camp question created successfully', ['boot_camp_id' => $bootCamp->id, 'pdf_path' => $bootCamp->pdf]);
                            return redirect()->route('admin.boot-camp-questions.index')
                                ->with('success', __('messages.boot_camp_question_created_successfully'));
                        } else {
                            Log::error('File upload succeeded but file verification failed', ['pdf_path' => $bootCamp->pdf]);
                            DB::rollback();
                            return redirect()->route('admin.boot-camp-questions.index')
                                ->with('error', __('messages.file_upload_verification_failed'));
                        }
                    } else {
                        Log::error('Failed to save boot camp question after upload');
                        DB::rollback();
                        return redirect()->route('admin.boot-camp-questions.index')
                            ->with('error', __('messages.some_thing_wont_wrong'));
                    }
                } else {
                    $error_message = $upload_response_data->message ?? __('messages.file_upload_failed');
                    Log::error('Upload failed', ['error_message' => $error_message]);
                    DB::rollback();
                    return redirect()->route('admin.boot-camp-questions.index')
                        ->with('error', $error_message);
                }
            } else {
                Log::error('No PDF file found in request');
                DB::rollback();
                return redirect()->route('admin.boot-camp-questions.index')
                    ->with('error', __('messages.no_file_uploaded'));
            }
        } catch (\Exception $e) {
            Log::error('Exception in store method', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();
            return redirect()->route('admin.boot-camp-questions.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BootCampQuestion $bootCampQuestion)
    {
        $bootCampQuestion->load(['category', 'subject']);
        return view('admin.boot_camp_questions.show', compact('bootCampQuestion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BootCampQuestion $bootCampQuestion)
    {
        $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();

        $subjects = Subject::active()
            ->ordered()
            ->get();

        // Get the parent and child category for the current boot camp question
        $selectedParent = null;
        $selectedChild = null;

        if ($bootCampQuestion->category) {
            if ($bootCampQuestion->category->parent_id) {
                $selectedParent = $bootCampQuestion->category->parent_id;
                $selectedChild = $bootCampQuestion->category_id;
            } else {
                $selectedParent = $bootCampQuestion->category_id;
            }
        }

        return view('admin.boot_camp_questions.edit', compact(
            'bootCampQuestion',
            'parentCategories',
            'subjects',
            'selectedParent',
            'selectedChild'
        ));
    }

    public function update(Request $request, BootCampQuestion $bootCampQuestion)
    {
        $request->validate([
            'parent_category' => 'required|exists:categories,id',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'pdf' => 'nullable|file|mimes:pdf', // 10MB max
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
            Log::info('Starting boot camp question update', [
                'boot_camp_question_id' => $bootCampQuestion->id,
                'has_new_file' => $request->hasFile('pdf')
            ]);

            $oldFile = $bootCampQuestion->pdf; // Store old file path before update

            if ($request->hasFile('pdf')) {
                Log::info('Processing new PDF upload for update', [
                    'boot_camp_question_id' => $bootCampQuestion->id,
                    'file_name' => $request->file('pdf')->getClientOriginalName(),
                    'file_size' => $request->file('pdf')->getSize()
                ]);

                $upload_response = BunnyHelper()->upload(
                    $request->file('pdf'),
                    BootCampQuestion::BUNNY_PATH . '/' . $bootCampQuestion->id
                );

                if (!$upload_response) {
                    Log::error('Upload response is null during update');
                    DB::rollback();
                    return redirect()->route('admin.boot-camp-questions.index')
                        ->with('error', __('messages.file_upload_failed'));
                }

                $upload_response_data = $upload_response->getData();

                Log::info('Update upload response received', [
                    'boot_camp_question_id' => $bootCampQuestion->id,
                    'success' => $upload_response_data->success ?? false,
                    'file_path' => $upload_response_data->file_path ?? null
                ]);

                if (isset($upload_response_data->success) && $upload_response_data->success && isset($upload_response_data->file_path)) {
                    $data['pdf'] = $upload_response_data->file_path;
                    $data['pdf_size'] = $upload_response_data->data->file_size ?? null;

                    // Keep existing display name if not provided in form and no new file name
                    if (!$data['display_name']) {
                        $data['display_name'] = $upload_response_data->data->original_name ?? $bootCampQuestion->display_name;
                    }

                    // Verify the new file exists before proceeding
                    if (!BunnyHelper()->exists($data['pdf'])) {
                        Log::error('New file upload succeeded but verification failed during update', [
                            'boot_camp_question_id' => $bootCampQuestion->id,
                            'pdf_path' => $data['pdf']
                        ]);
                        DB::rollback();
                        return redirect()->route('admin.boot-camp-questions.index')
                            ->with('error', __('messages.file_upload_verification_failed'));
                    }

                    Log::info('New file uploaded and verified successfully', [
                        'boot_camp_question_id' => $bootCampQuestion->id,
                        'new_file_path' => $data['pdf']
                    ]);

                } else {
                    $error_message = $upload_response_data->message ?? __('messages.file_upload_failed');
                    Log::error('Upload failed during update', [
                        'boot_camp_question_id' => $bootCampQuestion->id,
                        'error_message' => $error_message
                    ]);
                    DB::rollback();
                    return redirect()->route('admin.boot-camp-questions.index')
                        ->with('error', $error_message);
                }
            } else {
                // If no new file uploaded, keep existing display_name if not provided
                if (!$data['display_name']) {
                    $data['display_name'] = $bootCampQuestion->display_name;
                }
            }

            // Update the boot camp question
            $updateResult = $bootCampQuestion->update($data);

            if (!$updateResult) {
                Log::error('Failed to update boot camp question in database', [
                    'boot_camp_question_id' => $bootCampQuestion->id
                ]);
                DB::rollback();
                return redirect()->route('admin.boot-camp-questions.index')
                    ->with('error', __('messages.some_thing_wont_wrong'));
            }

            // Delete old file only after successful update and if new file was uploaded
            if ($request->hasFile('pdf') && $oldFile && isset($data['pdf']) && $oldFile !== $data['pdf']) {
                try {
                    $deleteResult = BunnyHelper()->delete($oldFile);
                    Log::info('Old file deletion result', [
                        'boot_camp_question_id' => $bootCampQuestion->id,
                        'old_file_path' => $oldFile,
                        'delete_result' => $deleteResult
                    ]);
                } catch (\Exception $deleteException) {
                    // Log the error but don't fail the update
                    Log::warning('Failed to delete old file during update', [
                        'boot_camp_question_id' => $bootCampQuestion->id,
                        'old_file_path' => $oldFile,
                        'error' => $deleteException->getMessage()
                    ]);
                }
            }

            DB::commit();
            Log::info('Boot camp question updated successfully', ['boot_camp_question_id' => $bootCampQuestion->id]);

            return redirect()->route('admin.boot-camp-questions.index')
                ->with('success', __('messages.boot_camp_question_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Exception during boot camp question update', [
                'boot_camp_question_id' => $bootCampQuestion->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();

            return redirect()->route('admin.boot-camp-questions.index')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(BootCampQuestion $bootCampQuestion)
    {
        DB::beginTransaction();
        try {
            Log::info('Starting boot camp question deletion', [
                'boot_camp_question_id' => $bootCampQuestion->id,
                'has_pdf' => !empty($bootCampQuestion->pdf),
                'pdf_path' => $bootCampQuestion->pdf
            ]);

            $oldFile = $bootCampQuestion->pdf;

            // Delete the boot camp question from database first
            $deleteResult = $bootCampQuestion->delete();

            if (!$deleteResult) {
                Log::error('Failed to delete boot camp question from database', [
                    'boot_camp_question_id' => $bootCampQuestion->id
                ]);
                DB::rollback();
                return redirect()->route('admin.boot-camp-questions.index')
                    ->with('error', __('messages.some_thing_wont_wrong'));
            }

            // Delete PDF file after successful database deletion
            if ($oldFile) {
                try {
                    // Check if file exists before attempting deletion
                    if (BunnyHelper()->exists($oldFile)) {
                        $fileDeleteResult = BunnyHelper()->delete($oldFile);
                        Log::info('PDF file deletion result', [
                            'boot_camp_question_id' => $bootCampQuestion->id,
                            'file_path' => $oldFile,
                            'delete_result' => $fileDeleteResult
                        ]);
                    } else {
                        Log::info('PDF file does not exist, skipping deletion', [
                            'boot_camp_question_id' => $bootCampQuestion->id,
                            'file_path' => $oldFile
                        ]);
                    }
                } catch (\Exception $fileDeleteException) {
                    // Log the error but don't fail the deletion since DB record is already deleted
                    Log::warning('Failed to delete PDF file during boot camp question deletion', [
                        'boot_camp_question_id' => $bootCampQuestion->id,
                        'file_path' => $oldFile,
                        'error' => $fileDeleteException->getMessage()
                    ]);
                }
            }

            // Also try to delete the entire folder for this boot camp question
            try {
                $folderPath = BootCampQuestion::BUNNY_PATH . '/' . $bootCampQuestion->id;
                BunnyHelper()->deleteFiles($folderPath);
                Log::info('Boot camp question folder cleaned up', [
                    'boot_camp_question_id' => $bootCampQuestion->id,
                    'folder_path' => $folderPath
                ]);
            } catch (\Exception $folderDeleteException) {
                Log::warning('Failed to clean up boot camp question folder', [
                    'boot_camp_question_id' => $bootCampQuestion->id,
                    'error' => $folderDeleteException->getMessage()
                ]);
            }

            DB::commit();
            Log::info('Boot camp question deleted successfully', ['boot_camp_question_id' => $bootCampQuestion->id]);

            return redirect()->route('admin.boot-camp-questions.index')
                ->with('success', __('messages.boot_camp_question_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Exception during boot camp question deletion', [
                'boot_camp_question_id' => $bootCampQuestion->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollback();

            return redirect()->route('admin.boot-camp-questions.index')
                ->with('error', $e->getMessage());
        }
    }


    /**
     * Download PDF file
     */
   public function downloadPdf(BootCampQuestion $bootCampQuestion)
    {
        if (!$bootCampQuestion->pdf || !$bootCampQuestion->pdfExists()) {
            abort(404, __('messages.file_not_found'));
        }

        try {
            // Get file content from Bunny storage
            $fileContent = BunnyHelper()->getFileContent($bootCampQuestion->pdf);

            if (!$fileContent) {
                Log::error('Failed to get file content from Bunny', ['pdf_path' => $bootCampQuestion->pdf]);
                abort(404, __('messages.file_not_found'));
            }

            // Increment download count
            $bootCampQuestion->increment('download_count');

            // Prepare filename
            $filename = $bootCampQuestion->display_name ?? 'document.pdf';
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
                'boot_camp_question_id' => $bootCampQuestion->id,
                'pdf_path' => $bootCampQuestion->pdf,
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
    public function toggleStatus(BootCampQuestion $bootCampQuestion)
    {
        $bootCampQuestion->update([
            'is_active' => !$bootCampQuestion->is_active
        ]);

        $status = $bootCampQuestion->is_active ? __('messages.activated') : __('messages.deactivated');

        return response()->json([
            'success' => true,
            'message' => __('messages.boot_camp_question') . ' ' . $status,
            'is_active' => $bootCampQuestion->is_active
        ]);
    }
}
