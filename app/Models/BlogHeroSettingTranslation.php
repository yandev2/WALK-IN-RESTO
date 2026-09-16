<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogHeroSettingTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blog_hero_setting_id',
        'locale',
        'badge_text',
        'title',
        'subtitle',
        'search_placeholder',
    ];

    public function blogHeroSetting(): BelongsTo
    {
        return $this->belongsTo(BlogHeroSetting::class);
    }
}
