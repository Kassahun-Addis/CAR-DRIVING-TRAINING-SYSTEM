<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share the company data with multiple views
        View::composer(['partials.header', 'student.navigation'], function ($view) {
            $user = Auth::user();
            $company = $user ? Company::where('company_id', $user->company_id)->first() : null;
            $view->with('company', $company);
        });
    }
}