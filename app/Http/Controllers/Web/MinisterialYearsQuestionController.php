<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\MinisterialYearsQuestion;
use App\Models\Category;
use App\Models\Subject;

class MinisterialYearsQuestionController extends Controller
{
    /**
     * Display a listing of ministerial years questions for frontend
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryId = $request->get('category_id');
        $subjectId = $request->get('subject_id');
        $search = $request->get('search');

        // Build query with relationships
        $query = MinisterialYearsQuestion::with(['category', 'subject'])
            ->where('is_active', true)
            ->orderBy('sort_order', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('display_name', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name_ar', 'like', "%{$search}%")
                               ->orWhere('name_en', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subject', function($subQuery) use ($search) {
                      $subQuery->where('name_ar', 'like', "%{$search}%")
                               ->orWhere('name_en', 'like', "%{$search}%");
                  });
            });
        }

        // Paginate results
        $ministerialQuestions = $query->paginate(12);

        // Get categories for filter dropdown (only those with active ministerial questions)
        $categories = Category::whereHas('ministerialYearsQuestions', function($q) {
            $q->where('is_active', true);
        })
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

        // Get subjects for filter dropdown
        $subjects = collect();
        if ($categoryId) {
            $subjects = Subject::whereHas('ministerialYearsQuestions', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId)
                      ->where('is_active', true);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        }

        return view('web.ministerial-questions', compact('ministerialQuestions', 'categories', 'subjects', 'categoryId', 'subjectId', 'search'));
    }

    /**
     * Download a ministerial years question file
     */
    public function download(MinisterialYearsQuestion $ministerialQuestion)
    {
        // Check if ministerial question is active and file exists
        if (!$ministerialQuestion->is_active || !$ministerialQuestion->pdfExists()) {
            abort(404, __('front.file_not_available'));
        }

        // Increment download count
        $ministerialQuestion->increment('download_count');

        // Return file download response
        return response()->download(
            $ministerialQuestion->pdf_path, 
            $ministerialQuestion->display_name ?? 'ministerial-question.pdf'
        );
    }

    /**
     * AJAX endpoint for getting subjects by category
     */
    public function getSubjectsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        if (!$categoryId) {
            return response()->json([]);
        }

        // Get subjects that are related to this category and have active ministerial questions
        $subjects = Subject::whereHas('ministerialYearsQuestions', function($query) use ($categoryId) {
            $query->where('category_id', $categoryId)
                  ->where('is_active', true);
        })
        ->orWhere(function($query) use ($categoryId) {
            $query->where('grade_id', $categoryId)
                  ->orWhere('semester_id', $categoryId)
                  ->orWhere('programm_id', $categoryId);
        })
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get(['id', 'name_ar', 'name_en'])
        ->map(function($subject) {
            return [
                'id' => $subject->id,
                'name' => app()->getLocale() === 'ar' ? $subject->name_ar : ($subject->name_en ?? $subject->name_ar),
            ];
        });

        return response()->json($subjects);
    }
}