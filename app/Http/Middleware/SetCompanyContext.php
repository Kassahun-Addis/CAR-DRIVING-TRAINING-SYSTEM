<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SetCompanyContext
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $companyId = Auth::user()->company_id;
            app()->instance('currentCompanyId', $companyId);
            Log::info('Current Company ID: ' . $companyId);

        }

        return $next($request);
    }
}