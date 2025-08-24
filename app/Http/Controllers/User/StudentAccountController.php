<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentAccountController extends Controller
{
    public function index()
    {
        return view('user.account');
    }
}
