<?php

namespace App\Filament\Resources\MenuItems;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Resources\MenuItems\Pages\EditMenuItem;
use App\Filament\Resources\MenuItems\Pages\ListMenuItems;
use App\Filament\Resources\MenuItems\Pages\TrashMenuItems;
use App\Filament\Support\TableRightClick;
use App\Models\MenuItem;
use App\Support\TenantContext;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MenuItemResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = MenuItem::class;

    protected static string $permission = 'menu.manage';

    protected static string $subscriptionFeature = 'menu';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Menu';

    protected static ?string $navigationLabel = 'Item menu';

    protected static ?string $pluralpluralModelLabel  = 'Item menu';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Identitas menu')
                            ->description('Nama, kategori, dan stasiun dapur/bar.')
                            ->icon(Heroicon::OutlinedBookOpen)
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(120)
                                    ->columnSpanFull(),
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship(
                                        'category',
                                        'name',
                                        fn(Builder $query): Builder => $query->where('restaurant_id', TenantContext::restaurantId()),
                                    )
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->native(false),
                                Select::make('station_id')
                                    ->label('Stasiun KDS')
                                    ->relationship(
                                        'station',
                                        'name',
                                        fn(Builder $query): Builder => $query->where('restaurant_id', TenantContext::restaurantId()),
                                    )
                                    ->required()
                                    ->preload()
                                    ->native(false),
                                Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Foto')
                            ->description('Foto utama wajib. Tambahkan 1–3 foto lain (total maks. 4 foto).')
                            ->icon(Heroicon::OutlinedPhoto)
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('photo_path')
                                    ->label('Foto utama')
                                    ->image()
                                    ->imageAspectRatio('4:3')
                                    ->panelAspectRatio('4:3')
                                    ->imagePreviewHeight('180')
                                    ->panelLayout('integrated')
                                    ->automaticallyCropImagesToAspectRatio()
                                    ->automaticallyResizeImagesMode('cover')
                                    ->automaticallyResizeImagesToWidth('1200')
                                    ->automaticallyResizeImagesToHeight('900')
                                    ->automaticallyUpscaleImagesWhenResizing(false)
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    ->imageEditorAspectRatios(['4:3'])
                                    ->required()
                                    ->directory('menu')
                                    ->disk('public')
                                    ->maxSize(15360)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Format: JPG, PNG, WEBP. Rasio 4:3, otomatis dipotong & dikompres (maks. 15 MB).'),
                                Repeater::make('photos')
                                    ->relationship()
                                    ->label('Foto tambahan')
                                    ->schema([
                                        FileUpload::make('photo_path')
                                            ->hiddenLabel()
                                            ->image()
                                            ->imageAspectRatio('4:3')
                                            ->panelAspectRatio('4:3')
                                            ->imagePreviewHeight('140')
                                            ->panelLayout('integrated')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->automaticallyResizeImagesMode('cover')
                                            ->automaticallyResizeImagesToWidth('1200')
                                            ->automaticallyResizeImagesToHeight('900')
                                            ->automaticallyUpscaleImagesWhenResizing(false)
                                            ->required()
                                            ->directory('menu')
                                            ->disk('public')
                                            ->maxSize(15360)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Format: JPG, PNG, WEBP. Rasio 4:3, otomatis dipotong & dikompres (maks. 15 MB).'),
                                    ])
                                    ->minItems(0)
                                    ->maxItems(3)
                                    ->defaultItems(0)
                                    ->addActionLabel('Tambah foto')
                                    ->reorderableWithDragAndDrop()
                                    ->orderColumn('sort_order')
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): string => 'Foto ' . (((int) ($state['sort_order'] ?? 0)) + 2)),
                            ]),
                    ]),
                Section::make('Harga & ketersediaan')
                    ->description('Harga jual dan status item di menu.')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->columns(3)
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->prefix('Rp'),
                        TextInput::make('discount_percent')
                            ->label('Potongan harga')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->nullable()
                            ->suffix('%')
                            ->helperText('Kosongkan jika tidak ada diskon.'),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->helperText('Angka kecil tampil lebih dulu. Bisa juga digeser di tabel.'),
                        Toggle::make('is_best_seller')
                            ->label('Best Seller')
                            ->helperText('Badge Best Seller & prioritas tampil teratas.')
                            ->default(false)
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('Aktif di menu')
                            ->default(true)
                            ->inline(false),
                        Toggle::make('is_out_of_stock')
                            ->label('Stok habis')
                            ->helperText('Tamu masih lihat item, tapi tidak bisa dipesan.')
                            ->inline(false),
                    ]),
                Section::make('Varian')
                    ->description('Ukuran atau pilihan dengan selisih harga.')
                    ->icon(Heroicon::OutlinedSquaresPlus)
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->hiddenLabel()
                            ->addActionAlignment(Alignment::Start)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama varian')
                                    ->placeholder('Besar')
                                    ->required()
                                    ->maxLength(80)
                                    ->columnSpanFull(),
                                TextInput::make('price_delta')
                                    ->label('Selisih harga')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->prefix('Rp'),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->inline(false),
                            ])
                            ->grid([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                '2xl' => 2
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah varian')
                            ->collapsed()
                            ->itemLabel(fn(array $state): string => filled($state['name'] ?? null)
                                ? $state['name']
                                : 'Varian baru'),
                    ]),
                Section::make('Extra / modifier')
                    ->description('Grup extra opsional yang bisa dipilih tamu.')
                    ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                    ->schema([
                        Select::make('modifierGroups')
                            ->label('Grup extra')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->native(false)
                            ->relationship(
                                name: 'modifierGroups',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query): Builder => $query->where(
                                    $query->getModel()->qualifyColumn('restaurant_id'),
                                    TenantContext::restaurantId(),
                                ),
                            )
                            ->pivotData([
                                'restaurant_id' => TenantContext::restaurantId(),
                                'outlet_id' => TenantContext::outletId(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $tenantId = fn(): int => Filament::getTenant()->id;

        $table = $table
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),
                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->disk('public')
                    ->square()
                    ->imageSize(48),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('station.name')
                    ->label('Stasiun')
                    ->sortable()
                    ->toggleable()
                    ->hidden(fn(ListMenuItems $livewire): bool => ! $livewire->showsStationColumn()),
                TextColumn::make('price')
                    ->label('Harga')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(function (MenuItem $record): string {
                        if ($record->hasDiscount()) {
                            return 'Rp ' . number_format($record->effectivePrice(), 0, ',', '.')
                                . ' (−' . $record->discount_percent . '%)';
                        }

                        return 'Rp ' . number_format((int) $record->price, 0, ',', '.');
                    }),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->width('sm')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                IconColumn::make('is_best_seller')
                    ->label('Best Seller')
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                IconColumn::make('is_out_of_stock')
                    ->label('Habis')
                    ->boolean(),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                SelectFilter::make('is_best_seller')
                    ->label('Best Seller')
                    ->native(false)
                    ->options([true => 'Best Seller', false => 'Biasa']),
                SelectFilter::make('is_active')
                    ->label('Status Item')
                    ->native(false)
                    ->options([true => 'Aktif', false => 'Tidak Aktif']),
                SelectFilter::make('is_out_of_stock')
                    ->label('Stock Item')
                    ->native(false)
                    ->options([true => 'Out', false => 'Ready']),
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->native(false)
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query): Builder => $query->where('restaurant_id', $tenantId()),
                    ),
                SelectFilter::make('station_id')
                    ->label('Stasiun')
                    ->native(false)
                    ->relationship(
                        name: 'station',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query): Builder => $query->where('restaurant_id', $tenantId()),
                    ),
            ]);

        return TableRightClick::apply($table, fn(): array => [
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuItems::route('/'),
            'create' => CreateMenuItem::route('/create'),
            'edit' => EditMenuItem::route('/{record}/edit'),
            'trash' => TrashMenuItems::route('/trash'),
        ];
    }
}
