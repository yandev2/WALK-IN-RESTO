<?php

namespace App\Http\Middleware;

use App\Models\VisitDevice;
use App\Services\StaleOperationsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->attributes->get('guest_device_token') ?? $request->cookie('guest_device');

        if (blank($token)) {
            return redirect()->route('guest.need-scan');
        }

        $device = VisitDevice::query()
            ->with(['visit.diningTable', 'visit.outlet.restaurant', 'visit.orders'])
            ->where('device_token', $token)
            ->latest('id')
            ->first();

        $visit = $device?->visit;

        if (! $visit || $visit->status !== 'open') {
            return redirect()->route('guest.need-scan');
        }

        app(StaleOperationsService::class)->sweepVisit($visit->fresh(['orders', 'diningTable']));

        $visit->refresh();

        if ($visit->status !== 'open') {
            return redirect()->route('guest.need-scan');
        }

        $device->forceFill(['last_seen_at' => now()])->save();

        $request->attributes->set('guest_device', $device);
        $request->attributes->set('guest_visit', $visit);

        return $next($request);
    }
}
