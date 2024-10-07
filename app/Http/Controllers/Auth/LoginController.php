<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trainee; // Import the Trainee model
use App\Models\User; // Import the User model for admin authentication

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:admin,student', // Ensure user_type is either admin or student
        ]);

        // Check if the user is an admin
        if ($credentials['user_type'] === 'admin') {
            // Attempt to log the admin in
            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                return redirect()->intended('/welcome'); // Redirect to the welcome page for admin
            }

            // If authentication fails for admin
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records for admin.',
            ]);
        } elseif ($credentials['user_type'] === 'student') {
            // For students, check the trainees table using ID as password
            $trainee = Trainee::where('id', $credentials['password'])->first();

            // If a trainee is found, you can authenticate them
            if ($trainee) {
                // You may not need to use Auth::login() since you're handling it differently
                return redirect()->intended('/home'); // Redirect to the student dashboard
            }

            // If no trainee found, return an error
            return back()->withErrors([
                'password' => 'The provided ID does not match our records.',
            ]);
        }

        // If user_type is not valid
        return back()->withErrors([
            'user_type' => 'Invalid user type specified.',
        ]);
    }

    /**
     * Handle a successful authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        // This method can be omitted since we are handling redirects directly in the login method
    }
}