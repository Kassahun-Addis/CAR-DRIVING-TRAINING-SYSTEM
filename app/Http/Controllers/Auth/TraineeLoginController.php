<?php
// app/Http/Controllers/Auth/TraineeLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainee;
use Illuminate\Support\Facades\Auth;

class TraineeLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.trainee-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone_no' => 'required|numeric',
            'yellow_card' => 'required',
        ]);

        $trainee = Trainee::where('yellow_card', $credentials['yellow_card'])->
                            where('phone_no', $credentials['phone_no'])->first();

        if ($trainee) {
            Auth::guard('trainee')->login($trainee);
            return redirect()->intended('/home'); // Redirect to trainee dashboard
        }

        return back()->withErrors(['yellow_card' => 'Invalid yellow card number.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('trainee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/trainee/login'); // Redirect to trainee login page
    }
}