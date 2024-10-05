<?php

use App\Http\Middleware\AuthAccess;
use Illuminate\Foundation\Application;
use App\Http\Middleware\AuthLoginBlocked;
use App\Http\Middleware\ApiAuthentication;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Sanctum\Http\Middleware\AuthenticateSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'AuthAccess' => AuthAccess::class,
            'AuthLoginBlocked' => AuthLoginBlocked::class,
            'AuthApi' => ApiAuthentication::class
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();