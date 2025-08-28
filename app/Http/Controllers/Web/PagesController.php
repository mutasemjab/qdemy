<?php

namespace  App\Http\Controllers\Web;

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
        return view('web.teachers',[
            'teachers' => $teachers,
        ]);
    }

    public function teacher(Teacher $teacher)
    {
        return view('web.teacher-profile',[
            'teacher' => $teacher,
        ]);
    }

    public function download()
    {
        return view('web.download');
    }

    public function contacts()
    {
        return view('web.contact');
    }

    public function sale_point()
    {
        return view('web.sale-point');
    }

    public function cards_order()
    {
        return view('web.cards-order');
    }

    public function community()
    {
        return view('web.community');
    }

    public function bank_questions()
    {
        return view('web.bank-questions');
    }

    public function ex_questions()
    {
        return view('web.ex');
    }

    public function packages_offers()
    {
        return view('web.packages-offers');
    }
}
