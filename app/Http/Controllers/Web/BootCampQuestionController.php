<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\BootCampQuestion;
use App\Models\Category;
use App\Models\Subject;

class BootCampQuestionController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryId = $request->get('category_id');
        $subjectId = $request->get('subject_id');
        $search = $request->get('search');

        // Build query with relationships
        $query = BootCampQuestion::with(['category', 'subject'])
            ->where('boot_camp_questions.is_active', true)
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
        $bootCampQuestions = $query->paginate(12);

        // Get categories for filter dropdown (only those with boot camp questions)
        $categories = Category::whereHas('bootCampQuestions')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get subjects for filter dropdown
        $subjects = collect();
        if ($categoryId) {
            $subjects = Subject::whereHas('bootCampQuestions', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        }

        return view('web.boot-camp-questions', compact('bootCampQuestions', 'categories', 'subjects', 'categoryId', 'subjectId', 'search'));
    }

    public function download(BootCampQuestion $bootCampQuestion)
    {
        if (!$bootCampQuestion->pdf || !$bootCampQuestion->pdfExists()) {
            return redirect()->back()->with('error', __('front.file_not_available'));
        }

        try {
            // Get file content from Bunny storage
            $fileContent = BunnyHelper()->getFileContent($bootCampQuestion->pdf);

            if (!$fileContent) {
                return redirect()->back()->with('error', __('front.file_not_available'));
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
            \Illuminate\Support\Facades\Log::error('Error downloading PDF', [
                'boot_camp_question_id' => $bootCampQuestion->id,
                'pdf_path' => $bootCampQuestion->pdf,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', __('front.file_not_available'));
        }
    }

    // AJAX endpoint for getting subjects by category
    public function getSubjectsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        if (!$categoryId) {
            return response()->json([]);
        }

        $subjects = Subject::whereHas('bootCampQuestions', function($query) use ($categoryId) {
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
