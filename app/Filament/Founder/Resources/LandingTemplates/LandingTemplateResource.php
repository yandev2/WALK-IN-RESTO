<?php

namespace App\Filament\Founder\Resources\LandingTemplates;

use App\Filament\Founder\Resources\LandingTemplates\Pages\CreateLandingTemplate;
use App\Filament\Founder\Resources\LandingTemplates\Pages\EditLandingTemplate;
use App\Filament\Founder\Resources\LandingTemplates\Pages\ListLandingTemplates;
use App\Models\LandingTemplate;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LandingTemplateResource extends Resource
{
    protected static ?string $model = LandingTemplate::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Template landing';

    protected static ?string $pluralModelLabel = 'template landing';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 13;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && $user->isPlatformOperator()
            && Filament::getCurrentPanel()?->getId() === 'founder';
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Template')
                    ->description('Kelola detail template landing page publik yang dapat dipilih oleh restoran.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Template')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, $livewire): void {
                                if ($livewire instanceof CreateLandingTemplate) {
                                    $set('slug', str($state ?? '')->slug('_')->toString());
                                    $set('view_path', 'landing.templates.' . str($state ?? '')->slug('_')->toString() . '.show');
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Slug / ID Kunci')
                            ->required()
                            ->maxLength(50)
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (?LandingTemplate $record): bool => $record instanceof LandingTemplate)
                            ->dehydrated()
                            ->helperText('Digunakan di sistem internal. Tidak dapat diubah setelah tersimpan.'),
                        TextInput::make('badge')
                            ->label('Badge')
                            ->placeholder('contoh: Populer, Default, Baru')
                            ->maxLength(50),
                        TextInput::make('view_path')
                            ->label('View Path Blade')
                            ->required()
                            ->default('landing.templates.classic.show')
                            ->helperText('Lokasi file Blade, contoh: landing.templates.foodie.show'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('thumbnail_path')
                            ->label('Preview Gambar (Thumbnail)')
                            ->image()
                            ->imageAspectRatio('16:10')
                            ->automaticallyCropImagesToAspectRatio()
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1280')
                            ->automaticallyResizeImagesToHeight('800')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:10'])
                            ->disk('public')
                            ->directory('landing-templates')
                            ->maxSize(15360)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Upload screenshot preview template. Rasio 16:10, otomatis dipotong & dikompres (maks. 15 MB). Jika kosong, sistem menggunakan file bawaan: images/landing/templates/{slug}.webp')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif & Tersedia untuk Restoran')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Preview')
                    ->state(fn (LandingTemplate $record): string => $record->thumbnail_url)
                    ->size(70)
                    ->extraImgAttributes(['class' => 'rounded-md object-cover shadow-xs border border-zinc-200 dark:border-zinc-700']),
                TextColumn::make('name')
                    ->label('Nama Template')
                    ->searchable()
                    ->sortable()
                    ->description(fn (LandingTemplate $record): ?string => $record->description),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('badge')
                    ->label('Badge')
                    ->badge()
                    ->color(fn (?string $state): string => match (strtolower($state ?? '')) {
                        'populer', 'hot' => 'warning',
                        'default' => 'success',
                        'baru', 'new' => 'info',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLandingTemplates::route('/'),
            'create' => CreateLandingTemplate::route('/create'),
            'edit' => EditLandingTemplate::route('/{record}/edit'),
        ];
    }
}
