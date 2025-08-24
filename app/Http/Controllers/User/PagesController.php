<?php

namespace App\Http\Controllers\User;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function teachers()
    {
        $teachers  = Teacher::get();
        return view('user.teachers',[
            'teachers' => $teachers,
        ]);
    }

    public function teacher(Teacher $teacher)
    {
        return view('user.teacher-profile',[
            'teacher' => $teacher,
        ]);
    }

    public function download()
    {
        return view('user.download');
    }

    public function contacts()
    {
        return view('user.contact');
    }

    public function sale_point()
    {
        return view('user.sale-point');
    }

    public function cards_order()
    {
        return view('user.cards-order');
    }

    public function community()
    {
        return view('user.community');
    }

    public function e_exam()
    {
        return view('user.e-exam');
    }

    public function bank_questions()
    {
        return view('user.bank-questions');
    }

    public function ex_questions()
    {
        return view('user.ex');
    }
}
