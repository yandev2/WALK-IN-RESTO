<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class DiningTable extends Model
{
    use BelongsToRestaurantAndOutlet;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'tables';

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'code',
        'capacity',
        'area',
        'floor_x_pct',
        'floor_y_pct',
        'is_out_of_service',
        'needs_cleaning',
        'qr_version',
        'qr_secret',
        'open_visit_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'code',
                'capacity',
                'area',
                'is_out_of_service',
                'needs_cleaning',
            ])
            ->logOnlyDirty()
            ->useLogName('table')
            ->dontLogEmptyChanges();
    }

    protected function casts(): array
    {
        return [
            'is_out_of_service' => 'boolean',
            'needs_cleaning' => 'boolean',
            'floor_x_pct' => 'float',
            'floor_y_pct' => 'float',
        ];
    }

    public const FLOOR_GRID_COLUMNS = 4;

    public const FLOOR_CELL_WIDTH_PCT = 22;

    public const FLOOR_CELL_HEIGHT_PCT = 18;

    public const FLOOR_PADDING_PCT = 5;

    public static function normalizeArea(?string $area): ?string
    {
        return filled($area) ? $area : null;
    }

    public function hasFloorPosition(): bool
    {
        return $this->floor_x_pct !== null && $this->floor_y_pct !== null;
    }

    /**
     * @return array{x: float, y: float}
     */
    public static function autoGridPosition(int $index): array
    {
        $column = $index % self::FLOOR_GRID_COLUMNS;
        $row = intdiv($index, self::FLOOR_GRID_COLUMNS);

        return [
            'x' => self::FLOOR_PADDING_PCT + ($column * self::FLOOR_CELL_WIDTH_PCT),
            'y' => self::FLOOR_PADDING_PCT + ($row * self::FLOOR_CELL_HEIGHT_PCT),
        ];
    }

    /**
     * @return array{x: float, y: float}
     */
    public function displayPosition(int $index = 0): array
    {
        if ($this->hasFloorPosition()) {
            return [
                'x' => (float) $this->floor_x_pct,
                'y' => (float) $this->floor_y_pct,
            ];
        }

        return self::autoGridPosition($index);
    }

    /**
     * @return array{label: string, color: string}
     */
    public function floorStatusMeta(): array
    {
        return match ($this->floorStatus()) {
            'out_of_service' => ['label' => 'Out of service', 'color' => 'danger'],
            'cleaning' => ['label' => 'Butuh dibersihkan', 'color' => 'warning'],
            'occupied' => ['label' => 'Terisi', 'color' => 'info'],
            'ordering' => ['label' => 'Pesan belum bayar', 'color' => 'gray'],
            default => ['label' => 'Kosong', 'color' => 'success'],
        };
    }

    protected static function booted(): void
    {
        static::creating(function (self $table): void {
            if (blank($table->qr_secret)) {
                $table->qr_secret = Str::random(64);
            }

            if (blank($table->qr_version)) {
                $table->qr_version = 1;
            }

            if (! $table->hasFloorPosition()) {
                $index = self::query()
                    ->where('outlet_id', $table->outlet_id)
                    ->when(
                        self::normalizeArea($table->area) === null,
                        fn ($query) => $query->where(function ($inner): void {
                            $inner->whereNull('area')->orWhere('area', '');
                        }),
                        fn ($query) => $query->where('area', $table->area),
                    )
                    ->count();

                $position = self::autoGridPosition($index);
                $table->floor_x_pct = $position['x'];
                $table->floor_y_pct = $position['y'];
            }
        });

        static::updating(function (self $table): void {
            if ($table->isDirty('is_out_of_service') && $table->is_out_of_service && filled($table->open_visit_id)) {
                throw ValidationException::withMessages([
                    'is_out_of_service' => 'Tidak bisa menonaktifkan meja yang masih ada visit terbuka. Tutup visit dulu.',
                ]);
            }
        });

        static::deleting(function (self $table): void {
            if (filled($table->open_visit_id)) {
                throw ValidationException::withMessages([
                    'table' => 'Tidak bisa menghapus meja yang masih ada visit terbuka. Tutup visit dulu.',
                ]);
            }
        });
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function openVisit(): BelongsTo
    {
        return $this->belongsTo(Visit::class, 'open_visit_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'table_id');
    }

    public function floorStatus(): string
    {
        if ($this->is_out_of_service) {
            return 'out_of_service';
        }

        if ($this->needs_cleaning) {
            return 'cleaning';
        }

        if (filled($this->open_visit_id)) {
            $visit = $this->openVisit;
            $hasPaid = $visit?->relationLoaded('orders')
                ? $visit->orders->contains(fn (Order $order): bool => in_array($order->status, Order::ACCEPTED_STATUSES, true))
                : $visit?->orders()->whereIn('status', Order::ACCEPTED_STATUSES)->exists();

            return $hasPaid ? 'occupied' : 'ordering';
        }

        return 'available';
    }

    public function isClaimable(): bool
    {
        return $this->floorStatus() === 'available';
    }
}
