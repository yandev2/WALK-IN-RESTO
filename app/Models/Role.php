<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const OWNER = 'owner';
    public const KASIR = 'kasir';
    public const DAPUR = 'dapur';

    /**
     * System roles that cannot be assigned to staff by restaurant owner.
     *
     * @var list<string>
     */
    public const LOCKED_NAMES = ['owner', 'super_admin', 'founder', 'panel_user'];

    /**
     * Mandatory restaurant roles that must exist and cannot be deleted.
     *
     * @var list<string>
     */
    public const MANDATORY_ROLES = [self::OWNER, self::KASIR, self::DAPUR];

    /**
     * Roles protected from deletion.
     *
     * @var list<string>
     */
    public const PROTECTED_DELETE_ROLES = [
        self::OWNER,
        self::KASIR,
        self::DAPUR,
        'super_admin',
        'founder',
        'panel_user',
    ];

    /**
     * Roles protected from renaming.
     *
     * @var list<string>
     */
    public const UNRENAMABLE_ROLES = [
        self::OWNER,
        self::KASIR,
        self::DAPUR,
        'super_admin',
        'founder',
        'panel_user',
    ];

    protected static function booted(): void
    {
        static::updating(function (self $role): bool {
            if ($role->isProtectedFromRenaming() && $role->isDirty('name')) {
                return false;
            }

            return true;
        });

        static::deleting(function (self $role): bool {
            return ! $role->isProtectedFromDeletion();
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function isOwnerRole(): bool
    {
        return ($this->getOriginal('name') ?? $this->name) === self::OWNER;
    }

    public function isMandatoryRole(): bool
    {
        $name = $this->getOriginal('name') ?? $this->name;

        return in_array($name, self::MANDATORY_ROLES, true);
    }

    public function isProtectedFromDeletion(): bool
    {
        $name = $this->getOriginal('name') ?? $this->name;

        return in_array($name, self::PROTECTED_DELETE_ROLES, true);
    }

    public function isProtectedFromRenaming(): bool
    {
        $name = $this->getOriginal('name') ?? $this->name;

        return in_array($name, self::UNRENAMABLE_ROLES, true);
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
