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

    

     public function getMinisterialYearQuestion()
    {
        $questions = MinisterialYearsQuestion::with('category')->latest()->paginate(10);
        return $this->success_response(__('Ministerial Year Questions fetched successfully'), $questions);
    }
}
