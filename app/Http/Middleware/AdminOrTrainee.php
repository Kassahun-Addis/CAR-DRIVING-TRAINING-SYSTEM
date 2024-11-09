<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminOrTrainee
{

    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check() || Auth::guard('trainee')->check()) {
            return $next($request);
        }

        return redirect()->route('login'); // Redirect to login if not authenticated
    }
}
