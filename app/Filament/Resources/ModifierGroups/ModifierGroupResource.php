<?php

namespace App\Filament\Resources\ModifierGroups;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\ModifierGroups\Pages\ManageModifierGroups;
use App\Filament\Resources\ModifierGroups\Pages\TrashModifierGroups;
use App\Filament\Support\TableRightClick;
use App\Models\ModifierGroup;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ModifierGroupResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = ModifierGroup::class;

    protected static string $permission = 'menu.manage';

    protected static string $subscriptionFeature = 'menu';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|UnitEnum|null $navigationGroup = 'Menu';

    protected static ?string $navigationLabel = 'Extra / modifier';

    protected static ?string $modelLabel = 'grup extra';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Aturan grup')
                    ->description('Contoh: “Level pedas” wajib pilih 1, “Topping” opsional maksimal 3.')
                    ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama grup')
                            ->placeholder('Level pedas')
                            ->required()
                            ->maxLength(80)
                            ->columnSpanFull(),
                        TextInput::make('min_select')
                            ->label('Minimal pilih')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->helperText('0 = tamu boleh tidak memilih.'),
                        TextInput::make('max_select')
                            ->label('Maksimal pilih')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->helperText('Batas jumlah extra dalam grup ini.'),
                        Toggle::make('is_required')
                            ->label('Wajib dipilih')
                            ->helperText('Tamu harus memilih minimal 1 extra aktif.')
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Pilihan extra')
                    ->description('Daftar opsi extra beserta harga tambahan.')
                    ->icon(Heroicon::OutlinedListBullet)
                    ->schema([
                        Repeater::make('modifiers')
                            ->relationship()
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama extra')
                                    ->placeholder('Pedas')
                                    ->required()
                                    ->maxLength(80)
                                    ->columnSpanFull(),
                                TextInput::make('price')
                                    ->label('Harga tambahan')
                                    ->numeric()
                                    ->minValue(0)
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
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah extra')
                            ->collapsed()
                            ->itemLabel(function (array $state): string {
                                $name = filled($state['name'] ?? null) ? $state['name'] : 'Extra baru';

                                if (! isset($state['price'])) {
                                    return $name;
                                }

                                return $name.' · Rp '.number_format((float) $state['price'], 0, ',', '.');
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('min_select')
                    ->label('Min')
                    ->alignCenter(),
                TextColumn::make('max_select')
                    ->label('Maks')
                    ->alignCenter(),
                IconColumn::make('is_required')
                    ->label('Wajib')
                    ->boolean(),
                TextColumn::make('modifiers_count')
                    ->counts('modifiers')
                    ->label('Pilihan')
                    ->alignCenter(),
            ])
            ->defaultSort('name');

        return TableRightClick::apply($table, fn (): array => [
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageModifierGroups::route('/'),
            'trash' => TrashModifierGroups::route('/trash'),
        ];
    }
}
