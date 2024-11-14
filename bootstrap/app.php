<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'adminOrTrainee' => \App\Http\Middleware\AdminOrTrainee::class,
            'company.context' => \App\Http\Middleware\SetCompanyContext::class,
            'redirectIfUnauthenticated' => \App\Http\Middleware\RedirectIfUnauthenticated::class, // Register your middleware
        ]);

        // Add CSRF exclusion for specific routes here
        $middleware->validateCsrfTokens(except: [
            'api/save-exam-score',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
