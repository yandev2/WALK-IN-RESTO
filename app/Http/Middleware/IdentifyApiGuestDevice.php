<?php

namespace App\Http\Middleware;

use App\Support\GuestContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyApiGuestDevice
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = GuestContext::tokenFromRequest($request);

        if (filled($token)) {
            $request->attributes->set('guest_device_token', $token);
        }

        return $next($request);
    }
}
