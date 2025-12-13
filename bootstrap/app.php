<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\RouteServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        // Add StartSession middleware to API routes so sessions can be used
        $middleware->api(prepend: [
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        // statefulApi() applies EnsureFrontendRequestsAreStateful which enables sessions
        // and handles CSRF for requests from stateful domains
        $middleware->statefulApi();

        // CSRF validation: Only apply to web routes, not API routes
        // API routes are handled by Sanctum's EnsureFrontendRequestsAreStateful middleware
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        // Add request/response logging middleware (development only)
        $middleware->append(\App\Http\Middleware\LogRequestResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
