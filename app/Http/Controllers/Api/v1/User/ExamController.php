<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Repositories\CategoryRepository;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    use Responses;
    
      public function index()
    {
        $exams = Exam::with(['subject', 'course', 'section', 'questions'])->latest()->paginate(10);

        return $this->success_response(__('Exams fetched successfully'), $exams);
    }

    
}