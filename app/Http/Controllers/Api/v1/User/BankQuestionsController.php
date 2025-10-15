<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\BankQuestion;
use App\Models\MinisterialYearsQuestion;
use Illuminate\Http\Request;
use App\Traits\Responses;
use Illuminate\Support\Facades\Log;

class BankQuestionsController extends Controller
{
    use Responses;

   public function getBankQuestion(Request $request)
    {
        $query = BankQuestion::with(['category', 'subject'])
            ->active()
            ->latest();

        // Add filtering capabilities
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        $perPage = $request->get('per_page', 10);
        $questions = $query->paginate($perPage);

        // Transform the data to include PDF URLs
        $transformedQuestions = $questions->getCollection()->map(function ($question) {
            return [
                'id' => $question->id,
                'display_name' => $question->display_name,
                'category' => [
                    'id' => $question->category?->id,
                    'name' => $question->category_name,
                    'breadcrumb' => $question->category_breadcrumb
                ],
                'subject' => [
                    'id' => $question->subject?->id,
                    'name' => $question->subject_name
                ],
                'file_info' => [
                    'size' => $question->formatted_file_size,
                    'has_pdf' => !empty($question->pdf),
                ],
                'download_count' => $question->download_count,
                'is_active' => $question->is_active,
                'created_at' => $question->created_at,
                'pdf_path' => $question->pdf_path 

            ];
        });

        // Update the collection in paginator
        $questions->setCollection($transformedQuestions);

        return $this->success_response(__('Bank Questions fetched successfully'), $questions);
    }

    

    public function getMinisterialYearQuestion(Request $request)
    {
        $query = MinisterialYearsQuestion::with(['category', 'subject'])
            ->latest();

        // Add filtering capabilities
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $search = $request->search;
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

        // Add active scope if it exists
        if (method_exists(MinisterialYearsQuestion::class, 'scopeActive')) {
            $query->active();
        }

        $perPage = $request->get('per_page', 10);
        $questions = $query->paginate($perPage);

        // Transform the data to match bank questions structure
        $transformedQuestions = $questions->getCollection()->map(function ($question) {
            return [
                'id' => $question->id,
                'display_name' => $question->display_name,
                'category' => [
                    'id' => $question->category?->id,
                    'name' => $this->getCategoryName($question),
                    'breadcrumb' => $this->getCategoryBreadcrumb($question)
                ],
                'subject' => [
                    'id' => $question->subject?->id,
                    'name' => $this->getSubjectName($question)
                ],
                'file_info' => [
                    'size' => $this->getFormattedFileSize($question),
                    'has_pdf' => !empty($question->pdf),
                ],
                'download_count' => $question->download_count ?? 0,
                'is_active' => $question->is_active ?? true,
                'created_at' => $question->created_at,
                'pdf_path' => $question->pdf_path ,
            ];
        });

        // Update the collection in paginator
        $questions->setCollection($transformedQuestions);

        return $this->success_response(__('Ministerial Year Questions fetched successfully'), $questions);
    }
}
