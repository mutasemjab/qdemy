<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\BankQuestion;
use App\Models\MinisterialYearsQuestion;

use App\Traits\Responses;

class BankQuestionsController extends Controller
{
    use Responses;

    public function getBankQuestion()
    {
        $questions = BankQuestion::with('category')->latest()->paginate(10);
        return $this->success_response(__('Bank Questions fetched successfully'), $questions);
    }

     public function getMinisterialYearQuestion()
    {
        $questions = MinisterialYearsQuestion::with('category')->latest()->paginate(10);
        return $this->success_response(__('Ministerial Year Questions fetched successfully'), $questions);
    }
}
