<?php

namespace App\Filament\Founder\Resources\SubscriptionPlans;

use App\Enums\BillingType;
use App\Filament\Founder\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan;
use App\Filament\Founder\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans;
use App\Models\SubscriptionPlan;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Langganan';

    protected static ?string $navigationLabel = 'Paket';

    protected static ?string $pluralModelLabel  = 'paket';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(120),
                        Select::make('billing_type')
                            ->label('Model Billing')
                            ->options(collect(BillingType::cases())->mapWithKeys(
                                fn (BillingType $type) => [$type->value => $type->label()],
                            ))
                            ->required()
                            ->native(false)
                            ->live(),
                        TextInput::make('price_monthly')
                            ->label('Harga per bulan')
                            ->numeric()
                            ->prefix('Rp')
                            ->visible(fn (Get $get): bool => ($get('billing_type') ?? BillingType::FixedMonthly->value) === BillingType::FixedMonthly->value)
                            ->required(fn (Get $get): bool => ($get('billing_type') ?? BillingType::FixedMonthly->value) === BillingType::FixedMonthly->value),
                        TextInput::make('commission_percentage')
                            ->label('Tarif komisi omzet')
                            ->numeric()
                            ->suffix('%')
                            ->visible(fn (Get $get): bool => $get('billing_type') === BillingType::Commission->value)
                            ->required(fn (Get $get): bool => $get('billing_type') === BillingType::Commission->value),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->required()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('code')
                    ->badge(),
                TextColumn::make('billing_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof BillingType ? $state->label() : (string) $state),
                TextColumn::make('pricing')
                    ->label('Tarif / Biaya')
                    ->state(fn (SubscriptionPlan $record): string => $record->formattedPrice()),
                IconColumn::make('is_active')
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
            'index' => ListSubscriptionPlans::route('/'),
            'edit' => EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
