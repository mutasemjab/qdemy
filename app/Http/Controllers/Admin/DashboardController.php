<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Get total customers (users)
    $totalCustomers = User::count();


    return view('admin.dashboard', compact(
        'totalCustomers',
       
    ));
}

}
