<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class IdentifyGuestDevice
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('guest_device');

        if (blank($token) || ! is_string($token) || strlen($token) > 64 || preg_match('/[^a-zA-Z0-9_\-]/', $token)) {
            $token = Str::random(64);
            cookie()->queue(cookie(
                name: 'guest_device',
                value: $token,
                minutes: 60 * 24 * 30,
                httpOnly: true,
                sameSite: 'lax',
                secure: (bool) config('session.secure', app()->isProduction()),
            ));
        }

        $request->attributes->set('guest_device_token', $token);

        return $next($request);
    }
}
