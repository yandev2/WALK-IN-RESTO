<?php

namespace App\Filament\Founder\Resources\SubscriptionInvoices;

use App\Enums\InvoiceSource;
use App\Enums\InvoiceStatus;
use App\Filament\Founder\Resources\SubscriptionInvoices\Pages\CreateSubscriptionInvoice;
use App\Filament\Founder\Resources\SubscriptionInvoices\Pages\ListSubscriptionInvoices;
use App\Filament\Founder\Resources\SubscriptionInvoices\Pages\ViewSubscriptionInvoice;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionInvoiceService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class SubscriptionInvoiceResource extends Resource
{
    protected static ?string $model = SubscriptionInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Langganan';

    protected static ?string $navigationLabel = 'Invoice';

    protected static ?string $modelLabel = 'invoice';

    protected static ?string $recordTitleAttribute = 'invoice_number';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()
            ->where('status', InvoiceStatus::AwaitingVerification)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('restaurant_id')
                            ->label('Tenant')
                            ->relationship('restaurant', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('plan_code')
                            ->label('Paket')
                            ->options(fn () => SubscriptionPlan::query()->orderBy('sort_order')->pluck('name', 'code'))
                            ->required()
                            ->native(false),
                        TextInput::make('billing_months')
                            ->label('Durasi (bulan)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(12)
                            ->default(1),
                        Textarea::make('admin_notes')
                            ->label('Catatan internal')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('invoice_number')->label('Nomor'),
                        TextEntry::make('restaurant.name')->label('Tenant'),
                        TextEntry::make('plan_code')->label('Paket invoice'),
                        TextEntry::make('requested_plan_code')->label('Paket diminta')->placeholder('—'),
                        TextEntry::make('billing_months')->label('Durasi (bulan)')->placeholder('—'),
                        TextEntry::make('amount')
                            ->label('Nominal')
                            ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn ($state): string => $state instanceof InvoiceStatus ? $state->label() : (string) $state),
                        TextEntry::make('source')
                            ->formatStateUsing(fn ($state): string => $state instanceof InvoiceSource ? $state->value : (string) $state),
                        TextEntry::make('due_at')->dateTime('d M Y H:i')->placeholder('—'),
                        TextEntry::make('paid_at')->dateTime('d M Y H:i')->placeholder('—'),
                        TextEntry::make('payment_notes')->columnSpanFull()->placeholder('—'),
                        TextEntry::make('rejection_notes')->columnSpanFull()->placeholder('—'),
                        TextEntry::make('admin_notes')->columnSpanFull()->placeholder('—'),
                    ]),
                Section::make('Bukti transfer')
                    ->schema([
                        ImageEntry::make('payment_proof_path')
                            ->hiddenLabel()
                            ->disk('local')
                            ->visibility('private')
                            ->visible(fn (SubscriptionInvoice $record): bool => filled($record->payment_proof_path) && ! str_ends_with((string) $record->payment_proof_path, '.pdf')),
                        TextEntry::make('payment_proof_path')
                            ->label('File')
                            ->visible(fn (SubscriptionInvoice $record): bool => filled($record->payment_proof_path)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Nomor')
                    ->searchable(),
                TextColumn::make('restaurant.name')
                    ->label('Tenant')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof InvoiceStatus ? $state->label() : (string) $state),
                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),
                TextColumn::make('due_at')
                    ->dateTime('d M Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(InvoiceStatus::cases())->mapWithKeys(
                        fn (InvoiceStatus $status) => [$status->value => $status->label()],
                    )),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                ViewAction::make(),
                Action::make('approve')
                    ->label('Terima')
                    ->color('success')
                    ->visible(fn (SubscriptionInvoice $record): bool => $record->status === InvoiceStatus::AwaitingVerification)
                    ->requiresConfirmation()
                    ->action(function (SubscriptionInvoice $record): void {
                        $user = auth()->user();
                        if (! $user instanceof User) {
                            return;
                        }
                        app(SubscriptionInvoiceService::class)->approve($record, $user);
                        Notification::make()->title('Invoice dilunasi.')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->visible(fn (SubscriptionInvoice $record): bool => $record->status === InvoiceStatus::AwaitingVerification)
                    ->schema([
                        Textarea::make('rejection_notes')->label('Alasan')->required(),
                    ])
                    ->action(function (SubscriptionInvoice $record, array $data): void {
                        $user = auth()->user();
                        if (! $user instanceof User) {
                            return;
                        }
                        app(SubscriptionInvoiceService::class)->reject($record, $data['rejection_notes'], $user);
                        Notification::make()->title('Bukti ditolak.')->success()->send();
                    }),
                Action::make('void')
                    ->label('Batalkan')
                    ->color('gray')
                    ->visible(fn (SubscriptionInvoice $record): bool => $record->isOpen())
                    ->requiresConfirmation()
                    ->action(function (SubscriptionInvoice $record): void {
                        app(SubscriptionInvoiceService::class)->void($record);
                        Notification::make()->title('Invoice dibatalkan.')->success()->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptionInvoices::route('/'),
            'create' => CreateSubscriptionInvoice::route('/create'),
            'view' => ViewSubscriptionInvoice::route('/{record}'),
        ];
    }
}
