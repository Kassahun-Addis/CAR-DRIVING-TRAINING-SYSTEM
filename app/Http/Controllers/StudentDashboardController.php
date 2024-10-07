<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // You can pass any data to the view here if needed
        return view('home'); // Ensure you have a view for the student dashboard
    }

     // New method to show the dashboard for the logged-in trainee
     public function showDashboard()
    {
        // Get the currently authenticated trainee
        $trainee = Auth::user(); // Fetch the logged-in user
    
        // Debug: Log the authenticated trainee information
        \Log::info('Authenticated Trainee:', $trainee ? $trainee->toArray() : 'No authenticated trainee');
    
        // Return the dashboard view with the trainee's data
        return view('home', compact('trainee')); // Ensure 'home' is the correct view name
    }
}