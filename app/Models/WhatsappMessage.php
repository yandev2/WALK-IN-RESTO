<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappMessage extends Model
{
    use BelongsToRestaurantAndOutlet;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'visit_id',
        'order_id',
        'receipt_id',
        'requested_by_user_id',
        'kind',
        'provider',
        'to_wa',
        'body',
        'media_path',
        'status',
        'attempts',
        'provider_ref',
        'provider_payload',
        'last_error',
        'queued_at',
        'sent_at',
        'failed_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'provider_ref',
        'provider_payload',
    ];

    protected function casts(): array
    {
        return [
            'provider_payload' => 'array',
            'queued_at' => 'datetime',
            'sent_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(OrderReceipt::class, 'receipt_id');
    }
}
