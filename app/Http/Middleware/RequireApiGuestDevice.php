<?php

namespace App\Http\Middleware;

use App\Support\GuestContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireApiGuestDevice
{
    public function handle(Request $request, Closure $next): Response
    {
        if (blank(GuestContext::deviceToken())) {
            return response()->json([
                'message' => 'Device token wajib. Panggil POST /api/v1/guest/session dulu.',
                'code' => 'device_required',
            ], 401);
        }

        return $next($request);
    }
}
