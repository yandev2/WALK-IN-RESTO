<?php

use App\Http\Middleware\EnsureApiGuestVisit;
use App\Http\Middleware\EnsureGuestVisit;
use App\Http\Middleware\IdentifyApiGuestDevice;
use App\Http\Middleware\IdentifyGuestDevice;
use App\Http\Middleware\RequireApiGuestDevice;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'identify.guest' => IdentifyGuestDevice::class,
            'guest.visit' => EnsureGuestVisit::class,
            'identify.api.guest' => IdentifyApiGuestDevice::class,
            'require.api.guest' => RequireApiGuestDevice::class,
            'guest.api.visit' => EnsureApiGuestVisit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function ($request, Throwable $e): bool {
            return $request->is('api/*') || $request->expectsJson();
        });
    })->create();
