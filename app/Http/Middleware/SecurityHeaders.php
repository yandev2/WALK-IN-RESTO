<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach standard HTTP security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), camera=(), microphone=()');

        if (app()->environment('production') || $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Apply Content-Security-Policy baseline in production and testing environments
        if (app()->environment('production', 'testing')) {
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.bunny.net https://unpkg.com https://cdn.jsdelivr.net",
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com https://unpkg.com",
                "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com data:",
                "img-src 'self' data: blob: https:",
                "media-src 'self' data: blob: https:",
                "connect-src 'self' ws: wss: https:",
                "frame-src 'self' https://www.google.com https://maps.google.com",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ];
            $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        }

        return $response;
    }
}
