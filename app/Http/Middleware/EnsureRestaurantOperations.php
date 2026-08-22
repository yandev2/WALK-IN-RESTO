<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use App\Support\GuestContext;
use App\Support\SubscriptionGate;
use App\Support\TableQrToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRestaurantOperations
{
    public function handle(Request $request, Closure $next): Response
    {
        $restaurant = $this->resolveRestaurant($request);

        if (! $restaurant instanceof Restaurant) {
            return $next($request);
        }

        if (app(SubscriptionGate::class)->hasFeature($restaurant, 'operations')) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Pemesanan online tidak tersedia untuk restoran ini.',
                'code' => 'operations_unavailable',
            ], 403);
        }

        return redirect()
            ->route('landing.show', $restaurant)
            ->with('error', 'Pemesanan online tidak tersedia untuk restoran ini.');
    }

    private function resolveRestaurant(Request $request): ?Restaurant
    {
        $visit = $request->attributes->get('guest_visit') ?? GuestContext::visit();
        $fromVisit = $visit?->outlet?->restaurant;

        if ($fromVisit instanceof Restaurant) {
            return $fromVisit;
        }

        $token = $request->route('token');

        if (! is_string($token) || $token === '') {
            return null;
        }

        $table = TableQrToken::resolve($token);
        $table?->loadMissing('outlet.restaurant');

        return $table?->outlet?->restaurant;
    }
}
