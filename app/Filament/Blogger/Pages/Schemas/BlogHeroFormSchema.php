<?php

namespace App\Filament\Blogger\Pages\Schemas;

use App\Filament\Blogger\Forms\TranslationTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BlogHeroFormSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Tampilan Banner & Latar Belakang')
                    ->description('Atur gambar latar hero banner, kegelapan backdrop overlay, dan pill kategori.')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->schema([
                        FileUpload::make('banner_image')
                            ->label('Gambar Latar Belakang Banner')
                            ->helperText('Rekomendasi rasio lanskap 1920×800 px. Jika dikosongkan, akan menggunakan latar belakang ambient mesh neon default.')
                            ->image()
                            ->imageEditor()
                            ->directory('blog/banner')
                            ->disk('public')
                            ->visibility('public')
                            ->imagePreviewHeight('180')
                            ->nullable()
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('overlay_opacity')
                                ->label('Kegelapan Overlay / Backdrop (%)')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->default(60)
                                ->suffix('%')
                                ->helperText('Persentase kegelapan overlay di atas gambar agar teks dan badge tetap kontras dan mudah dibaca.'),
                            Toggle::make('show_quick_categories')
                                ->label('Tampilkan Pill Kategori Cepat')
                                ->helperText('Tampilkan deretan pills kategori kuliner aktif di bawah kotak pencarian.')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),

                Section::make('Konten Teks Hero Banner')
                    ->description('Atur teks badge melayang, judul utama, subjudul/deskripsi, dan teks kotak pencarian per bahasa (ID & EN).')
                    ->icon(Heroicon::OutlinedSparkles)
                    ->schema([
                        TranslationTabs::make('Konten Hero', function (string $locale) {
                            return [
                                Grid::make(1)->schema([
                                    TextInput::make("{$locale}.badge_text")
                                        ->label('Teks Floating Badge / Eyebrow')
                                        ->maxLength(150)
                                        ->placeholder($locale === 'id' ? '✨ Jelajahi Kuliner Nusantara' : '✨ Explore Culinary Trends')
                                        ->helperText('Teks pill melayang di bagian paling atas hero banner.'),
                                    TextInput::make("{$locale}.title")
                                        ->label('Judul Utama Hero')
                                        ->maxLength(255)
                                        ->placeholder($locale === 'id' ? 'Blog Kuliner & Restoran' : 'Culinary & Restaurant Blog')
                                        ->helperText('Judul besar utama dengan efek gradien.'),
                                    Textarea::make("{$locale}.subtitle")
                                        ->label('Subjudul / Deskripsi')
                                        ->rows(3)
                                        ->maxLength(500)
                                        ->placeholder($locale === 'id' ? 'Temukan artikel, tips kuliner, panduan restoran, dan inspirasi menu terbaik.' : 'Explore inspiring food stories, culinary guides, and dining trends.')
                                        ->helperText('Deskripsi penjelas di bawah judul hero banner.'),
                                    TextInput::make("{$locale}.search_placeholder")
                                        ->label('Teks Placeholder Kotak Pencarian')
                                        ->maxLength(150)
                                        ->placeholder($locale === 'id' ? 'Cari artikel kuliner, resep, atau restoran…' : 'Search culinary articles, recipes, or restaurants…')
                                        ->helperText('Teks panduan di dalam input pencarian artikel.'),
                                ]),
                            ];
                        }),
                    ]),
            ]);
    }
}
