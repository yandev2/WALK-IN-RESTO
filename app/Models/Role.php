<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const OWNER = 'owner';

    /**
     * @var list<string>
     */
    public const LOCKED_NAMES = ['owner', 'super_admin', 'founder', 'panel_user'];

    protected static function booted(): void
    {
        static::updating(function (self $role): bool {
            if ($role->getOriginal('name') === self::OWNER && $role->isDirty('name')) {
                return false;
            }

            return true;
        });

        static::deleting(function (self $role): bool {
            return ! $role->isOwnerRole();
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function isOwnerRole(): bool
    {
        return $this->name === self::OWNER;
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeAssignableToStaff(Builder $query): Builder
    {
        return $query->whereNotIn('name', self::LOCKED_NAMES);
    }
}
