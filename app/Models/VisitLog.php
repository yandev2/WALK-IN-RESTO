<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VisitLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'page_key',
        'visitable_type',
        'visitable_id',
        'locale',
        'path',
        'ip_address',
        'user_agent',
        'referer',
        'visitor_hash',
        'session_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function visitable(): MorphTo
    {
        return $this->morphTo();
    }
}
