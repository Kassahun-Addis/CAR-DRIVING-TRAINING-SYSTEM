<?php
// app/Http/Controllers/Auth/AdminLoginController.php

// app/Http/Controllers/Auth/AdminLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
{
    \Log::info('Login attempt:', $request->only('email', 'role'));

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'role' => 'required|in:admin,clerk', // Validate the user type
    ]);

    // Check if the user exists with the given credentials
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if ($user && Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
        // Check if the user type matches
        if ($user->role === $credentials['role']) {
            return redirect()->intended('/welcome'); // Redirect to admin or clerk dashboard
        }
        Auth::logout(); // Log out if the user type does not match
        return back()->withErrors(['email' => 'Invalid user type.']);
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login'); // Redirect to admin login page
    }
}