<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\BankQuestion;
use App\Models\Category;
use App\Models\Subject;

class BankQuestionController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryId = $request->get('category_id');
        $subjectId = $request->get('subject_id');
        $search = $request->get('search');

        // Build query with relationships
        $query = BankQuestion::with(['category', 'subject'])
            ->where('bank_questions.is_active', true)
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
        $bankQuestions = $query->paginate(12);

        // Get categories for filter dropdown (only those with bank questions)
        $categories = Category::whereHas('bankQuestions')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get subjects for filter dropdown
        $subjects = collect();
        if ($categoryId) {
            $subjects = Subject::whereHas('bankQuestions', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        }

        return view('web.bank-questions', compact('bankQuestions', 'categories', 'subjects', 'categoryId', 'subjectId', 'search'));
    }

    public function download(BankQuestion $bankQuestion)
    {
        if (!$bankQuestion->pdfExists()) {
            return redirect()->back()->with('error', __('front.file_not_available'));
        }

        // Increment download count if you have a downloads field
        $bankQuestion->increment('download_count');

        // Return file download response
        return response()->download($bankQuestion->pdf_path, $bankQuestion->display_name ?? 'document.pdf');
    }

    // AJAX endpoint for getting subjects by category
    public function getSubjectsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        if (!$categoryId) {
            return response()->json([]);
        }

        $subjects = Subject::whereHas('bankQuestions', function($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get()
        ->map(function($subject) {
            return [
                'id' => $subject->id,
                'name' => app()->getLocale() === 'ar' ? $subject->name_ar : ($subject->name_en ?? $subject->name_ar),
            ];
        });

        return response()->json($subjects);
    }
}