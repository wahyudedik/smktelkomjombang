<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware
        $middleware->append(\App\Http\Middleware\SetLocale::class);
        $middleware->append(\App\Http\Middleware\SetTimezone::class);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'verified.email' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        // Exclude Instagram webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'instagram/webhook',
            'iclock/cdata',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
