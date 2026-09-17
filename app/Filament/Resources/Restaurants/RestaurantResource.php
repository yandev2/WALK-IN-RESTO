<?php

namespace App\Filament\Resources\Restaurants;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Restaurants\Pages\CreateRestaurant;
use App\Filament\Resources\Restaurants\Pages\EditRestaurant;
use App\Filament\Resources\Restaurants\Pages\ListRestaurants;
use App\Filament\Support\TableRightClick;
use App\Models\Facility;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Restoran';

    protected static ?string $pluralModelLabel  = 'restoran';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
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
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, $livewire): void {
                                if ($livewire instanceof CreateRecord) {
                                    $set('slug', str($state ?? '')->slug()->toString());
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(80)
                            ->unique(ignoreRecord: true),
                        TextInput::make('legal_name')
                            ->label('Nama legal')
                            ->maxLength(191),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->imageAspectRatio('1:1')
                            ->panelAspectRatio('1:1')
                            ->imagePreviewHeight('160')
                            ->panelLayout('integrated')
                            ->automaticallyCropImagesToAspectRatio()
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('800')
                            ->automaticallyResizeImagesToHeight('800')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->directory('restaurants/logos')
                            ->disk('public')
                            ->maxSize(15360)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Format: JPG, PNG, WEBP transparan. Rasio 1:1 persegi, otomatis dipotong & dikompres (maks. 15 MB).'),
                        TextInput::make('timezone')
                            ->required()
                            ->default('Asia/Jakarta'),
                        TextInput::make('currency')
                            ->required()
                            ->maxLength(3)
                            ->default('IDR'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        ToggleButtons::make('price_level')
                            ->label('Level harga')
                            ->options([
                                1 => '$',
                                2 => '$$',
                                3 => '$$$',
                                4 => '$$$$',
                            ])
                            ->inline()
                            ->grouped()
                            ->nullable()
                            ->columnSpanFull(),
                        CheckboxList::make('categories')
                            ->label('Kategori restoran')
                            ->relationship('categories', 'name')
                            ->options(fn () => RestaurantCategory::query()
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('name')
                                ->pluck('name', 'id'))
                            ->columns(2),
                        ToggleButtons::make('facilities')
                            ->label('Fasilitas')
                            ->options(fn () => Facility::activeOptions())
                            ->multiple()
                            ->inline()
                            ->icons(Facility::iconMap())
                            ->afterStateHydrated(function (ToggleButtons $component, ?Restaurant $record): void {
                                if (! $record) {
                                    return;
                                }

                                $selected = collect($record->facilities ?? [])
                                    ->filter()
                                    ->keys()
                                    ->values()
                                    ->all();

                                $component->state($selected);
                            })
                            ->dehydrateStateUsing(function (?array $state): array {
                                return collect(Facility::knownKeys())
                                    ->mapWithKeys(fn (string $key) => [$key => in_array($key, $state ?? [], true)])
                                    ->all();
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ]);

        return TableRightClick::apply($table, fn (): array => [
            Action::make('openPanel')
                ->label('Buka panel')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (Restaurant $record): string => Dashboard::getUrl(tenant: $record)),
            EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRestaurants::route('/'),
            'create' => CreateRestaurant::route('/create'),
            'edit' => EditRestaurant::route('/{record}/edit'),
        ];
    }
}
