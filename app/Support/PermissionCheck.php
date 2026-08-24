<?php

namespace App\Support;

use App\Models\User;
use Spatie\Permission\Models\Permission;

final class PermissionCheck
{
    public static function allows(User $user, string $permission): bool
    {
        if (! Permission::query()->where('name', $permission)->where('guard_name', 'web')->exists()) {
            return false;
        }

        return $user->can($permission);
    }

    /**
     * @param  list<string>  $permissions
     */
    public static function allowsAny(User $user, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (self::allows($user, $permission)) {
                return true;
            }
        }

        return false;
    }
}
