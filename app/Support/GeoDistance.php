<?php

namespace App\Support;

final class GeoDistance
{
    public static function meters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earth = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 2 * $earth * atan2(sqrt($a), sqrt(1 - $a));
    }

    public static function kilometers(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        return self::meters($lat1, $lng1, $lat2, $lng2) / 1000;
    }

    public static function label(?float $kilometers): ?string
    {
        if ($kilometers === null) {
            return null;
        }

        if ($kilometers < 1) {
            return number_format($kilometers * 1000, 0, ',', '.').' m';
        }

        return number_format($kilometers, 1, ',', '.').' km';
    }
}
