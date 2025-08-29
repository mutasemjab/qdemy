<?php
namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Traits\Responses;

class TeacherController extends Controller
{
    use Responses;

     public function index()
    {
        $data = Teacher::with('user')->get();

        return $this->success_response('Teachers Retrieved successfully', $data);
    }


}
