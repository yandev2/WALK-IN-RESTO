<?php

namespace App\Filament\Blogger\Resources\Bloggers\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class BloggerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        // Left Column (Main Account Data)
                        Grid::make(1)
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->schema([
                                Section::make('Identitas Penulis')
                                    ->description('Kredensial login dan profil identitas penulis blog.')
                                    ->icon(Heroicon::OutlinedUser)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nama Lengkap')
                                            ->prefixIcon(Heroicon::OutlinedUser)
                                            ->placeholder('Masukkan nama lengkap penulis')
                                            ->required()
                                            ->maxLength(255),

                                        Grid::make(['default' => 1, 'sm' => 2])
                                            ->schema([
                                                TextInput::make('username')
                                                    ->label('Username')
                                                    ->prefixIcon(Heroicon::OutlinedAtSymbol)
                                                    ->placeholder('cth. sitirahma')
                                                    ->required()
                                                    ->maxLength(64)
                                                    ->unique(ignoreRecord: true)
                                                    ->alphaDash()
                                                    ->helperText('Hanya huruf, angka, strip (-), dan garis bawah (_).'),

                                                TextInput::make('email')
                                                    ->label('Alamat Email')
                                                    ->prefixIcon(Heroicon::OutlinedEnvelope)
                                                    ->placeholder('cth. siti@walk-in-resto.com')
                                                    ->email()
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique(ignoreRecord: true)
                                                    ->helperText('Digunakan untuk notifikasi & akses login.'),
                                            ]),

                                        TextInput::make('password')
                                            ->label('Kata Sandi (Password)')
                                            ->prefixIcon(Heroicon::OutlinedKey)
                                            ->password()
                                            ->revealable()
                                            ->required(fn (string $operation): bool => $operation === 'create')
                                            ->dehydrated(fn (?string $state): bool => filled($state))
                                            ->helperText(fn (string $operation): ?string => $operation === 'edit'
                                                ? 'Kosongkan jika tidak ingin mengubah kata sandi saat ini.'
                                                : 'Disarankan minimal 8 karakter kombinasi huruf dan angka.')
                                            ->maxLength(255),
                                    ]),
                            ]),

                        // Right Column (Avatar, Status, and Stats)
                        Grid::make(1)
                            ->columnSpan(['default' => 1, 'lg' => 1])
                            ->schema([
                                Section::make('Foto Profil')
                                    ->description('Rasio 1:1, ditampilkan pada artikel & author box.')
                                    ->icon(Heroicon::OutlinedPhoto)
                                    ->schema([
                                        FileUpload::make('avatar_path')
                                            ->hiddenLabel()
                                            ->image()
                                            ->avatar()
                                            ->imageAspectRatio('1:1')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->automaticallyResizeImagesMode('cover')
                                            ->automaticallyResizeImagesToWidth('500')
                                            ->automaticallyResizeImagesToHeight('500')
                                            ->automaticallyUpscaleImagesWhenResizing(false)
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['1:1'])
                                            ->imagePreviewHeight('150')
                                            ->directory('users/avatars')
                                            ->disk('public')
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Format: JPG, PNG, WEBP (maks. 5 MB).'),
                                    ]),

                                Section::make('Status Akses')
                                    ->description('Kontrol hak akses login ke panel blog.')
                                    ->icon(Heroicon::OutlinedShieldCheck)
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Akun Aktif')
                                            ->helperText('Jika nonaktif, penulis tidak dapat masuk ke panel blog.')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->onIcon(Heroicon::OutlinedCheck)
                                            ->offIcon(Heroicon::OutlinedXMark),
                                    ]),

                                Section::make('Statistik Penulis')
                                    ->description('Ringkasan kontribusi artikel di blog.')
                                    ->icon(Heroicon::OutlinedChartBar)
                                    ->visible(fn (string $operation): bool => $operation === 'edit')
                                    ->schema([
                                        Placeholder::make('stat_articles')
                                            ->label('Total Artikel')
                                            ->content(fn (?User $record): HtmlString => new HtmlString(
                                                '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800">'
                                                .'<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> '
                                                .number_format($record?->blogPosts()->count() ?? 0).' Artikel</span>'
                                            )),

                                        Placeholder::make('stat_views')
                                            ->label('Total Pembaca')
                                            ->content(fn (?User $record): HtmlString => new HtmlString(
                                                '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">'
                                                .'<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> '
                                                .number_format((int) ($record?->blogPosts()->sum('views_count') ?? 0)).' Tayangan</span>'
                                            )),

                                        Placeholder::make('stat_registered')
                                            ->label('Bergabung Sejak')
                                            ->content(fn (?User $record): string => $record?->created_at ? $record->created_at->translatedFormat('d F Y, H:i') : '-'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
