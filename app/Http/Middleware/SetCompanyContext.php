<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetCompanyContext
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Assuming the user has a company_id attribute
            $companyId = Auth::user()->company_id;
            app()->instance('currentCompanyId', $companyId);
        } else {
            // Handle the case for guests or unauthenticated users
            // You might want to set a default company ID or handle it differently
            app()->instance('currentCompanyId', null);
        }

        // Add logging to verify the middleware execution
        \Log::info('SetCompanyContext middleware executed', [
            'user_id' => Auth::id(),
            'company_id' => app()->bound('currentCompanyId') ? app('currentCompanyId') : 'not set',
        ]);

        return $next($request);
    }
}