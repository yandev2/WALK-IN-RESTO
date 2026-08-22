<?php

namespace App\Filament\Resources\MenuCategories;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\MenuCategories\Pages\ManageMenuCategories;
use App\Filament\Resources\MenuCategories\Pages\TrashMenuCategories;
use App\Filament\Support\TableRightClick;
use App\Models\MenuCategory;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class MenuCategoryResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = MenuCategory::class;

    protected static string $permission = 'menu.manage';

    protected static string $subscriptionFeature = 'menu';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquaresPlus;

    protected static string|UnitEnum|null $navigationGroup = 'Menu';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'kategori menu';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(80),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('sort_order')->label('Urutan')->sortable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('items_count')->counts('items')->label('Item'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');

        return TableRightClick::apply($table, fn (): array => [
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMenuCategories::route('/'),
            'trash' => TrashMenuCategories::route('/trash'),
        ];
    }
}
