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
                'pdf_urls' => [
                    'view' => !empty($question->pdf) ? route('api.bank-questions.pdf.view', $question->id) : null,
                    'download' => !empty($question->pdf) ? route('api.bank-questions.pdf.download', $question->id) : null,
                    'direct' => $question->pdf_path // This uses your existing getPdfPathAttribute
                ]
            ];
        });

        // Update the collection in paginator
        $questions->setCollection($transformedQuestions);

        return $this->success_response(__('Bank Questions fetched successfully'), $questions);
    }

      public function downloadPdf($id)
    {
        $bankQuestion = BankQuestion::find($id);

        if (!$bankQuestion || !$bankQuestion->pdf || !$bankQuestion->pdfExists()) {
            return $this->error_response(__('File not found'), [], 404);
        }

        try {
            // Get file content from Bunny storage
            $fileContent = BunnyHelper()->getFileContent($bankQuestion->pdf);

            if (!$fileContent) {
                return $this->error_response(__('File not found'), [], 404);
            }

            // Increment download count
            $bankQuestion->increment('download_count');

            // Prepare filename
            $filename = $bankQuestion->display_name ?? 'document.pdf';
            if (!str_ends_with(strtolower($filename), '.pdf')) {
                $filename .= '.pdf';
            }

            // Return file as download
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

            return $this->error_response(__('Download error'), [], 500);
        }
    }

     public function getMinisterialYearQuestion()
    {
        $questions = MinisterialYearsQuestion::with('category')->latest()->paginate(10);
        return $this->success_response(__('Ministerial Year Questions fetched successfully'), $questions);
    }
}
