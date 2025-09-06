<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Repositories\CategoryRepository;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class ExamController extends Controller
{
    use Responses;
    
      public function index()
    {
        $exams = Exam::with(['subject', 'course', 'section', 'questions'])->latest()->paginate(10);

        return $this->success_response(__('Exams fetched successfully'), $exams);
    }


    public function getExamLink(Request $request, $examId)
  {
      $user = auth()->user(); // user from mobile token (Sanctum / Passport)

      // Check if exam exists
      $exam = Exam::findOrFail($examId);

      // ✅ Generate a signed URL (safe, expires after some minutes)
      $signedUrl = URL::temporarySignedRoute(
          'exam',  // route name
          now()->addMinutes(30), // expiration time
          ['exam' => $exam->id, 'user' => $user->id]
      );

      return $this->success_response(__('Exam link generated successfully'), [
          'exam_link' => $signedUrl
      ]);
  }

    
}