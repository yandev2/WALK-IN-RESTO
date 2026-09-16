<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model implements TranslatableContract
{
    use Translatable;

    public array $translatedAttributes = [
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'robots',
    ];

    protected $fillable = [
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function scopeForLocale(Builder $query, ?string $locale = null): Builder
    {
        $locale ??= app()->getLocale();

        return $query->whereHas('translations', function (Builder $q) use ($locale) {
            $q->where('locale', $locale)
                ->whereNotNull('name')
                ->where('name', '!=', '');
        });
    }
}
