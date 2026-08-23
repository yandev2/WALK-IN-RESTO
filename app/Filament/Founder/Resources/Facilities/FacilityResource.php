<?php

namespace App\Filament\Founder\Resources\Facilities;

use App\Filament\Founder\Resources\Facilities\Pages\CreateFacility;
use App\Filament\Founder\Resources\Facilities\Pages\EditFacility;
use App\Filament\Founder\Resources\Facilities\Pages\ListFacilities;
use App\Models\Facility;
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

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Fasilitas';

    protected static ?string $pluralModelLabel  = 'fasilitas';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 12;

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
                                if ($livewire instanceof CreateFacility) {
                                    $set('key', str($state ?? '')->slug('_')->toString());
                                }
                            }),
                        TextInput::make('key')
                            ->label('Kunci')
                            ->required()
                            ->maxLength(40)
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (?Facility $record): bool => $record instanceof Facility)
                            ->dehydrated()
                            ->helperText('Dipakai di data restoran. Tidak bisa diubah setelah tersimpan.'),
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
                TextColumn::make('key')
                    ->label('Kunci')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFacilities::route('/'),
            'create' => CreateFacility::route('/create'),
            'edit' => EditFacility::route('/{record}/edit'),
        ];
    }
}
