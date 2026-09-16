<?php

namespace App\Filament\Blogger\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SeoFields
{
    /**
     * @return array<int, mixed>
     */
    public static function forLocale(string $locale, bool $withSlug = false): array
    {
        $fields = [];

        if ($withSlug) {
            $fields[] = TextInput::make("{$locale}.slug")
                ->label('Slug')
                ->required()
                ->maxLength(255);
        }

        $fields[] = TextInput::make("{$locale}.meta_title")
            ->label('Meta Title')
            ->maxLength(100)
            ->helperText('Judul artikel untuk hasil pencarian Google (maks 100 karakter).');

        $fields[] = Textarea::make("{$locale}.meta_description")
            ->label('Meta Description')
            ->maxLength(200)
            ->rows(2)
            ->helperText('Deskripsi singkat ringkasan artikel di search engine.');

        $fields[] = TextInput::make("{$locale}.meta_keywords")
            ->label('Meta Keywords')
            ->maxLength(255)
            ->helperText('Kata kunci dipisah dengan koma.');

        $fields[] = TextInput::make("{$locale}.og_title")
            ->label('Open Graph Title')
            ->maxLength(70)
            ->helperText('Judul pratinjau saat dibagikan ke media sosial (WhatsApp, FB, Twitter).');

        $fields[] = Textarea::make("{$locale}.og_description")
            ->label('Open Graph Description')
            ->rows(2)
            ->helperText('Deskripsi pratinjau media sosial.');

        $fields[] = TextInput::make("{$locale}.canonical_url")
            ->label('Canonical URL')
            ->url()
            ->helperText('URL rujukan utama jika artikel diambil dari sumber lain.');

        $fields[] = TextInput::make("{$locale}.robots")
            ->label('Robots Indexing')
            ->default('index,follow');

        $fields[] = TextInput::make("{$locale}.focus_keyword")
            ->label('Focus Keyword')
            ->helperText('Kata kunci utama artikel untuk optimasi SEO.');

        return $fields;
    }
}
