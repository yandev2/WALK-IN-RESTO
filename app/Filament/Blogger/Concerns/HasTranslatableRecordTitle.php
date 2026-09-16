<?php

namespace App\Filament\Blogger\Concerns;

use App\Support\FilamentTranslatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

trait HasTranslatableRecordTitle
{
    /**
     * Attribute on the translation model used for record titles.
     */
    protected static function translatableTitleAttribute(): string
    {
        return static::$recordTitleAttribute ?? 'name';
    }

    public static function getRecordTitleAttribute(): ?string
    {
        // Avoid Filament SQL that treats translated attrs as base-table columns.
        return null;
    }

    public static function getRecordTitle(?Model $record): string | Htmlable | null
    {
        if (! $record) {
            return static::getModelLabel();
        }

        $label = FilamentTranslatable::label($record, static::translatableTitleAttribute());

        return filled($label) ? $label : static::getModelLabel();
    }
}
