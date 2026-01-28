<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\BootCampQuestion;
use Illuminate\Http\Request;
use App\Traits\Responses;
use Illuminate\Support\Facades\Log;

class BootCampQuestionsController extends Controller
{
    use Responses;

   public function getBootCampQuestions(Request $request)
    {
        $query = BootCampQuestion::with(['category', 'subject'])
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

        return $this->success_response(__('Boot Camp Questions fetched successfully'), $questions);
    }
}
