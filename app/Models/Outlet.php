<?php

namespace App\Models;

use App\Models\Concerns\PurgesPublicDiskFiles;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Outlet extends Model
{
    use Concerns\ScopedToRestaurant;
    use LogsActivity;
    use PurgesPublicDiskFiles;

    protected $fillable = [
        'restaurant_id',
        'public_id',
        'code',
        'name',
        'address',
        'phone',
        'is_default',
        'is_open',
        'is_active',
        'latitude',
        'longitude',
        'geofence_radius_m',
        'gps_accuracy_max_m',
        'qris_image_path',
        'pb1_pct',
        'service_pct',
        'tax_mode',
        'claim_ttl_minutes',
        'awaiting_cashier_ttl_minutes',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'address',
                'phone',
                'is_open',
                'is_active',
                'pb1_pct',
                'service_pct',
                'tax_mode',
                'geofence_radius_m',
                'gps_accuracy_max_m',
                'claim_ttl_minutes',
                'awaiting_cashier_ttl_minutes',
            ])
            ->logOnlyDirty()
            ->useLogName('outlet')
            ->dontLogEmptyChanges();
    }

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_open' => 'boolean',
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'pb1_pct' => 'decimal:2',
            'service_pct' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $outlet): void {
            if (blank($outlet->public_id)) {
                $outlet->public_id = (string) Str::ulid();
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'outlet_users')
            ->withPivot('restaurant_id');
    }

    public function kdsStations(): HasMany
    {
        return $this->hasMany(KdsStation::class);
    }

    public function diningTables(): HasMany
    {
        return $this->hasMany(DiningTable::class);
    }

    public function menuCategories(): HasMany
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function operatingHours(): HasMany
    {
        return $this->hasMany(OutletOperatingHour::class)->orderBy('day_of_week');
    }

    public function closedDates(): HasMany
    {
        return $this->hasMany(OutletClosedDate::class);
    }

    public function isOpenNow(?CarbonInterface $at = null): bool
    {
        if (! $this->is_active || ! $this->is_open) {
            return false;
        }

        $timezone = $this->restaurant?->timezone ?: 'Asia/Jakarta';
        $at = $at?->copy()->timezone($timezone) ?? now($timezone);

        $closedToday = $this->relationLoaded('closedDates')
            ? $this->closedDates->contains(fn (OutletClosedDate $date): bool => $date->closed_on?->isSameDay($at))
            : $this->closedDates()->whereDate('closed_on', $at->toDateString())->exists();

        if ($closedToday) {
            return false;
        }

        $hours = $this->relationLoaded('operatingHours')
            ? $this->operatingHours->firstWhere('day_of_week', $at->dayOfWeek)
            : $this->operatingHours()->where('day_of_week', $at->dayOfWeek)->first();

        if (! $hours) {
            return true;
        }

        if ($hours->is_closed || blank($hours->opens_at) || blank($hours->closes_at)) {
            return false;
        }

        $open = $at->copy()->setTimeFromTimeString((string) $hours->opens_at);
        $close = $at->copy()->setTimeFromTimeString((string) $hours->closes_at);

        if ($close->lessThanOrEqualTo($open)) {
            return $at->greaterThanOrEqualTo($open) || $at->lessThanOrEqualTo($close);
        }

        return $at->betweenIncluded($open, $close);
    }

    public function todayHours(?CarbonInterface $at = null): ?OutletOperatingHour
    {
        $timezone = $this->restaurant?->timezone ?: 'Asia/Jakarta';
        $at = $at?->copy()->timezone($timezone) ?? now($timezone);

        return $this->operatingHours->firstWhere('day_of_week', $at->dayOfWeek);
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['qris_image_path'];
    }
}
