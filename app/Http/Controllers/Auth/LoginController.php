<?php
// app/Http/Controllers/Auth/AdminLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function showLoginForms()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        \Log::info('Login attempt:', $request->only('email', 'role'));

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,clerk', // Only admin and clerk
        ]);

        // Check if the user exists with the given credentials
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            // Check if the user is verified and the role matches
            if ($user->active && (
                ($credentials['role'] === 'admin' && ($user->role === 'admin' || $user->role === 'superadmin')) ||
                ($credentials['role'] === 'superadmin' && $user->role === 'superadmin') || // Allow superadmin role
                ($credentials['role'] === 'clerk' && $user->role === 'clerk')
            )) {
                return redirect()->intended('/'); // Redirect to the appropriate dashboard
            }
            Auth::logout(); // Log out if the user is not verified or role does not match
            return back()->withErrors(['email' => 'You are not authorized to log in as this role.']);
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