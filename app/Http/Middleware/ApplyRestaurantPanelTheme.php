<?php

namespace App\Http\Middleware;

use App\Support\FilamentTenantTheme;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyRestaurantPanelTheme
{
    public function handle(Request $request, Closure $next): Response
    {
        FilamentTenantTheme::apply(FilamentTenantTheme::restaurantForCurrentPanel());

        return $next($request);
    }
}
