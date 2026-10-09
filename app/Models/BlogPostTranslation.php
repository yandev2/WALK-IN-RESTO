<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPostTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blog_post_id',
        'locale',
        'slug',
        'title',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'featured_image_alt',
        'canonical_url',
        'robots',
        'focus_keyword',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $translation): void {
            if (blank($translation->slug) && filled($translation->title)) {
                $translation->slug = Str::slug($translation->title);
            } elseif (filled($translation->slug)) {
                $translation->slug = Str::slug($translation->slug);
            }
        });
    }

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}
