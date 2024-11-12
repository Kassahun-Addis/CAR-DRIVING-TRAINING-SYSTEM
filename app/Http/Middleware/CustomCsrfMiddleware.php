<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomCsrfMiddleware
{
    protected $except = [
        'api/save-exam-score', // Add your route here
    ];

    public function handle(Request $request, Closure $next)
    {
        // Check if the request path is in the $except array
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        // Perform CSRF token verification here
        // If verification fails, return an appropriate response
        if ($request->method() === 'POST' && !$request->hasHeader('X-CSRF-TOKEN')) {
            return response()->json(['error' => 'CSRF token missing'], 419);
        }

        return $next($request);
    }

    protected function inExceptArray(Request $request)
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}