<?php

namespace App\Filament\Resources\KdsStations;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\KdsStations\Pages\ManageKdsStations;
use App\Filament\Resources\KdsStations\Pages\TrashKdsStations;
use App\Filament\Support\TableRightClick;
use App\Models\KdsStation;
use App\Support\TenantContext;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class KdsStationResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = KdsStation::class;

    protected static string $permission = 'menu.manage';

    protected static string $subscriptionFeature = 'kds';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static string|UnitEnum|null $navigationGroup = 'Menu';

    protected static ?string $navigationLabel = 'Stasiun KDS';

    protected static ?string $pluralModelLabel  = 'stasiun KDS';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas stasiun')
                    ->description('Nama tampilan dan kode unik untuk layar dapur/bar.')
                    ->icon(Heroicon::OutlinedFire)
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Dapur')
                            ->required()
                            ->maxLength(80),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('kitchen')
                            ->required()
                            ->maxLength(32)
                            ->alphaDash()
                            ->unique(
                                table: KdsStation::class,
                                column: 'slug',
                                ignoreRecord: true,
                                modifyRuleUsing: fn(Unique $rule) => $rule->where('outlet_id', TenantContext::outletId()),
                            )
                            ->helperText('Huruf kecil, angka, dan strip. Dipakai di URL layar KDS.'),
                    ]),
                Section::make('Tampilan')
                    ->description('Stasiun nonaktif tidak menerima item menu baru.')
                    ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->columnSpan(2)
                            ->helperText('Angka kecil tampil lebih dulu. Bisa juga digeser di tabel.'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                     ->width('sm')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');

        return TableRightClick::apply($table, fn(): array => [
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageKdsStations::route('/'),
            'trash' => TrashKdsStations::route('/trash'),
        ];
    }
}
