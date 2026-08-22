<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutletClosedDate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'closed_on',
        'reason',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_on' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
