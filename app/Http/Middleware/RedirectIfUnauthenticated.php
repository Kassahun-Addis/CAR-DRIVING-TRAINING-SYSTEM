<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfUnauthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // Check if the user is an admin
            if (auth()->guard('web')->check()) {
                return redirect()->guest(route('admin.login'));
            }

            // Check if the user is a trainee
            if (auth()->guard('trainee')->check()) {
                return redirect()->guest(route('trainee.login'));
            }

            // Default redirection if no specific guard is matched
            return redirect()->guest(route('login'));
        }

        return $next($request);
    }
}