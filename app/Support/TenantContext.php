<?php

namespace App\Support;

use App\Models\Outlet;
use App\Models\Restaurant;
use Filament\Facades\Filament;

final class TenantContext
{
    private static ?Restaurant $overrideRestaurant = null;

    private static bool $resolving = false;

    public static function set(?Restaurant $restaurant): void
    {
        self::$overrideRestaurant = $restaurant;
    }

    public static function clear(): void
    {
        self::$overrideRestaurant = null;
        self::$resolving = false;
    }

    public static function restaurant(): ?Restaurant
    {
        if (self::$overrideRestaurant instanceof Restaurant) {
            return self::$overrideRestaurant;
        }

        $tenant = Filament::getTenant();

        if ($tenant instanceof Restaurant) {
            return $tenant;
        }

        if (self::$resolving) {
            return null;
        }

        self::$resolving = true;

        try {
            return GuestContext::visit()?->outlet?->restaurant;
        } finally {
            self::$resolving = false;
        }
    }

    public static function restaurantId(): ?int
    {
        return self::restaurant()?->getKey();
    }

    public static function outlet(): ?Outlet
    {
        return self::restaurant()?->defaultOutlet;
    }

    public static function outletId(): ?int
    {
        return self::outlet()?->getKey();
    }
}
