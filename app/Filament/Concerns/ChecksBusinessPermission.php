<?php

namespace App\Filament\Concerns;

use App\Models\User;
use App\Support\SubscriptionAccess;
use Illuminate\Database\Eloquent\Model;

trait ChecksBusinessPermission
{
    public static function canViewAny(): bool
    {
        return static::userHasPermission(static::$permission)
            && SubscriptionAccess::allows(static::subscriptionFeature());
    }

    public static function canCreate(): bool
    {
        return static::userHasPermission(static::$permission)
            && SubscriptionAccess::allows(static::subscriptionFeature(), mutate: true);
    }

    public static function canEdit(Model $record): bool
    {
        return static::userHasPermission(static::$permission)
            && SubscriptionAccess::allows(static::subscriptionFeature(), mutate: true);
    }

    public static function canDelete(Model $record): bool
    {
        return static::canCreate();
    }

    public static function canDeleteAny(): bool
    {
        return static::canCreate();
    }

    protected static function subscriptionFeature(): ?string
    {
        return static::$subscriptionFeature ?? null;
    }

    protected static function userHasPermission(string $permission): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->isPlatformOperator() || $user->can($permission);
    }
}
