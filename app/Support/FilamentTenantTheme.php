<?php

namespace App\Support;

use App\Models\Restaurant;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Colors\ColorManager;
use Filament\Support\Facades\FilamentColor;
use ReflectionClass;

final class FilamentTenantTheme
{
    public static function restaurantForCurrentPanel(): ?Restaurant
    {
        $tenant = Filament::getTenant();

        if ($tenant instanceof Restaurant) {
            return $tenant;
        }

        if (Filament::getCurrentPanel()?->getId() !== 'admin') {
            return null;
        }

        $user = auth()->user();

        if (! $user instanceof User || $user->isPlatformOperator()) {
            return null;
        }

        $panel = Filament::getCurrentPanel();

        if (! $panel) {
            return null;
        }

        $restaurant = $user->getTenants($panel)->first();

        return $restaurant instanceof Restaurant ? $restaurant : null;
    }

    public static function apply(?Restaurant $restaurant): void
    {
        if ($restaurant instanceof Restaurant && ! $restaurant->relationLoaded('cmsProfile')) {
            $restaurant->load('cmsProfile');
        }

        self::resetColorCache();

        FilamentColor::register([
            'primary' => Color::hex(RestaurantTheme::for($restaurant)['primary']),
        ]);
    }

    private static function resetColorCache(): void
    {
        $manager = app(ColorManager::class);
        $reflection = new ReflectionClass($manager);

        if ($reflection->hasProperty('cachedColors')) {
            $property = $reflection->getProperty('cachedColors');
            $property->setAccessible(true);

            if ($property->isInitialized($manager)) {
                if (method_exists($property, 'unsetValue')) {
                    $property->unsetValue($manager);
                } else {
                    $property->setValue($manager, []);
                }
            }
        }

        foreach (['componentClasses', 'componentCustomStyles'] as $propertyName) {
            if (! $reflection->hasProperty($propertyName)) {
                continue;
            }

            $property = $reflection->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($manager, []);
        }
    }
}
