<?php

namespace App\Http\Middleware;

use App\Models\VisitDevice;
use App\Services\StaleOperationsService;
use App\Support\GuestContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiGuestVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = GuestContext::deviceToken();

        if (blank($token)) {
            return response()->json([
                'message' => 'Device token wajib. Panggil POST /api/v1/guest/session dulu.',
                'code' => 'device_required',
            ], 401);
        }

        $device = VisitDevice::query()
            ->with(['visit.diningTable', 'visit.outlet.restaurant', 'visit.orders'])
            ->where('device_token', $token)
            ->latest('id')
            ->first();

        $visit = $device?->visit;

        if (! $visit || $visit->status !== 'open') {
            return response()->json([
                'message' => 'Scan QR meja dulu.',
                'code' => 'visit_required',
            ], 401);
        }

        app(StaleOperationsService::class)->sweepVisit($visit->fresh(['orders', 'diningTable']));

        $visit->refresh();

        if ($visit->status !== 'open') {
            return response()->json([
                'message' => 'Sesi meja sudah berakhir. Scan QR lagi.',
                'code' => 'visit_expired',
            ], 401);
        }

        $device->forceFill(['last_seen_at' => now()])->save();

        $request->attributes->set('guest_device_token', $token);
        $request->attributes->set('guest_device', $device);
        $request->attributes->set('guest_visit', $visit);

        return $next($request);
    }
}
