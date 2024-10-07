<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // You can pass any data to the view here if needed
        return view('welcome'); // Ensure you have a view for the student dashboard
    }
}
