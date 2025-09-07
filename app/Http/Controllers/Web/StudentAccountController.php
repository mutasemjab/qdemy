<?php

namespace  App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentAccountController extends Controller
{
    public function index()
    {
        $userExamsResults    = collect();
        $userCourses         = collect();

        $user    = auth_student();
        if($user){
           $userExamsResults    = $user->result_attempts();
           $userCourses         = $user->courses;
        }
        return view('web.account',[
            'userCourses'      => $userCourses,
            'userExamsResults' => $userExamsResults,
        ]);
    }
}
