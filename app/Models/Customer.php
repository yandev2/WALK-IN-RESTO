<?php

namespace App\Models;

use App\Models\Concerns\ScopedToRestaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Customer extends Model
{
    use ScopedToRestaurant;

    protected $fillable = [
        'public_id',
        'restaurant_id',
        'phone',
        'name',
        'tier',
        'points_balance',
        'total_spent',
        'total_orders',
        'last_visit_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'points_balance' => 'integer',
            'total_spent' => 'integer',
            'total_orders' => 'integer',
            'last_visit_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $customer): void {
            if (blank($customer->public_id)) {
                $customer->public_id = (string) Str::ulid();
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(CustomerLoyaltyPoint::class);
    }

    public function tierLabel(): string
    {
        return match (strtolower($this->tier)) {
            'silver' => 'Silver',
            'gold' => 'Gold',
            'vip' => 'VIP',
            default => 'Reguler',
        };
    }

    public function badgeColor(): string
    {
        return match (strtolower($this->tier)) {
            'vip' => 'purple',
            'gold' => 'amber',
            'silver' => 'slate',
            default => 'gray',
        };
    }

    public function formattedPhone(): string
    {
        $clean = preg_replace('/\D/', '', $this->phone);

        if (str_starts_with($clean, '62') && strlen($clean) >= 10) {
            return '+62 '.substr($clean, 2, 3).'-'.substr($clean, 5, 4).'-'.substr($clean, 9);
        }

        return $this->phone;
    }

    public function averageSpendPerOrder(): int
    {
        return $this->total_orders > 0 ? (int) round($this->total_spent / $this->total_orders) : 0;
    }

    public function lastVisitForHumans(): string
    {
        return $this->last_visit_at ? $this->last_visit_at->diffForHumans() : 'Belum berkunjung';
    }

    public function waLink(?string $message = null): ?string
    {
        $clean = preg_replace('/\D/', '', $this->phone);

        if (blank($clean)) {
            return null;
        }

        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        }

        $url = 'https://wa.me/'.$clean;

        if (filled($message)) {
            $url .= '?text='.urlencode($message);
        }

        return $url;
    }
}

