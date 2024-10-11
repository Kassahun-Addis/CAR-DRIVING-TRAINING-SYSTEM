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
    
     protected function sendFailedLoginResponse(Request $request)
     {
         throw ValidationException::withMessages([
             $this->username() => [trans('auth.failed')],
         ]);
     }
     
     public function login(Request $request)
{
    \Log::info('Login attempt:', $request->all());

    // Basic validation
    $credentials = $request->validate([
        'password' => 'required',
        'user_type' => 'required|in:admin,student',
    ]);

    try {
        if ($credentials['user_type'] === 'admin') {
            // Validate admin credentials
            $adminCredentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt to log in the admin
            if (Auth::attempt($adminCredentials)) {
                return redirect()->intended('/welcome'); // Admin dashboard
            }

            return back()->withErrors(['email' => 'Admin credentials do not match.']);
        } elseif ($credentials['user_type'] === 'student') {
            // Attempt to log in the student using yellow card number
            $yellowCardNumber = $credentials['password'];
            $trainee = Trainee::where('yellow_card', $yellowCardNumber)->first();

            if ($trainee) {
                \Log::info('Trainee found: ', [$trainee]);
                Auth::guard('trainee')->login($trainee);
                return redirect()->intended('/home'); // Student dashboard
            } else {
                \Log::info('Trainee not found for yellow card: ' . $yellowCardNumber);
                return back()->withErrors(['password' => 'Invalid yellow card number.']);
            }
        }
    } catch (\Exception $e) {
        // Log the exception message
        \Log::error('Login error: ' . $e->getMessage());

        // Return a generic error message to the user
        return back()->withErrors(['login' => 'An error occurred during login. Please try again.']);
    }

    return back()->withErrors(['user_type' => 'Invalid user type.']);
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