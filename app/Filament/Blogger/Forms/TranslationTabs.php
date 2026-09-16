<?php

namespace App\Filament\Blogger\Forms;

use App\Support\Locales;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class TranslationTabs
{
    /**
     * @param  callable(string): array<int, mixed>  $fieldsForLocale
     */
    public static function make(string $label, callable $fieldsForLocale): Tabs
    {
        $tabs = [];

        foreach (Locales::all() as $locale) {
            $tabs[] = Tab::make(Locales::label($locale))
                ->id($locale)
                ->schema($fieldsForLocale($locale));
        }

        return Tabs::make($label)
            ->persistTabInQueryString('active_locale')
            ->tabs($tabs)
            ->columnSpanFull();
    }
}
