<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use App\Support\FilamentTenantTheme;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyPlatformBrandTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        $restaurant = FilamentTenantTheme::restaurantForCurrentPanel();

        if ($restaurant instanceof Restaurant) {
            FilamentTenantTheme::apply($restaurant);

            return $next($request);
        }

        FilamentTenantTheme::apply(null);

        return $next($request);
    }
}
