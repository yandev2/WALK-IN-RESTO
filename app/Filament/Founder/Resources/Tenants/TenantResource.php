<?php

namespace App\Filament\Founder\Resources\Tenants;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Founder\Resources\Tenants\Pages\EditTenant;
use App\Filament\Founder\Resources\Tenants\Pages\ListTenants;
use App\Filament\Pages\Dashboard as TenantDashboard;
use App\Filament\Support\TableRightClick;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\SubscriptionPlan;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Restaurant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Tenant';

    protected static ?string $pluralModelLabel  = 'tenant';

    protected static ?string $pluralpluralModelLabel  = 'tenant';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
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
                Section::make('Identitas')
                    ->columns(2)
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
                        Toggle::make('is_active')
                            ->label('Tenant aktif')
                            ->default(true),
                        Toggle::make('listed_in_directory')
                            ->label('Tampil di directory')
                            ->default(true),
                        Toggle::make('landing_enabled')
                            ->label('Landing publik aktif')
                            ->default(true),
                        Toggle::make('is_recommended')
                            ->label('Rekomendasi spesial')
                            ->helperText('Tampilkan di slider rekomendasi halaman direktori.')
                            ->default(false),
                    ]),
                Section::make('Langganan')
                    ->columns(2)
                    ->schema([
                        Select::make('plan_code')
                            ->label('Paket')
                            ->options(fn () => SubscriptionPlan::query()->orderBy('sort_order')->pluck('name', 'code'))
                            ->required()
                            ->native(false)
                            ->default(PlanCode::ManagementKds->value),
                        TextInput::make('commission_percentage')
                            ->label('Tarif komisi khusus (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->placeholder(fn (): string => 'Default: '.PlatformSetting::cashierCommissionPercentage().'% (Platform)')
                            ->helperText('Kosongkan untuk memakai tarif komisi global platform.'),
                        Select::make('subscription_status')
                            ->label('Status')
                            ->options(collect(SubscriptionStatus::cases())->mapWithKeys(
                                fn (SubscriptionStatus $status) => [$status->value => $status->label()],
                            ))
                            ->required()
                            ->native(false)
                            ->default(SubscriptionStatus::Trial->value),
                        DateTimePicker::make('trial_ends_at')
                            ->label('Trial berakhir')
                            ->seconds(false),
                        DateTimePicker::make('grace_ends_at')
                            ->label('Grace berakhir')
                            ->seconds(false),
                        DateTimePicker::make('subscribed_until')
                            ->label('Aktif sampai')
                            ->seconds(false),
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
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('plan_code')
                    ->label('Paket')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => PlanCode::tryFrom((string) $state)?->label() ?? (string) $state),
                TextColumn::make('effective_commission')
                    ->label('Komisi')
                    ->badge()
                    ->state(fn (Restaurant $record): string => $record->isCommissionPlan()
                        ? ($record->commission_percentage !== null
                            ? rtrim(rtrim(number_format((float) $record->commission_percentage, 2, ',', '.'), '0'), ',').'% (Khusus)'
                            : rtrim(rtrim(number_format(PlatformSetting::cashierCommissionPercentage(), 2, ',', '.'), '0'), ',').'% (Global)')
                        : 'Flat'),
                TextColumn::make('overdue_status')
                    ->label('Tunggakan')
                    ->badge()
                    ->state(fn (Restaurant $record): string => $record->hasOverdueCashierInvoice() ? 'Ada Tunggakan' : 'Lancar')
                    ->color(fn (Restaurant $record): string => $record->hasOverdueCashierInvoice() ? 'danger' : 'success'),
                TextColumn::make('subscription_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof SubscriptionStatus ? $state->label() : (string) $state),
                TextColumn::make('subscribed_until')
                    ->label('Aktif sampai')
                    ->dateTime('d M Y')
                    ->placeholder('—'),
                IconColumn::make('listed_in_directory')
                    ->label('Directory')
                    ->boolean(),
                IconColumn::make('landing_enabled')
                    ->label('Landing')
                    ->boolean(),
                IconColumn::make('is_recommended')
                    ->label('Rekomendasi')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('subscription_status')
                    ->options(collect(SubscriptionStatus::cases())->mapWithKeys(
                        fn (SubscriptionStatus $status) => [$status->value => $status->label()],
                    )),
                SelectFilter::make('plan_code')
                    ->options(fn () => SubscriptionPlan::query()->pluck('name', 'code')),
            ]);

        return TableRightClick::apply($table, fn (): array => [
            Action::make('openPanel')
                ->label('Buka panel tenant')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (Restaurant $record): string => TenantDashboard::getUrl(panel: 'admin', tenant: $record)),
            EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
