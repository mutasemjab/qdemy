<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\MinisterialYearsQuestion;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $ministerialYear = MinisterialYearsQuestion::create($data);
            
            if ($request->hasFile('pdf')) {
                $upload_response = BunnyHelper()->upload($request->pdf, MinisterialYearsQuestion::BUNNY_PATH . '/' . $ministerialYear->id);
                $upload_response_data = $upload_response->getData();
                
                if($upload_response_data->success && $upload_response_data->file_path){
                    $ministerialYear->pdf = $upload_response_data->file_path;
                    $ministerialYear->pdf_size = $upload_response_data->data?->file_size;
                    
                    // Use custom display name if provided, otherwise use original file name
                    if (!$ministerialYear->display_name) {
                        $ministerialYear->display_name = $upload_response_data->data?->original_name;
                    }
                    
                    if($ministerialYear->save()){
                        DB::commit();
                        $message_status = 'success';
                        $message = __('messages.ministerial_question_created_successfully');
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

        return redirect()->route('ministerial-questions.index')
            ->with($message_status, $message);
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

    /**
     * Update the specified resource in storage.
     */
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
            if ($request->hasFile('pdf')) {
                $upload_response = BunnyHelper()->upload($request->pdf, MinisterialYearsQuestion::BUNNY_PATH . '/'. $ministerialQuestion->id);
                $upload_response_data = $upload_response->getData();
                
                if($upload_response_data->success && $upload_response_data->file_path){
                    $data['pdf'] = $upload_response_data->file_path;
                    $data['pdf_size'] = $upload_response_data->data?->file_size;
                    
                    // Keep existing display name if not provided in form and no new file name
                    if (!$data['display_name']) {
                        $data['display_name'] = $upload_response_data->data?->original_name;
                    }
                    
                    $oldFile = $ministerialQuestion->pdf;
                    if($oldFile){ 
                        BunnyHelper()->delete($oldFile); 
                    }
                }
            }
            
            $ministerialQuestion->update($data);
            DB::commit();
            $message_status = 'success';
            $message = __('messages.ministerial_question_updated_successfully');
            
        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            $message_status = 'error';
        }

        return redirect()->route('ministerial-questions.index')
            ->with($message_status, $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MinisterialYearsQuestion $ministerialQuestion)
    {
        DB::beginTransaction();
        try {
            // Delete PDF file
            if ($ministerialQuestion->pdf) {
                $oldFile = $ministerialQuestion->pdf;
                if($oldFile){ 
                    BunnyHelper()->delete($oldFile); 
                }
            }
            
            $ministerialQuestion->delete();
            DB::commit();
            
            $message_status = 'success';
            $message = __('messages.ministerial_question_deleted_successfully');
            
        } catch (\Exception $e) {
            DB::rollback();
            $message_status = 'error';
            $message = $e->getMessage();
        }

        return redirect()->route('ministerial-questions.index')
            ->with($message_status, $message);
    }

    /**
     * Download PDF file
     */
    public function downloadPdf(MinisterialYearsQuestion $ministerialQuestion)
    {
        if (!$ministerialQuestion->pdf || !$ministerialQuestion->pdfExists()) {
            abort(404, __('messages.file_not_found'));
        }
        
        // Increment download count
        $ministerialQuestion->increment('download_count');
        
        return response()->download(
            $ministerialQuestion->pdf_path, 
            $ministerialQuestion->display_name ?? 'ministerial-question.pdf'
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

        // Get subjects that are related to this category through category_subjects pivot table
        $subjects = Subject::whereHas('categories', function($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })
        ->orWhere('grade_id', $categoryId)
        ->orWhere('semester_id', $categoryId)
        ->orWhere('programm_id', $categoryId)
        ->active()
        ->ordered()
        ->get(['id', 'name_ar', 'name_en'])
        ->map(function($subject) {
            return [
                'id' => $subject->id,
                'name' => app()->getLocale() === 'ar' ? $subject->name_ar : ($subject->name_en ?? $subject->name_ar),
            ];
        });

        return response()->json($subjects);
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