<?php
namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\OnBoarding;
use App\Traits\Responses;

class OnBoardingController extends Controller
{
    use Responses;

     public function index()
    {
        $data = OnBoarding::get();

        return $this->success_response('OnBoardings Retrieved successfully', $data);
    }


}
