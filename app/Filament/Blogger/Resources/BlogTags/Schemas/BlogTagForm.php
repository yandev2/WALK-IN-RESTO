<?php

namespace App\Filament\Blogger\Resources\BlogTags\Schemas;

use App\Filament\Blogger\Forms\TranslationTabs;
use App\Support\Locales;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Flex::make([
                    TranslationTabs::make('Konten Tag', function (string $locale) {
                        $isLocaleTouched = function (Get $get) use ($locale): bool {
                            $name = trim((string) ($get("{$locale}.name") ?? ''));
                            $slug = trim((string) ($get("{$locale}.slug") ?? ''));

                            return $name !== '' || $slug !== '';
                        };

                        $hasAnyLocaleContent = function (Get $get): bool {
                            foreach (Locales::all() as $loc) {
                                $name = trim((string) ($get("{$loc}.name") ?? ''));
                                $slug = trim((string) ($get("{$loc}.slug") ?? ''));
                                if ($name !== '' || $slug !== '') {
                                    return true;
                                }
                            }

                            return false;
                        };

                        return [
                            TextInput::make("{$locale}.name")
                                ->label('Nama Tag')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, Set $set, Get $get) use ($locale) {
                                    if (blank($get("{$locale}.slug"))) {
                                        $set("{$locale}.slug", Str::slug($state ?? ''));
                                    }
                                })
                                ->required(function (Get $get) use ($isLocaleTouched, $hasAnyLocaleContent, $locale): bool {
                                    if ($isLocaleTouched($get)) {
                                        return true;
                                    }

                                    return ! $hasAnyLocaleContent($get) && $locale === Locales::default();
                                })
                                ->maxLength(255)
                                ->helperText('Wajib diisi untuk bahasa utama atau bahasa yang sedang ditulis.'),
                            TextInput::make("{$locale}.slug")
                                ->label('Slug URL')
                                ->required($isLocaleTouched)
                                ->maxLength(255)
                                ->helperText('Slug URL untuk tag (otomatis dari nama).'),
                        ];
                    }),

                    Section::make('Pengaturan')
                        ->columns(1)
                        ->schema([
                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0)
                                ->required(),
                            Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true)
                                ->required(),
                        ])->grow(false),
                ]),
            ]);
    }
}
