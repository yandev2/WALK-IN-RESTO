<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantReview extends Model
{
    use Concerns\ScopedToRestaurant;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'visit_id',
        'order_id',
        'customer_name',
        'rating',
        'comment',
        'is_published',
        'internal_notes',
        'submitted_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function displayName(): string
    {
        return filled($this->customer_name) ? $this->customer_name : 'Tamu';
    }

    public function starsString(): string
    {
        return str_repeat('⭐', max(1, min(5, (int) $this->rating)));
    }

    public function sentimentLabel(): string
    {
        return match (true) {
            $this->rating >= 4 => 'Puas',
            $this->rating === 3 => 'Netral',
            default => 'Perlu Perhatian',
        };
    }

    public function sentimentColor(): string
    {
        return match (true) {
            $this->rating >= 4 => 'success',
            $this->rating === 3 => 'warning',
            default => 'danger',
        };
    }

    public function customerPhone(): ?string
    {
        return $this->visit?->customer_wa ?: $this->order?->receipt_wa_snapshot;
    }

    public function customer(): ?Customer
    {
        $phone = $this->customerPhone();
        if (blank($phone)) {
            return null;
        }

        $clean = \App\Support\WhatsAppNumber::normalize($phone);
        if (! $clean) {
            return null;
        }

        return Customer::withoutRestaurantScope()
            ->where('restaurant_id', $this->restaurant_id)
            ->where('phone', $clean)
            ->first();
    }

    public function defaultWaFollowUpMessage(): string
    {
        $name = $this->displayName();
        $restoName = $this->restaurant?->name ?? 'Restoran Kami';

        if ($this->rating >= 4) {
            return "Halo Kak {$name}, terima kasih banyak telah bersantap di {$restoName} dan memberikan ulasan bintang {$this->rating}! Senang sekali bisa melayani Kakak. Semoga hari Kakak menyenangkan dan kami tunggu kunjungan berikutnya! ✨";
        }

        return "Halo Kak {$name}, kami dari tim manajemen {$restoName}. Terima kasih atas ulasan dan masukan yang Kakak sampaikan. Kami memohon maaf jika pengalaman bersantap Kakak belum maksimal. Kami sangat menghargai masukan Kakak dan ingin menindaklanjutinya agar dapat melayani lebih baik lagi.";
    }

    public function waLink(?string $message = null): ?string
    {
        $phone = $this->customerPhone();
        if (blank($phone)) {
            return null;
        }

        $clean = preg_replace('/\D/', '', $phone);
        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        }

        $url = 'https://wa.me/'.$clean;
        $msg = $message ?? $this->defaultWaFollowUpMessage();

        if (filled($msg)) {
            $url .= '?text='.urlencode($msg);
        }

        return $url;
    }
}
