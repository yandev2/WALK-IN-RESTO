<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\CashierShift;
use App\Models\DiningTable;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Services\CashierShiftService;
use App\Support\CashierMenuCatalog;
use App\Support\CashierOrderPreview;
use App\Support\CmsMedia;
use App\Support\IdrAmount;
use App\Support\SubscriptionAccess;
use App\Support\TenantContext;
use App\Support\WhatsAppNumber;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Renderless;

class CreateCashierOrder extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Order kasir';

    protected static ?string $title = 'Buat order kasir';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public ?array $simpleModeCompletedOrder = null;

    /**
     * @var array{type: 'new'|'edit', menu_item_id: int, index?: int, variant_id?: int|null, modifier_ids: list<int>, notes: string}|null
     */
    public ?array $posEditor = null;

    public string $posSearch = '';

    public string $posCategory = 'all';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('order.create'))
            && SubscriptionAccess::allows('operations', mutate: true);
    }

    public function mount(): void
    {
        $this->data = [
            'table_id' => null,
            'customer_wa' => null,
            'customer_name' => null,
            'send_receipt' => false,
            'payment_method' => 'cash',
            'cash_received' => null,
            'points_to_redeem' => 0,
            'lines' => [],
        ];
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('openShift')
                ->label('Buka Shift Kasir')
                ->icon(Heroicon::OutlinedPlusCircle)
                ->color('success')
                ->visible(fn (): bool => $this->activeShift === null)
                ->extraAttributes([
                    'x-on:click' => "window.dispatchEvent(new CustomEvent('open-cashier-shift-modal'))",
                ])
                ->action(function (array $arguments = []): void {
                    if (! empty($arguments['starting_cash'])) {
                        $this->openShift($arguments['starting_cash'], $arguments['notes'] ?? null);

                        return;
                    }

                    $this->dispatch('open-cashier-shift-modal');
                }),

            Action::make('closeShift')
                ->label('Tutup Shift')
                ->icon(Heroicon::OutlinedLockClosed)
                ->color('warning')
                ->visible(fn (): bool => $this->activeShift !== null)
                ->extraAttributes([
                    'x-on:click' => "window.dispatchEvent(new CustomEvent('open-cashier-close-modal'))",
                ])
                ->action(function (array $arguments = []): void {
                    if (isset($arguments['actual_cash'])) {
                        $this->closeShift(
                            $arguments['actual_cash'],
                            $arguments['difference_reason'] ?? null,
                            $arguments['notes'] ?? null,
                        );

                        return;
                    }

                    $this->dispatch('open-cashier-close-modal');
                }),
        ];
    }

    public function isPosUi(): bool
    {
        return true;
    }

    public function create(): void
    {
        $tenant = Filament::getTenant();
        $user = auth()->user();

        if (! $tenant instanceof Restaurant || ! $user instanceof User) {
            return;
        }

        $outlet = TenantContext::outlet();
        $activeShift = ($user instanceof User && $outlet)
            ? app(CashierShiftService::class)->getCurrentOpenShift($user, $outlet)
            : null;

        $isOwnerOrSuperAdmin = $user->isSuperAdmin() || $user->isRestaurantOwner();

        if (! $activeShift && ! $isOwnerOrSuperAdmin) {
            Notification::make()
                ->title('Shift kasir belum dibuka!')
                ->body('Silakan buka shift kasir dan masukkan modal awal sebelum membuat pesanan.')
                ->warning()
                ->send();

            return;
        }

        try {
            $data = $this->validatedPosData();
        } catch (ValidationException $e) {
            Notification::make()
                ->title(collect($e->errors())->flatten()->first() ?: 'Order tidak bisa dibuat')
                ->danger()
                ->send();

            throw $e;
        }

        $table = DiningTable::query()
            ->where('restaurant_id', $tenant->id)
            ->whereKey($data['table_id'])
            ->firstOrFail();

        $sendReceipt = (bool) ($data['send_receipt'] ?? false);

        if ($sendReceipt && ! WhatsAppNumber::isValid($data['customer_wa'] ?? null)) {
            Notification::make()
                ->title('Nomor WhatsApp wajib diisi jika struk dikirim via WhatsApp.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'customer_wa' => 'Nomor WhatsApp wajib diisi jika struk dikirim via WhatsApp.',
            ]);
        }

        try {
            $order = app(CashierOrderService::class)->create(
                $user,
                $table,
                filled($data['customer_wa'] ?? null) ? (string) $data['customer_wa'] : null,
                $data['customer_name'] ?? null,
                $data['payment_method'],
                $sendReceipt,
                $data['lines'] ?? [],
                IdrAmount::parse($data['cash_received'] ?? null),
                (int) ($data['points_to_redeem'] ?? 0),
            );
        } catch (ValidationException $e) {
            Notification::make()
                ->title(collect($e->errors())->flatten()->first() ?: 'Order tidak bisa dibuat')
                ->danger()
                ->send();

            throw $e;
        }

        $table->loadMissing('outlet');
        if ((bool) $table->outlet?->simple_mode) {
            $payment = $order->payments()->first();
            $customerName = $data['customer_name'] ?? $order->visit?->customer_name;
            $this->simpleModeCompletedOrder = [
                'id' => $order->id,
                'number' => $order->number,
                'public_id' => $order->public_id,
                'table_name' => $table->code,
                'customer_name' => filled($customerName) ? (string) $customerName : 'Tamu Walk-in',
                'grand_payable' => (int) $order->grand_payable,
                'payment_method' => $order->payment_method === 'cash' ? 'Tunai' : 'QRIS',
                'cash_received' => $payment?->cash_received,
                'change_amount' => $payment?->change_amount,
                'print_url' => route('receipts.print', ['order' => $order->public_id, 'auto' => 1]),
            ];

            $this->resetCashierForm();

            Notification::make()
                ->title('Pesanan #'.$order->number.' berhasil diselesaikan')
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('Order kasir masuk antrian')
            ->success()
            ->send();

        $this->redirect(OrderResource::getUrl('view', ['record' => $order], tenant: $tenant));
    }

    public function closeSimpleModeModal(): void
    {
        $this->simpleModeCompletedOrder = null;
        $this->resetCashierForm();
    }

    public function resetCashierForm(): void
    {
        $defaultData = [
            'table_id' => null,
            'customer_wa' => null,
            'customer_name' => null,
            'send_receipt' => false,
            'payment_method' => 'cash',
            'cash_received' => null,
            'points_to_redeem' => 0,
            'lines' => [],
        ];

        $this->data = $defaultData;
        $this->posEditor = null;
        $this->posSearch = '';
        $this->posCategory = 'all';

        $this->cacheSchema('content', null);

        $this->dispatch('cashier-reset-form');
    }

    public function content(Schema $schema): Schema
    {
        $modal = View::make('filament.pages.partials.cashier-simple-mode-modal')
            ->viewData(fn (): array => [
                'simpleModeCompletedOrder' => $this->simpleModeCompletedOrder,
            ]);

        return $schema
            ->components([
                View::make('filament.pages.partials.cashier-pos-shell')
                    ->viewData(fn (): array => $this->posShellViewData()),
                $modal,
            ]);
    }

    #[Renderless]
    public function addPosItem(int $menuItemId): array
    {
        if (! $this->isPosUi()) {
            return $this->posUiState();
        }

        $item = $this->posItem($menuItemId);

        if ($item === null) {
            return $this->posUiState();
        }

        if ($item['has_modifiers'] || ! empty($item['has_variants'])) {
            return $this->posUiState();
        }

        $this->incrementOrPushLine($menuItemId, null, [], null);

        return $this->posUiState();
    }

    #[Renderless]
    public function changePosQty(int $index, int $delta): array
    {
        if (! $this->isPosUi()) {
            return $this->posUiState();
        }

        $lines = array_values($this->data['lines'] ?? []);

        if (! isset($lines[$index])) {
            return $this->posUiState();
        }

        $qty = (int) ($lines[$index]['qty'] ?? 0) + $delta;

        if ($qty < 1) {
            unset($lines[$index]);
            $this->data['lines'] = array_values($lines);

            return $this->posUiState();
        }

        $lines[$index]['qty'] = $qty;
        $this->data['lines'] = $lines;

        return $this->posUiState();
    }

    #[Renderless]
    public function changePosItemQty(int $menuItemId, int $delta): array
    {
        if (! $this->isPosUi()) {
            return $this->posUiState();
        }

        $index = $this->plainLineIndex($menuItemId);

        if ($index === null) {
            if ($delta > 0) {
                return $this->addPosItem($menuItemId);
            }

            return $this->posUiState();
        }

        return $this->changePosQty($index, $delta);
    }

    #[Renderless]
    public function removePosLine(int $index): array
    {
        if (! $this->isPosUi()) {
            return $this->posUiState();
        }

        $lines = array_values($this->data['lines'] ?? []);
        unset($lines[$index]);
        $this->data['lines'] = array_values($lines);

        return $this->posUiState();
    }

    #[Renderless]
    public function setPosPaymentMethod(string $method): void
    {
        if (! $this->isPosUi() || ! in_array($method, ['cash', 'qris'], true)) {
            return;
        }

        $this->data['payment_method'] = $method;
    }

    #[Renderless]
    public function setPosField(string $field, mixed $value): void
    {
        if (! $this->isPosUi() || ! in_array($field, ['table_id', 'customer_name', 'customer_wa', 'send_receipt', 'cash_received', 'points_to_redeem'], true)) {
            return;
        }

        if ($field === 'send_receipt') {
            $this->data[$field] = (bool) $value;

            return;
        }

        if ($field === 'points_to_redeem') {
            $this->data[$field] = max(0, (int) $value);

            return;
        }

        $this->data[$field] = $value === '' ? null : $value;

        if ($field === 'customer_wa') {
            if (blank($this->data['customer_wa'] ?? null)) {
                $this->data['send_receipt'] = false;
                $this->data['points_to_redeem'] = 0;
            } elseif (TenantContext::restaurant()?->hasFonnteKey() && (bool) TenantContext::outlet()?->auto_print_receipt) {
                $this->data['send_receipt'] = true;
            }
        }
    }

    #[Renderless]
    public function setPosPoints(int $points): array
    {
        if (! $this->isPosUi()) {
            return $this->posUiState();
        }

        $this->data['points_to_redeem'] = max(0, $points);

        return $this->posUiState();
    }

    #[Renderless]
    public function checkCustomerPoints(string $wa): array
    {
        $restaurant = TenantContext::restaurant();
        if (! $restaurant) {
            return ['found' => false];
        }

        $normalized = WhatsAppNumber::normalize($wa);
        if (! $normalized) {
            return ['found' => false];
        }

        $customer = \App\Models\Customer::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('phone', $normalized)
            ->first();

        if (! $customer) {
            return ['found' => false];
        }

        $settings = $restaurant->loyaltySettings();

        return [
            'found' => true,
            'name' => $customer->name,
            'tier' => $customer->tierLabel(),
            'points' => (int) $customer->points_balance,
            'min_points' => (int) ($settings['min_redeem_points'] ?? 10),
            'rate' => (int) ($settings['point_redemption_rate'] ?? 1000),
            'max_percentage' => (int) ($settings['max_redeem_percentage'] ?? 50),
            'loyalty_enabled' => (bool) ($settings['enabled'] ?? true),
        ];
    }

    #[Renderless]
    public function commitPosEditor(string $type, int $menuItemId, ?int $index, ?int $variantId, array $modifierIds, ?string $notes): array
    {
        if (! $this->isPosUi()) {
            return $this->posUiState();
        }

        $modifierIds = $this->normalizedModifierIds($modifierIds);
        $variantId = $variantId ? (int) $variantId : null;
        $notes = filled($notes) ? (string) $notes : null;

        $item = $this->posItem($menuItemId);
        if ($menuItemId < 1 || $item === null) {
            return $this->posUiState();
        }

        if (! empty($item['has_variants'])) {
            $validVariantIds = collect($item['variants'] ?? [])->pluck('id')->all();
            if ($variantId === null || ! in_array($variantId, $validVariantIds, true)) {
                $variantId = $validVariantIds[0] ?? null;
            }
        } else {
            $variantId = null;
        }

        if ($type === 'edit') {
            $lines = array_values($this->data['lines'] ?? []);

            if (isset($lines[(int) $index])) {
                $lines[(int) $index]['variant_id'] = $variantId;
                $lines[(int) $index]['modifier_ids'] = $modifierIds;
                $lines[(int) $index]['notes'] = $notes;
                $this->data['lines'] = $lines;
            }
        } else {
            $this->incrementOrPushLine($menuItemId, $variantId, $modifierIds, $notes);
        }

        return $this->posUiState();
    }

    /**
     * @return array<string, mixed>
     */
    private function posShellViewData(): array
    {
        $restaurantId = TenantContext::restaurantId();
        $catalog = CashierMenuCatalog::posPayload($restaurantId);
        $state = $this->posUiState($catalog);

        $user = auth()->user();
        $isOwnerOrOperator = $user instanceof User && ($user->isPlatformOperator() || $user->isRestaurantOwner());

        return [
            'canViewDrawerCash' => $isOwnerOrOperator,
            'catalog' => $catalog,
            'categories' => CashierMenuCatalog::categories($restaurantId),
            'hasUncategorized' => collect($catalog)->contains(fn (array $item): bool => blank($item['category_id'] ?? null)),
            'tables' => $this->posTableOptions(),
            'hasFonnte' => TenantContext::restaurant()?->hasFonnteKey() ?? false,
            'autoPrintReceipt' => (bool) (TenantContext::outlet()?->auto_print_receipt ?? false),
            'qrisImageUrl' => CmsMedia::url(TenantContext::outlet()?->qris_image_path),
            'tableId' => $this->data['table_id'] ?? '',
            'customerName' => $this->data['customer_name'] ?? '',
            'customerWa' => $this->data['customer_wa'] ?? '',
            'sendReceipt' => (bool) ($this->data['send_receipt'] ?? false),
            'cashReceived' => $this->data['cash_received'] ?? null,
            'pointsToRedeem' => (int) ($this->data['points_to_redeem'] ?? 0),
            'loyaltySettings' => TenantContext::restaurant()?->loyaltySettings() ?? [],
            'activeShift' => $this->activeShift,
            ...$state,
        ];
    }

    /**
     * @param  list<array<string, mixed>>|null  $catalog
     * @return array<string, mixed>
     */
    private function posUiState(?array $catalog = null): array
    {
        $catalog ??= CashierMenuCatalog::posPayload(TenantContext::restaurantId());
        $lines = array_values($this->data['lines'] ?? []);
        $paymentMethod = (string) ($this->data['payment_method'] ?? 'cash');

        $restaurant = TenantContext::restaurant();
        $pointsRequested = (int) ($this->data['points_to_redeem'] ?? 0);
        $customerWa = $this->data['customer_wa'] ?? null;
        $discountAmount = 0;
        $pointsRedeemed = 0;

        if ($restaurant && filled($customerWa) && $pointsRequested > 0) {
            $normalizedWa = WhatsAppNumber::normalize((string) $customerWa);
            if ($normalizedWa) {
                $customer = \App\Models\Customer::query()
                    ->where('restaurant_id', $restaurant->id)
                    ->where('phone', $normalizedWa)
                    ->first();

                if ($customer) {
                    $rawSubtotal = 0;
                    foreach ($lines as $line) {
                        $rawSubtotal += CashierOrderPreview::lineTotal($line);
                    }

                    $calc = app(\App\Services\CustomerCrmService::class)
                        ->calculateRedemption($customer, $rawSubtotal, $pointsRequested, $restaurant);

                    if ($calc['allowed']) {
                        $discountAmount = $calc['discount_amount'];
                        $pointsRedeemed = $calc['points'];
                    }
                }
            }
        }

        $estimate = CashierOrderPreview::estimateFromLines(
            $lines,
            TenantContext::outlet(),
            $paymentMethod,
            $this->data['cash_received'] ?? null,
            $discountAmount,
        );
        $estimate['points_redeemed'] = $pointsRedeemed;

        return [
            'plainQtyByItem' => $this->plainQtyByItem($lines),
            'cartLines' => $this->posCartLines($catalog, $lines),
            'preview' => $this->presentPreview($estimate),
            'paymentMethod' => $paymentMethod,
        ];
    }

    /**
     * @param  array<string, mixed>  $preview
     * @return array<string, mixed>
     */
    private function presentPreview(array $preview): array
    {
        $formatPct = static function (float $pct): string {
            return rtrim(rtrim(number_format($pct, 2, ',', '.'), '0'), ',');
        };

        return [
            ...$preview,
            'discount_label' => CmsMedia::formatIdr((int) ($preview['discount_amount'] ?? 0)),
            'subtotal_label' => CmsMedia::formatIdr((int) ($preview['subtotal'] ?? 0)),
            'service_label' => CmsMedia::formatIdr((int) ($preview['service_amount'] ?? 0)),
            'pb1_label' => CmsMedia::formatIdr((int) ($preview['pb1_amount'] ?? 0)),
            'grand_label' => CmsMedia::formatIdr((int) ($preview['grand_payable'] ?? 0)),
            'service_pct_label' => $formatPct((float) ($preview['service_pct'] ?? 0)),
            'pb1_pct_label' => $formatPct((float) ($preview['pb1_pct'] ?? 0)),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function posTableOptions(): array
    {
        return DiningTable::query()
            ->where('restaurant_id', TenantContext::restaurantId())
            ->where('is_out_of_service', false)
            ->where(function ($query) {
                $query->where('needs_cleaning', false)
                    ->orWhereHas('outlet', fn ($q) => $q->where('simple_mode', true));
            })
            ->whereNull('open_visit_id')
            ->orderBy('code')
            ->pluck('code', 'id')
            ->all();
    }

    /**
     * @param  list<array<string, mixed>>  $catalog
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function posCartLines(array $catalog, array $lines): array
    {
        $byId = collect($catalog)->keyBy('id');
        $cart = [];

        foreach ($lines as $index => $line) {
            $itemId = (int) ($line['menu_item_id'] ?? 0);
            $item = $byId->get($itemId);
            $modifierIds = $this->normalizedModifierIds($line['modifier_ids'] ?? []);
            $modifierLabels = collect(is_array($item) ? ($item['modifiers'] ?? []) : [])
                ->keyBy('id');
            $extras = [];

            foreach ($modifierIds as $modifierId) {
                $label = $modifierLabels->get($modifierId)['label'] ?? null;

                if (filled($label)) {
                    $extras[] = $label;
                }
            }

            $variantId = filled($line['variant_id'] ?? null) ? (int) $line['variant_id'] : null;
            $variantName = null;
            if ($variantId && is_array($item)) {
                foreach ($item['variants'] ?? [] as $v) {
                    if ((int) ($v['id'] ?? 0) === $variantId) {
                        $variantName = (string) ($v['name'] ?? '');
                        break;
                    }
                }
            }

            $notes = filled($line['notes'] ?? null) ? (string) $line['notes'] : null;
            $total = CashierOrderPreview::lineTotal($line);

            $cart[] = [
                'index' => $index,
                'menu_item_id' => $itemId,
                'variant_id' => $variantId,
                'variant_name' => $variantName,
                'qty' => (int) ($line['qty'] ?? 0),
                'name' => is_array($item) ? (string) $item['name'] : 'Item',
                'photo_url' => is_array($item) ? ($item['photo_url'] ?? null) : null,
                'extras' => $extras,
                'modifier_ids' => $modifierIds,
                'notes' => $notes,
                'total' => $total,
                'total_label' => CmsMedia::formatIdr($total),
                'is_plain' => $variantId === null && $modifierIds === [] && $notes === null,
            ];
        }

        return $cart;
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     * @return array<int, int>
     */
    private function plainQtyByItem(array $lines): array
    {
        $qty = [];

        foreach ($lines as $line) {
            if (! $this->isPlainLine($line)) {
                continue;
            }

            $itemId = (int) ($line['menu_item_id'] ?? 0);
            $qty[$itemId] = (int) ($line['qty'] ?? 0);
        }

        return $qty;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function posItem(int $menuItemId): ?array
    {
        foreach (CashierMenuCatalog::posPayload(TenantContext::restaurantId()) as $item) {
            if ((int) $item['id'] === $menuItemId) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param  list<int>  $modifierIds
     */
    private function incrementOrPushLine(int $menuItemId, ?int $variantId, array $modifierIds, ?string $notes): void
    {
        $lines = array_values($this->data['lines'] ?? []);
        $variantId = $variantId ? (int) $variantId : null;
        $modifierIds = $this->normalizedModifierIds($modifierIds);
        $notes = filled($notes) ? $notes : null;

        foreach ($lines as $index => $line) {
            if ((int) ($line['menu_item_id'] ?? 0) !== $menuItemId) {
                continue;
            }

            $lineVariantId = filled($line['variant_id'] ?? null) ? (int) $line['variant_id'] : null;
            if ($lineVariantId !== $variantId) {
                continue;
            }

            if ($this->normalizedModifierIds($line['modifier_ids'] ?? []) !== $modifierIds) {
                continue;
            }

            $lineNotes = filled($line['notes'] ?? null) ? (string) $line['notes'] : null;

            if ($lineNotes !== $notes) {
                continue;
            }

            $lines[$index]['qty'] = (int) ($line['qty'] ?? 0) + 1;
            $this->data['lines'] = $lines;

            return;
        }

        $lines[] = [
            'menu_item_id' => $menuItemId,
            'variant_id' => $variantId,
            'qty' => 1,
            'modifier_ids' => $modifierIds,
            'notes' => $notes,
        ];

        $this->data['lines'] = $lines;
    }

    private function plainLineIndex(int $menuItemId): ?int
    {
        foreach (array_values($this->data['lines'] ?? []) as $index => $line) {
            if ((int) ($line['menu_item_id'] ?? 0) === $menuItemId && $this->isPlainLine($line)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $line
     */
    private function isPlainLine(array $line): bool
    {
        return blank($line['variant_id'] ?? null)
            && $this->normalizedModifierIds($line['modifier_ids'] ?? []) === []
            && blank($line['notes'] ?? null);
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function linesForPos(array $lines): array
    {
        $normalized = [];

        foreach (array_values($lines) as $line) {
            if (! is_array($line) || blank($line['menu_item_id'] ?? null)) {
                continue;
            }

            $normalized[] = [
                'menu_item_id' => (int) $line['menu_item_id'],
                'variant_id' => filled($line['variant_id'] ?? null) ? (int) $line['variant_id'] : null,
                'qty' => max(1, (int) ($line['qty'] ?? 1)),
                'modifier_ids' => $this->normalizedModifierIds($line['modifier_ids'] ?? []),
                'notes' => filled($line['notes'] ?? null) ? (string) $line['notes'] : null,
            ];
        }

        return $normalized;
    }

    /**
     * @return list<int>
     */
    private function normalizedModifierIds(mixed $modifierIds): array
    {
        return collect(is_array($modifierIds) ? $modifierIds : [])
            ->filter()
            ->map(fn (mixed $id): int => (int) $id)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedPosData(): array
    {
        $data = $this->data ?? [];
        $lines = $this->linesForPos($data['lines'] ?? []);

        validator(
            [
                'table_id' => $data['table_id'] ?? null,
                'payment_method' => $data['payment_method'] ?? null,
                'lines' => $lines,
            ],
            [
                'table_id' => ['required'],
                'payment_method' => ['required', 'in:cash,qris'],
                'lines' => ['required', 'array', 'min:1'],
                'lines.*.menu_item_id' => ['required', 'integer'],
                'lines.*.variant_id' => ['nullable', 'integer'],
                'lines.*.qty' => ['required', 'integer', 'min:1'],
            ],
            [
                'table_id.required' => 'Pilih meja.',
                'lines.min' => 'Pilih minimal satu menu.',
                'lines.required' => 'Pilih minimal satu menu.',
            ],
        )->validate();

        return [
            'table_id' => $data['table_id'],
            'customer_wa' => $data['customer_wa'] ?? null,
            'customer_name' => $data['customer_name'] ?? null,
            'payment_method' => $data['payment_method'] ?? 'cash',
            'send_receipt' => (bool) ($data['send_receipt'] ?? false),
            'cash_received' => $data['cash_received'] ?? null,
            'points_to_redeem' => (int) ($data['points_to_redeem'] ?? 0),
            'lines' => $lines,
        ];
    }

    public function getActiveShiftProperty(): ?array
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return null;
        }

        $outlet = TenantContext::outlet();
        if (! $outlet) {
            return null;
        }

        $shift = app(CashierShiftService::class)->getCurrentOpenShift($user, $outlet);
        if (! $shift) {
            return null;
        }

        $calc = app(CashierShiftService::class)->calculateExpectedCash($shift);
        $isOwnerOrOperator = $user->isPlatformOperator() || $user->isRestaurantOwner();

        return [
            'id' => $shift->id,
            'public_id' => $shift->public_id,
            'user_name' => $shift->user?->name ?? 'Kasir',
            'starting_cash' => $calc['starting_cash'],
            'cash_sales' => $isOwnerOrOperator ? $calc['cash_sales'] : null,
            'non_cash_sales' => $isOwnerOrOperator ? $calc['non_cash_sales'] : null,
            'cash_in' => $isOwnerOrOperator ? $calc['cash_in'] : null,
            'cash_out' => $isOwnerOrOperator ? $calc['cash_out'] : null,
            'expected_cash' => $isOwnerOrOperator ? $calc['expected_cash'] : null,
            'total_sales' => $isOwnerOrOperator ? $calc['total_sales'] : null,
            'opened_at' => $shift->opened_at->format('H:i'),
            'print_url' => route('shifts.print', ['shift' => $shift->public_id]),
        ];
    }

    public function openShift(mixed $startingCash, ?string $notes = null): ?array
    {
        $user = auth()->user();
        $outlet = TenantContext::outlet();
        if (! $user instanceof User || ! $outlet) {
            return null;
        }

        $parsed = IdrAmount::parse($startingCash) ?? 0;
        if ($parsed < 0) {
            Notification::make()->title('Modal awal tidak valid')->danger()->send();

            return null;
        }

        try {
            app(CashierShiftService::class)->openShift($user, $outlet, $parsed, $notes);
            Notification::make()->title('Shift kasir berhasil dibuka')->success()->send();

            return ['activeShift' => $this->activeShift];
        } catch (ValidationException $e) {
            Notification::make()->title(collect($e->errors())->flatten()->first() ?: 'Gagal membuka shift')->danger()->send();

            return null;
        }
    }

    #[Renderless]
    public function recordCashMovement(string $type, mixed $amount, string $category = 'lainnya', ?string $notes = null): ?array
    {
        $user = auth()->user();
        $outlet = TenantContext::outlet();
        if (! $user instanceof User || ! $outlet) {
            return null;
        }

        $shift = app(CashierShiftService::class)->getCurrentOpenShift($user, $outlet);
        if (! $shift) {
            Notification::make()->title('Tidak ada shift aktif')->danger()->send();

            return null;
        }

        $parsedAmount = IdrAmount::parse($amount) ?? 0;
        if ($parsedAmount <= 0) {
            Notification::make()->title('Nominal mutasi kas harus lebih dari 0')->danger()->send();

            return null;
        }

        try {
            app(CashierShiftService::class)->recordCashMovement($shift, $user, $type, $parsedAmount, $category, $notes);
            $label = $type === 'cash_in' ? 'Kas masuk' : 'Kas keluar';
            Notification::make()->title("{$label} berhasil dicatat")->success()->send();

            return ['activeShift' => $this->activeShift];
        } catch (ValidationException $e) {
            Notification::make()->title(collect($e->errors())->flatten()->first() ?: 'Gagal mencatat mutasi kas')->danger()->send();

            return null;
        }
    }

    public function closeShift(mixed $actualEndingCash, ?string $differenceReason = null, ?string $notes = null): ?array
    {
        $user = auth()->user();
        $outlet = TenantContext::outlet();
        if (! $user instanceof User || ! $outlet) {
            return null;
        }

        $shift = app(CashierShiftService::class)->getCurrentOpenShift($user, $outlet);
        if (! $shift) {
            Notification::make()->title('Tidak ada shift aktif untuk ditutup')->danger()->send();

            return null;
        }

        $parsedActual = IdrAmount::parse($actualEndingCash) ?? 0;
        if ($parsedActual < 0) {
            Notification::make()->title('Uang fisik di laci tidak valid')->danger()->send();

            return null;
        }

        try {
            $closed = app(CashierShiftService::class)->closeShift($shift, $user, $parsedActual, $differenceReason, $notes);
            Notification::make()->title('Shift kasir #'.$closed->id.' berhasil ditutup')->success()->send();

            return [
                'closedShift' => [
                    'id' => $closed->id,
                    'public_id' => $closed->public_id,
                    'starting_cash' => (int) $closed->starting_cash,
                    'cash_sales' => (int) $closed->cash_sales,
                    'non_cash_sales' => (int) $closed->non_cash_sales,
                    'cash_in' => (int) $closed->cash_in,
                    'cash_out' => (int) $closed->cash_out,
                    'expected_cash' => (int) $closed->expected_ending_cash,
                    'actual_cash' => (int) $closed->actual_ending_cash,
                    'difference' => (int) $closed->cash_difference,
                    'difference_reason' => $closed->difference_reason,
                    'print_url' => route('shifts.print', ['shift' => $closed->public_id]),
                ],
                'activeShift' => null,
            ];
        } catch (ValidationException $e) {
            Notification::make()->title(collect($e->errors())->flatten()->first() ?: 'Gagal menutup shift')->danger()->send();

            return null;
        }
    }
}
