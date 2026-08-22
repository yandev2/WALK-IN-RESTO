<?php

namespace App\Support;

use App\Models\Outlet;
use App\Models\Restaurant;
use Filament\Facades\Filament;

final class TenantContext
{
    private static ?Restaurant $overrideRestaurant = null;

    public static function set(?Restaurant $restaurant): void
    {
        self::$overrideRestaurant = $restaurant;
    }

    public static function clear(): void
    {
        self::$overrideRestaurant = null;
    }

    public static function restaurant(): ?Restaurant
    {
        if (self::$overrideRestaurant instanceof Restaurant) {
            return self::$overrideRestaurant;
        }

        $tenant = Filament::getTenant();

        return $tenant instanceof Restaurant ? $tenant : null;
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
