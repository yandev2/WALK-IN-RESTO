<?php

namespace App\Support;

class Locales
{
    /**
     * @return list<string>
     */
    public static function defaults(): array
    {
        return ['id', 'en'];
    }

    public static function default(): string
    {
        return config('translatable.locale') ?? config('app.locale', 'id');
    }

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        $locales = config('translatable.locales');

        if (is_array($locales) && count($locales) > 0) {
            return array_values(array_filter(
                $locales,
                static fn ($locale) => is_string($locale) && $locale !== '',
            ));
        }

        return self::defaults();
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            'id' => 'Indonesia',
            'en' => 'English',
        ];
    }

    public static function label(string $locale): string
    {
        return self::labels()[$locale] ?? strtoupper($locale);
    }
}
