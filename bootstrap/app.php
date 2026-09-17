<?php

use App\Http\Middleware\EnsureApiGuestVisit;
use App\Http\Middleware\EnsureGuestVisit;
use App\Http\Middleware\IdentifyApiGuestDevice;
use App\Http\Middleware\IdentifyGuestDevice;
use App\Http\Middleware\RequireApiGuestDevice;
use App\Http\Middleware\SecurityHeaders;
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
        $trustedProxies = env('TRUSTED_PROXIES');
        if (filled($trustedProxies)) {
            if ($trustedProxies === '*') {
                $middleware->trustProxies(at: '*');
            } elseif ($trustedProxies === 'cloudflare') {
                $middleware->trustProxies(at: [
                    '173.245.48.0/20',
                    '103.21.244.0/22',
                    '103.22.200.0/22',
                    '103.31.4.0/22',
                    '141.101.64.0/18',
                    '108.162.192.0/18',
                    '190.93.240.0/20',
                    '188.114.96.0/20',
                    '197.234.240.0/22',
                    '198.41.128.0/17',
                    '162.158.0.0/15',
                    '104.16.0.0/13',
                    '104.24.0.0/14',
                    '172.64.0.0/13',
                    '131.0.72.0/22',
                    '2400:cb00::/32',
                    '2606:4700::/32',
                    '2803:f800::/32',
                    '2405:b500::/32',
                    '2405:8100::/32',
                    '2a06:98c0::/29',
                    '2c0f:f248::/32',
                ]);
            } else {
                $proxies = array_filter(array_map('trim', explode(',', (string) $trustedProxies)));
                $middleware->trustProxies(at: array_values($proxies));
            }
        } elseif (env('APP_ENV') === 'production') {
            $middleware->trustProxies(at: [
                '127.0.0.1',
                '::1',
                '10.0.0.0/8',
                '172.16.0.0/12',
                '192.168.0.0/16',
            ]);
        } else {
            $middleware->trustProxies(at: '*');
        }

        $middleware->append(SecurityHeaders::class);

        $middleware->redirectTo(
            guests: '/admin/login',
            users: function () {
                $user = auth()->user();
                if ($user instanceof \App\Models\User) {
                    if ($user->isSuperAdmin()) {
                        return '/founder';
                    }
                    $restaurant = $user->restaurants()->first();
                    if ($restaurant) {
                        return '/admin/'.$restaurant->slug;
                    }
                }

                return '/admin';
            }
        );

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
