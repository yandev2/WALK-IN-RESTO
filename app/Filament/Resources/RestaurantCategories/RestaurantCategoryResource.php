<?php

namespace App\Filament\Resources\RestaurantCategories;

use App\Filament\Resources\RestaurantCategories\Pages\CreateRestaurantCategory;
use App\Filament\Resources\RestaurantCategories\Pages\EditRestaurantCategory;
use App\Filament\Resources\RestaurantCategories\Pages\ListRestaurantCategories;
use App\Models\RestaurantCategory;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

class RestaurantCategoryResource extends Resource
{
    protected static ?string $model = RestaurantCategory::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Kategori restoran';

    protected static ?string $pluralModelLabel  = 'kategori restoran';

    protected static ?string $recordTitleAttribute = 'name';

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
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, $livewire): void {
                                if ($livewire instanceof CreateRestaurantCategory) {
                                    $set('slug', str($state ?? '')->slug()->toString());
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(80)
                            ->unique(ignoreRecord: true),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('restaurants_count')
                    ->label('Restoran')
                    ->counts('restaurants'),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRestaurantCategories::route('/'),
            'create' => CreateRestaurantCategory::route('/create'),
            'edit' => EditRestaurantCategory::route('/{record}/edit'),
        ];
    }
}
