<?php

namespace App\Support;

use App\Models\Restaurant;
use App\Models\User;
use Filament\Facades\Filament;

final class SubscriptionAccess
{
    public static function allows(?string $feature = null, bool $mutate = false): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        $tenant = Filament::getTenant();

        if (! $tenant instanceof Restaurant) {
            return true;
        }

        $gate = app(SubscriptionGate::class);

        if (! $gate->canAccessPanel($tenant)) {
            return false;
        }

        if (filled($feature) && ! $gate->hasFeature($tenant, $feature)) {
            return false;
        }

        if ($mutate && $gate->isReadOnly($tenant)) {
            return false;
        }

        return true;
    }

    public static function isReadOnly(): bool
    {
        $user = auth()->user();

        if ($user instanceof User && $user->isPlatformOperator()) {
            return false;
        }

        $tenant = Filament::getTenant();

        if (! $tenant instanceof Restaurant) {
            return false;
        }

        return app(SubscriptionGate::class)->isReadOnly($tenant);
    }
}
