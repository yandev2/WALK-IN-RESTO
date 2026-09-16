<?php

namespace App\Filament\Blogger\Resources\Bloggers\Schemas;

use App\Models\User;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BloggerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        // Left: Account Information
                        Grid::make(1)
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->schema([
                                Section::make('Informasi Akun Penulis')
                                    ->description('Data profil dan informasi kredensial penulis.')
                                    ->icon(Heroicon::OutlinedUser)
                                    ->columns(['default' => 1, 'sm' => 2])
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Nama Lengkap')
                                            ->weight('bold')
                                            ->columnSpanFull(),

                                        TextEntry::make('username')
                                            ->label('Username')
                                            ->badge()
                                            ->color('gray')
                                            ->copyable(),

                                        TextEntry::make('email')
                                            ->label('Alamat Email')
                                            ->icon(Heroicon::OutlinedEnvelope)
                                            ->copyable(),

                                        TextEntry::make('role')
                                            ->label('Peran Akses')
                                            ->badge()
                                            ->color('primary')
                                            ->state('Penulis Blog (Blogger)'),

                                        IconEntry::make('is_active')
                                            ->label('Status Akun')
                                            ->boolean(),
                                    ]),
                            ]),

                        // Right: Avatar & Metrics
                        Grid::make(1)
                            ->columnSpan(['default' => 1, 'lg' => 1])
                            ->schema([
                                Section::make('Foto Profil')
                                    ->icon(Heroicon::OutlinedPhoto)
                                    ->schema([
                                        ImageEntry::make('avatar_path')
                                            ->hiddenLabel()
                                            ->disk('public')
                                            ->circular()
                                            ->imageSize(120)
                                            ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),
                                    ]),

                                Section::make('Statistik Konten')
                                    ->icon(Heroicon::OutlinedChartBar)
                                    ->schema([
                                        TextEntry::make('blog_posts_count')
                                            ->label('Total Artikel')
                                            ->state(fn (User $record): int => $record->blogPosts()->count())
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('total_views')
                                            ->label('Total Tayangan')
                                            ->state(fn (User $record): string => number_format((int) $record->blogPosts()->sum('views_count')).' Views')
                                            ->badge()
                                            ->color('success'),

                                        TextEntry::make('created_at')
                                            ->label('Bergabung Sejak')
                                            ->dateTime('d F Y, H:i'),

                                        TextEntry::make('updated_at')
                                            ->label('Terakhir Diperbarui')
                                            ->dateTime('d F Y, H:i'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
