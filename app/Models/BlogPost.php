<?php

namespace App\Models;

use App\Enums\BlogPostStatus;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    public array $translatedAttributes = [
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

    protected $fillable = [
        'blog_category_id',
        'author_id',
        'status',
        'featured_image',
        'og_image',
        'published_at',
        'reading_time_minutes',
        'is_featured',
        'sort_order',
        'is_active',
        'views_count',
        'likes_count',
        'comments_count',
    ];

    protected function casts(): array
    {
        return [
            'status' => BlogPostStatus::class,
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function blogCategory(): BelongsTo
    {
        return $this->category();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(BlogLike::class);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) {
                $q->where('status', BlogPostStatus::Published)
                    ->orWhere(function (Builder $q) {
                        $q->where('status', BlogPostStatus::Scheduled)
                            ->where('published_at', '<=', now());
                    });
            })
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeForLocale(Builder $query, ?string $locale = null): Builder
    {
        $locale ??= app()->getLocale();

        return $query->whereHas('translations', function (Builder $q) use ($locale) {
            $q->where('locale', $locale)
                ->whereNotNull('title')
                ->where('title', '!=', '');
        });
    }

    /**
     * Transition any scheduled posts whose publish time has arrived to published status.
     */
    public static function publishDueScheduled(): int
    {
        return static::query()
            ->where('status', BlogPostStatus::Scheduled)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => BlogPostStatus::Published]);
    }
}
