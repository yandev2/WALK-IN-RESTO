<?php

namespace App\Filament\Blogger\Resources\BlogCategories\Schemas;

use App\Filament\Blogger\Forms\TranslationTabs;
use App\Support\Locales;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        $makeIsLocaleTouched = function (string $locale) {
            return function (Get $get) use ($locale): bool {
                $name = trim((string) ($get("{$locale}.name") ?? ''));
                $slug = trim((string) ($get("{$locale}.slug") ?? ''));
                $metaTitle = trim((string) ($get("{$locale}.meta_title") ?? ''));
                $metaDesc = trim((string) ($get("{$locale}.meta_description") ?? ''));

                return $name !== '' || $slug !== '' || $metaTitle !== '' || $metaDesc !== '';
            };
        };

        $hasAnyLocaleContent = function (Get $get): bool {
            foreach (Locales::all() as $loc) {
                $name = trim((string) ($get("{$loc}.name") ?? ''));
                $slug = trim((string) ($get("{$loc}.slug") ?? ''));
                $metaTitle = trim((string) ($get("{$loc}.meta_title") ?? ''));
                $metaDesc = trim((string) ($get("{$loc}.meta_description") ?? ''));
                if ($name !== '' || $slug !== '' || $metaTitle !== '' || $metaDesc !== '') {
                    return true;
                }
            }

            return false;
        };

        return $schema
            ->columns(1)
            ->components([
                Flex::make([
                    Grid::make(1)
                        ->schema([
                            TranslationTabs::make('Konten Kategori', function (string $locale) use ($makeIsLocaleTouched, $hasAnyLocaleContent) {
                                $isLocaleTouched = $makeIsLocaleTouched($locale);

                                return [
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make("{$locale}.name")
                                                ->label('Nama Kategori')
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
                                                ->helperText('Slug untuk URL kategori (otomatis dari nama).'),
                                        ]),
                                ];
                            }),
                            TranslationTabs::make('SEO Kategori', function (string $locale) {
                                return [
                                    TextInput::make("{$locale}.meta_title")
                                        ->label('Meta Title')
                                        ->live(onBlur: true)
                                        ->maxLength(70)
                                        ->helperText('Maks. 70 karakter.'),
                                    Textarea::make("{$locale}.meta_description")
                                        ->label('Meta Description')
                                        ->maxLength(160)
                                        ->rows(2)
                                        ->helperText('Ringkasan kategori di search engine (maks. 160 karakter).'),
                                    TextInput::make("{$locale}.robots")
                                        ->label('Robots')
                                        ->default('index,follow'),
                                ];
                            }),
                        ]),
                    Section::make('Pengaturan')
                        ->columns(1)
                        ->columnSpan(1)
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
                ])->from('md'),
            ]);
    }
}
