<?php

namespace App\Models;

use App\Models\Concerns\PurgesPublicDiskFiles;
use App\Support\CmsMedia;
use Database\Factories\UserFactory;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, LogsActivity, Notifiable, PurgesPublicDiskFiles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar_path',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'username',
                'is_active',
            ])
            ->logOnlyDirty()
            ->useLogName('user')
            ->dontLogEmptyChanges();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (self $user): bool {
            return ! $user->isRestaurantOwner();
        });

        static::forceDeleting(function (self $user): bool {
            return ! $user->isRestaurantOwner();
        });
    }

    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_users')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function outlets(): BelongsToMany
    {
        return $this->belongsToMany(Outlet::class, 'outlet_users')
            ->withPivot('restaurant_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->trashed() || ! $this->is_active) {
            return false;
        }

        if ($panel->getId() === 'founder') {
            return $this->isPlatformOperator();
        }

        return DB::table('model_has_roles')
            ->where('model_type', self::class)
            ->where('model_id', $this->id)
            ->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasGlobalRole('super_admin');
    }

    public function isFounder(): bool
    {
        return $this->hasGlobalRole('founder');
    }

    public function isPlatformOperator(): bool
    {
        return $this->isSuperAdmin() || $this->isFounder();
    }

    public function isRestaurantOwner(?Restaurant $restaurant = null): bool
    {
        $restaurant ??= Filament::getTenant() instanceof Restaurant
            ? Filament::getTenant()
            : null;

        $query = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', self::class)
            ->where('model_has_roles.model_id', $this->id)
            ->where('roles.name', 'owner');

        if ($restaurant instanceof Restaurant) {
            $query->where('roles.restaurant_id', $restaurant->getKey());
        }

        return $query->exists();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return CmsMedia::url($this->avatar_path);
    }

    /**
     * @param  Builder<User>  $query
     * @param  string|list<string>  $roles
     * @return Builder<User>
     */
    public function scopeWhereHasGlobalRole(Builder $query, string|array $roles): Builder
    {
        $names = array_values((array) $roles);

        return $query->whereExists(function ($sub) use ($names): void {
            $sub->selectRaw('1')
                ->from('model_has_roles')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->whereColumn('model_has_roles.model_id', 'users.id')
                ->where('model_has_roles.model_type', self::class)
                ->whereIn('roles.name', $names);
        });
    }

    /**
     * @param  Builder<User>  $query
     * @param  string|list<string>  $roles
     * @return Builder<User>
     */
    public function scopeWhereHasRestaurantRole(Builder $query, string|array $roles, int $restaurantId): Builder
    {
        $names = array_values((array) $roles);

        return $query->whereExists(function ($sub) use ($names, $restaurantId): void {
            $sub->selectRaw('1')
                ->from('model_has_roles')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->whereColumn('model_has_roles.model_id', 'users.id')
                ->where('model_has_roles.model_type', self::class)
                ->whereIn('roles.name', $names)
                ->where('roles.restaurant_id', $restaurantId);
        });
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($panel->getId() === 'founder') {
            return collect();
        }

        if ($this->isPlatformOperator()) {
            return Restaurant::query()
                ->orderBy('name')
                ->get();
        }

        return $this->restaurants()
            ->where('restaurants.is_active', true)
            ->wherePivot('is_active', true)
            ->limit(1)
            ->get();
    }

    public function assertCanJoinRestaurant(Restaurant $restaurant): void
    {
        if ($this->isBoundToOtherRestaurant($restaurant)) {
            throw ValidationException::withMessages([
                'email' => 'Akun ini sudah terikat ke restoran lain.',
            ]);
        }
    }

    public function isBoundToOtherRestaurant(Restaurant $restaurant): bool
    {
        return $this->restaurants()
            ->whereKeyNot($restaurant->getKey())
            ->exists();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if (! $tenant instanceof Restaurant) {
            return false;
        }

        if ($this->isPlatformOperator()) {
            return true;
        }

        return $this->restaurants()
            ->whereKey($tenant->getKey())
            ->wherePivot('is_active', true)
            ->exists();
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['avatar_path'];
    }

    private function hasGlobalRole(string $role): bool
    {
        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', self::class)
            ->where('model_has_roles.model_id', $this->id)
            ->where('roles.name', $role)
            ->exists();
    }
}
