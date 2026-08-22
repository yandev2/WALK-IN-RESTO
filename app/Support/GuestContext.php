<?php

namespace App\Support;

use App\Models\Visit;
use App\Models\VisitDevice;
use Illuminate\Http\Request;

final class GuestContext
{
    public static function tokenFromRequest(?Request $request = null): ?string
    {
        $request ??= request();

        $header = $request->headers->get('X-Guest-Device');

        if (filled($header)) {
            return trim((string) $header);
        }

        $authorization = $request->headers->get('Authorization');

        if (is_string($authorization) && str_starts_with($authorization, 'Bearer ')) {
            $token = trim(substr($authorization, 7));

            return $token !== '' ? $token : null;
        }

        return null;
    }

    public static function deviceToken(): ?string
    {
        return request()->attributes->get('guest_device_token')
            ?? self::tokenFromRequest()
            ?? request()->cookie('guest_device');
    }

    public static function visit(): ?Visit
    {
        $visit = request()->attributes->get('guest_visit');

        if ($visit instanceof Visit) {
            return $visit;
        }

        $token = self::deviceToken();

        if (blank($token)) {
            return null;
        }

        $device = VisitDevice::query()
            ->with(['visit.diningTable', 'visit.outlet.restaurant'])
            ->where('device_token', $token)
            ->latest('id')
            ->first();

        $resolved = $device?->visit;

        if (! $resolved || $resolved->status !== 'open') {
            return null;
        }

        request()->attributes->set('guest_device', $device);
        request()->attributes->set('guest_visit', $resolved);

        return $resolved;
    }

    public static function device(): ?VisitDevice
    {
        $device = request()->attributes->get('guest_device');

        return $device instanceof VisitDevice ? $device : null;
    }
}
