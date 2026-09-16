<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilamentTranslatable
{
    /**
     * Resolve a translated attribute for Filament labels/titles.
     */
    public static function label(?Model $record, string $attribute = 'name', ?string $locale = null): string
    {
        if (! $record) {
            return '';
        }

        $locale ??= app()->getLocale();

        if (method_exists($record, 'translate')) {
            $translated = $record->translate($locale)?->{$attribute};

            if (filled($translated)) {
                return (string) $translated;
            }

            foreach (Locales::all() as $fallbackLocale) {
                $fallbackTranslation = $record->translate($fallbackLocale)?->{$attribute};
                if (filled($fallbackTranslation)) {
                    return (string) $fallbackTranslation;
                }
            }

            if ($record->relationLoaded('translations')) {
                $any = $record->translations->first(fn ($t) => filled($t->{$attribute}));
                if ($any) {
                    return (string) $any->{$attribute};
                }
            }
        }

        $fallback = $record->getAttribute($attribute);

        if (filled($fallback)) {
            return (string) $fallback;
        }

        return (string) $record->getKey();
    }

    /**
     * Resolve a raw translated attribute value.
     */
    public static function attribute(?Model $record, string $attribute, ?string $locale = null): mixed
    {
        if (! $record) {
            return null;
        }

        $locale ??= app()->getLocale();

        if (method_exists($record, 'translate')) {
            $translated = $record->translate($locale)?->{$attribute};

            if ($translated !== null) {
                return $translated;
            }

            foreach (Locales::all() as $fallbackLocale) {
                $fallbackTranslation = $record->translate($fallbackLocale)?->{$attribute};
                if ($fallbackTranslation !== null) {
                    return $fallbackTranslation;
                }
            }

            if ($record->relationLoaded('translations')) {
                $any = $record->translations->first(fn ($t) => filled($t->{$attribute}));
                if ($any) {
                    return $any->{$attribute};
                }
            }
        }

        return $record->getAttribute($attribute);
    }

    /**
     * Constrain a query to search a translated attribute via whereHas('translations').
     *
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public static function search(Builder $query, string $attribute, ?string $search): Builder
    {
        $term = trim((string) $search);

        if ($term === '') {
            return $query;
        }

        $like = '%'.mb_strtolower($term).'%';

        return $query->whereHas('translations', function (Builder $translations) use ($attribute, $like): void {
            $translations->whereRaw('LOWER('.$translations->qualifyColumn($attribute).') LIKE ?', [$like]);
        });
    }

    /**
     * Filament TextColumn searchable() query callback.
     *
     * @return callable(Builder<\Illuminate\Database\Eloquent\Model>, string): Builder<\Illuminate\Database\Eloquent\Model>
     */
    public static function searchableQuery(string $attribute): callable
    {
        return static function (Builder $query, string $search) use ($attribute): Builder {
            return self::search($query, $attribute, $search);
        };
    }

    /**
     * Filament Select::relationship() modifyQueryUsing callback for translated attributes.
     *
     * @return callable(Builder<\Illuminate\Database\Eloquent\Model>, ?string): Builder<\Illuminate\Database\Eloquent\Model>
     */
    public static function relationshipSearch(string $attribute): callable
    {
        return static function (Builder $query, ?string $search) use ($attribute): Builder {
            if (! filled($search)) {
                return $query;
            }

            return self::search($query, $attribute, $search);
        };
    }
}
