<?php

namespace App\Filament\Pages;

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus as SubscriptionStatusEnum;
use App\Filament\Support\TableRightClick;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionInvoiceService;
use App\Support\RestaurantTheme;
use App\Support\SubscriptionGate;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class SubscriptionStatus extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Langganan';

    protected static ?string $title = 'Langganan';

    protected static ?int $navigationSort = 80;

    public static function canAccess(): bool
    {
        return auth()->user() instanceof User;
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('billingGuide')
                ->label('Panduan Billing')
                ->icon(Heroicon::OutlinedInformationCircle)
                ->color('info')
                ->outlined()
                ->modalWidth('3xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalHeading('Cara kerja billing & langganan')
                ->modalContent(fn (): View => view('filament.pages.info-billing', [
                    'trialDays' => PlatformSetting::trialDays(),
                ])),
            Action::make('createInvoice')
                ->label('Buat invoice')
                ->icon(Heroicon::OutlinedPlus)
                ->hidden(fn (): bool => $this->openInvoice() instanceof SubscriptionInvoice)
                ->fillForm(fn (): array => $this->invoiceFormState(
                    $this->restaurant()?->plan_code,
                    1,
                ))
                ->schema($this->invoicePlanFields())
                ->modalWidth('4xl')
                ->modalHeading('Buat invoice')
                ->modalSubmitActionLabel('Buat invoice')
                ->action(function (array $data): void {
                    $restaurant = $this->restaurant();

                    if (! $restaurant instanceof Restaurant) {
                        return;
                    }

                    try {
                        app(SubscriptionInvoiceService::class)->createManual(
                            $restaurant,
                            (string) $data['requested_plan_code'],
                            auth()->user() instanceof User ? auth()->user() : null,
                            (int) $data['billing_months'],
                        );
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title(collect($exception->errors())->flatten()->first() ?: 'Tidak bisa membuat invoice.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Invoice dibuat. Unggah bukti transfer dari tabel di bawah.')
                        ->success()
                        ->send();

                    $this->resetTable();
                }),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaView::make('filament.pages.partials.subscription-summary')
                    ->viewData(fn (): array => $this->transferWidgetData()),
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        $table = $table
            ->query($this->invoicesQuery())
            ->heading('Riwayat invoice')
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Nomor')
                    ->searchable(),
                TextColumn::make('requested_plan_code')
                    ->label('Paket diminta')
                    ->formatStateUsing(fn ($state, SubscriptionInvoice $record): string => $record->requestedPlan?->name
                        ?? $record->effectivePlanCode()),
                TextColumn::make('billing_months')
                    ->label('Durasi')
                    ->formatStateUsing(fn ($state): string => filled($state) ? $state.' bulan' : '—'),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => $state instanceof InvoiceStatus ? $state->color() : 'gray')
                    ->formatStateUsing(fn ($state): string => $state instanceof InvoiceStatus ? $state->label() : (string) $state),
                TextColumn::make('due_at')
                    ->label('Jatuh tempo')
                    ->dateTime('d M Y')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->paginated([10, 25, 50])
            ->emptyStateIcon(Heroicon::OutlinedBanknotes)
            ->emptyStateHeading('Belum ada invoice')
            ->emptyStateDescription('Buat invoice untuk memperpanjang atau mengganti paket.');

        return TableRightClick::apply($table, fn (): array => [
            Action::make('uploadProof')
                ->label('Unggah bukti')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->visible(fn (SubscriptionInvoice $record): bool => $record->isOpen())
                ->fillForm(fn (SubscriptionInvoice $record): array => $this->invoiceFormState(
                    $record->effectivePlanCode(),
                    $record->billing_months ?? 1,
                    [
                        'payment_proof_path' => $record->payment_proof_path,
                        'payment_notes' => $record->payment_notes,
                    ],
                ))
                ->schema([
                    ...$this->invoicePlanFields(),
                    FileUpload::make('payment_proof_path')
                        ->label('Bukti transfer')
                        ->disk('local')
                        ->directory('subscription-proofs')
                        ->visibility('private')
                        ->automaticallyResizeImagesMode('contain')
                        ->automaticallyResizeImagesToWidth('1600')
                        ->automaticallyResizeImagesToHeight('1600')
                        ->automaticallyUpscaleImagesWhenResizing(false)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                        ->maxSize(15360)
                        ->helperText('Format: JPG, PNG, WEBP, atau PDF. Maksimal 15 MB. Gambar otomatis dioptimasi tanpa memotong bukti transfer.')
                        ->required(),
                    Textarea::make('payment_notes')
                        ->label('Catatan (opsional)')
                        ->rows(2),
                ])
                ->modalWidth('4xl')
                ->modalHeading('Unggah bukti pembayaran')
                ->modalSubmitActionLabel('Kirim bukti')
                ->action(function (SubscriptionInvoice $record, array $data): void {
                    $this->assertInvoiceBelongsToTenant($record);

                    try {
                        app(SubscriptionInvoiceService::class)->submitProof(
                            $record,
                            (string) $data['requested_plan_code'],
                            (int) $data['billing_months'],
                            (string) $data['payment_proof_path'],
                            $data['payment_notes'] ?? null,
                        );
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title(collect($exception->errors())->flatten()->first() ?: 'Tidak bisa mengunggah bukti.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Bukti pembayaran terkirim. Menunggu verifikasi Founder.')
                        ->success()
                        ->send();

                    $this->resetTable();
                }),
            Action::make('deleteUnpaid')
                ->label('Hapus')
                ->icon(Heroicon::OutlinedTrash)
                ->color('danger')
                ->visible(fn (SubscriptionInvoice $record): bool => $record->isOpen())
                ->requiresConfirmation()
                ->modalHeading('Hapus invoice?')
                ->modalDescription('Invoice yang belum lunas akan dihapus dari riwayat. Invoice lunas tidak bisa dihapus.')
                ->modalSubmitActionLabel('Hapus')
                ->action(function (SubscriptionInvoice $record): void {
                    $this->assertInvoiceBelongsToTenant($record);

                    try {
                        app(SubscriptionInvoiceService::class)->deleteUnpaid($record);
                    } catch (ValidationException $exception) {
                        Notification::make()
                            ->title(collect($exception->errors())->flatten()->first() ?: 'Invoice tidak bisa dihapus.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Invoice dihapus.')
                        ->success()
                        ->send();

                    $this->resetTable();
                }),
        ]);
    }

    public function restaurant(): ?Restaurant
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Restaurant ? $tenant : null;
    }

    public function openInvoice(): ?SubscriptionInvoice
    {
        $restaurant = $this->restaurant();

        if (! $restaurant) {
            return null;
        }

        return SubscriptionInvoice::query()
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('status', [InvoiceStatus::Sent->value, InvoiceStatus::AwaitingVerification->value])
            ->latest('id')
            ->first();
    }

    public function statusEnum(): ?SubscriptionStatusEnum
    {
        $restaurant = $this->restaurant();

        if (! $restaurant) {
            return null;
        }

        return app(SubscriptionGate::class)->status($restaurant);
    }

    public function statusLabel(): string
    {
        return $this->statusEnum()?->label() ?? '—';
    }

    public function planName(): string
    {
        $restaurant = $this->restaurant();
        $restaurant?->loadMissing('subscriptionPlan');

        return $restaurant?->subscriptionPlan?->name
            ?? $restaurant?->plan_code
            ?? '—';
    }

    public function expiryLabel(): ?string
    {
        $expiry = $this->restaurant()
            ? app(SubscriptionGate::class)->effectiveExpiryAt($this->restaurant())
            : null;

        return $expiry?->timezone(config('app.timezone'))->translatedFormat('d M Y H:i');
    }

    public function daysRemaining(): ?int
    {
        $restaurant = $this->restaurant();

        if (! $restaurant) {
            return null;
        }

        $expiry = app(SubscriptionGate::class)->effectiveExpiryAt($restaurant);

        if (! $expiry) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays($expiry->copy()->startOfDay(), false);
    }

    public function countdownLabel(): string
    {
        $days = $this->daysRemaining();

        if ($days === null) {
            return '—';
        }

        if ($days > 1) {
            return $days.' hari lagi';
        }

        if ($days === 1) {
            return '1 hari lagi';
        }

        if ($days === 0) {
            return 'Berakhir hari ini';
        }

        return 'Sudah berakhir';
    }

    public function monthlyPriceLabel(): string
    {
        $restaurant = $this->restaurant();
        $restaurant?->loadMissing('subscriptionPlan');
        $plan = $restaurant?->subscriptionPlan;

        if (! $plan instanceof SubscriptionPlan) {
            return '—';
        }

        return $plan->formattedPrice().'/bulan';
    }

    /**
     * @return array{primary: string, primary_dark: string, accent: string}
     */
    public function theme(): array
    {
        $restaurant = $this->restaurant();
        $restaurant?->loadMissing('cmsProfile');

        return RestaurantTheme::for($restaurant);
    }

    public function formattedPreviewAmount(?string $planCode, int $months): string
    {
        $digits = $this->formattedPreviewDigits($planCode, $months);

        return $digits === '0' ? 'Rp 0' : 'Rp '.$digits;
    }

    public function formattedPreviewDigits(?string $planCode, int $months): string
    {
        if (! filled($planCode)) {
            return '0';
        }

        try {
            $amount = app(SubscriptionInvoiceService::class)->calculateAmount($planCode, $months);
        } catch (\Throwable) {
            return '0';
        }

        return number_format($amount, 0, ',', '.');
    }

    /**
     * @return list<array{code: string, name: string, price_label: string, description: string, is_current: bool}>
     */
    public function invoicePlanCards(): array
    {
        $current = $this->restaurant()?->plan_code;

        return SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (SubscriptionPlan $plan): array => [
                'code' => $plan->code,
                'name' => $plan->name,
                'price_label' => $plan->formattedPrice().'/bulan',
                'description' => (string) ($plan->description ?: ''),
                'is_current' => $plan->code === $current,
            ])
            ->all();
    }

    public function selectInvoicePlan(string $planCode): void
    {
        $index = array_key_last($this->mountedActions ?? []);

        if ($index === null) {
            return;
        }

        $exists = SubscriptionPlan::query()
            ->where('code', $planCode)
            ->where('is_active', true)
            ->exists();

        if (! $exists) {
            return;
        }

        $this->mountedActions[$index]['data']['requested_plan_code'] = $planCode;
        $months = (int) ($this->mountedActions[$index]['data']['billing_months'] ?? 1);
        $this->mountedActions[$index]['data']['total_amount_display'] = $this->formattedPreviewDigits($planCode, $months);
    }

    /**
     * @return list<Component>
     */
    private function invoicePlanFields(): array
    {
        return [
            Hidden::make('requested_plan_code')
                ->required()
                ->live()
                ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                    $this->syncInvoiceTotal($set, $get, $state);
                }),
            SchemaView::make('filament.pages.partials.subscription-plan-picker')
                ->viewData(fn (Get $get): array => [
                    'plans' => $this->invoicePlanCards(),
                    'selectedPlanCode' => $get('requested_plan_code'),
                ]),
            Select::make('billing_months')
                ->label('Durasi')
                ->options(collect(range(1, 12))->mapWithKeys(fn (int $month) => [$month => $month.' bulan']))
                ->required()
                ->live()
                ->native(false)
                ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                    $this->syncInvoiceTotal($set, $get);
                }),
            TextInput::make('total_amount_display')
                ->label('Total pembayaran')
                ->prefix('Rp')
                ->readOnly()
                ->dehydrated(false),
            SchemaView::make('filament.pages.partials.subscription-transfer-widget')
                ->viewData(fn (): array => $this->transferWidgetData()),
        ];
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private function invoiceFormState(?string $planCode, int $months, array $extra = []): array
    {
        return array_merge([
            'requested_plan_code' => $planCode,
            'billing_months' => $months,
            'total_amount_display' => $this->formattedPreviewDigits($planCode, $months),
        ], $extra);
    }

    private function syncInvoiceTotal(Set $set, Get $get, ?string $planCode = null): void
    {
        $set(
            'total_amount_display',
            $this->formattedPreviewDigits(
                $planCode ?? $get('requested_plan_code'),
                (int) ($get('billing_months') ?: 1),
            ),
        );
    }

    /**
     * @return array{bankName: string, bankAccount: string, bankHolder: string, contactEmail: string|null, qrUrl: string}
     */
    private function transferWidgetData(): array
    {
        return PlatformSetting::billingViewData();
    }

    private function invoicesQuery(): Builder
    {
        $restaurant = $this->restaurant();
        $query = SubscriptionInvoice::query()->with('requestedPlan');

        if (! $restaurant) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('restaurant_id', $restaurant->id);
    }

    private function assertInvoiceBelongsToTenant(SubscriptionInvoice $invoice): void
    {
        abort_unless($invoice->restaurant_id === $this->restaurant()?->id, 403);
    }
}
