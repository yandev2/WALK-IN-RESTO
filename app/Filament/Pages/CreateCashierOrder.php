<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\DiningTable;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Support\CashierMenuCatalog;
use App\Support\CashierOrderPreview;
use App\Support\CmsMedia;
use App\Support\IdrAmount;
use App\Support\SubscriptionAccess;
use App\Support\TenantContext;
use App\Support\WhatsAppNumber;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Session;

class CreateCashierOrder extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Order kasir';

    protected static ?string $title = 'Buat order kasir';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    #[Session(key: 'cashier_order_ui')]
    public string $cashierUi = 'form';

    /**
     * @var array{type: 'new'|'edit', menu_item_id: int, index?: int, modifier_ids: list<int>, notes: string}|null
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
        $this->form->fill([
            'send_receipt' => false,
            'payment_method' => 'cash',
            'lines' => [['qty' => 1]],
        ]);

        if ($this->isPosUi()) {
            $this->data['lines'] = $this->linesForPos($this->data['lines'] ?? []);
        }
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return $this->isPosUi() ? Width::Full : null;
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleCashierUi')
                ->label(fn (): string => $this->isPosUi() ? 'Tampilan form' : 'Tampilan grid')
                ->icon(fn (): Heroicon => $this->isPosUi() ? Heroicon::OutlinedQueueList : Heroicon::OutlinedSquares2x2)
                ->action(function (): void {
                    $this->toggleCashierUi();
                }),
        ];
    }

    public function isPosUi(): bool
    {
        return $this->cashierUi === 'pos';
    }

    public function toggleCashierUi(): void
    {
        if ($this->isPosUi()) {
            $this->data['lines'] = $this->linesForForm($this->data['lines'] ?? []);
            $this->cashierUi = 'form';
            $this->posEditor = null;
            $this->cacheSchema('content', null);
            $this->form->fill($this->data);

            return;
        }

        $this->data['lines'] = $this->linesForPos($this->data['lines'] ?? []);
        $this->cashierUi = 'pos';
        $this->posEditor = null;
        $this->posSearch = '';
        $this->posCategory = 'all';
        $this->cacheSchema('content', null);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1);
    }

    public function form(Schema $schema): Schema
    {
        $hasFonnte = TenantContext::restaurant()?->hasFonnteKey() ?? false;

        return $schema
            ->components([

                Section::make('Tamu')
                    ->description('Data meja dan tamu untuk visit baru.')
                    ->icon(Heroicon::OutlinedUser)
                    ->columns(4)
                    ->schema([
                        Select::make('table_id')
                            ->label('Meja')
                            ->options(fn (): array => DiningTable::query()
                                ->where('restaurant_id', TenantContext::restaurantId())
                                ->where('is_out_of_service', false)
                                ->where('needs_cleaning', false)
                                ->whereNull('open_visit_id')
                                ->orderBy('code')
                                ->pluck('code', 'id')
                                ->all())
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->helperText('Hanya meja kosong dan siap dipakai.'),
                        TextInput::make('customer_wa')
                            ->label('WhatsApp tamu')
                            ->placeholder('08xxxxxxxxxx')
                            ->maxLength(20)
                            ->live()
                            ->helperText('Opsional. Wajib jika struk dikirim via WhatsApp.')
                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                if (blank($state)) {
                                    $set('send_receipt', false);

                                    return;
                                }

                                if (TenantContext::restaurant()?->hasFonnteKey()) {
                                    $set('send_receipt', true);
                                }
                            }),
                        TextInput::make('customer_name')
                            ->label('Nama tamu')
                            ->maxLength(120),
                        Select::make('payment_method')
                            ->label('Metode bayar')
                            ->options([
                                'cash' => 'Tunai',
                                'qris' => 'QRIS',
                            ])
                            ->required()
                            ->native(false)
                            ->live()
                            ->partiallyRenderComponentsAfterStateUpdated(['/form.cashier-order-totals']),
                        Toggle::make('send_receipt')
                            ->label('Kirim struk WhatsApp')
                            ->visible($hasFonnte)
                            ->default($hasFonnte)
                            ->live()
                            ->disabled(fn (Get $get): bool => blank($get('customer_wa')))
                            ->dehydrated()
                            ->helperText('Kirim ringkasan order ke nomor tamu setelah dibuat.')
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),

                Grid::make(5)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Menu')
                            ->columnSpan(3)
                            ->description('Tambah satu atau lebih item ke pesanan.')
                            ->icon(Heroicon::OutlinedShoppingBag)
                            ->schema([
                                View::make('filament.components.menu-item-select-styles'),
                                Repeater::make('lines')
                                    ->hiddenLabel()
                                    ->schema([
                                        Select::make('menu_item_id')
                                            ->label('Menu')
                                            ->options(fn (): array => CashierMenuCatalog::selectOptions(TenantContext::restaurantId()))
                                            ->getOptionLabelUsing(fn (mixed $value): ?string => CashierMenuCatalog::optionLabel(
                                                TenantContext::restaurantId(),
                                                $value,
                                            ))
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->allowHtml()
                                            ->live()
                                            ->partiallyRenderComponentsAfterStateUpdated(['/form.cashier-order-totals'])
                                            ->columnSpanFull(),
                                        TextInput::make('qty')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(1)
                                            ->required()
                                            ->suffix('x')
                                            ->live()
                                            ->partiallyRenderComponentsAfterStateUpdated(['/form.cashier-order-totals']),
                                        Select::make('modifier_ids')
                                            ->label('Extra')
                                            ->multiple()
                                            ->options(fn (Get $get): array => CashierMenuCatalog::modifierSelectOptions($get('menu_item_id')))
                                            ->native(false)
                                            ->live()
                                            ->partiallyRenderComponentsAfterStateUpdated(['/form.cashier-order-totals']),
                                        Textarea::make('notes')
                                            ->label('Catatan')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->minItems(1)
                                    ->required()
                                    ->columnSpanFull()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): string => CashierMenuCatalog::itemName(
                                        TenantContext::restaurantId(),
                                        $state['menu_item_id'] ?? null,
                                    ))
                                    ->addActionLabel('Tambah item')
                                    ->partiallyRenderComponentsAfterStateUpdated(['/form.cashier-order-totals']),
                            ]),

                        Section::make('Ringkasan pembayaran')
                            ->columnSpan(2)
                            ->description('Perkiraan total dihitung otomatis dari item yang dipilih.')
                            ->icon(Heroicon::OutlinedReceiptPercent)
                            ->schema([
                                Hidden::make('cash_received')
                                    ->dehydrated(fn (Get $get): bool => ($get('payment_method') ?? 'cash') === 'cash'),
                                View::make('filament.pages.partials.cashier-order-totals')
                                    ->key('cashier-order-totals')
                                    ->viewData(fn (Get $get): array => [
                                        'preview' => CashierOrderPreview::estimateFromLines(
                                            $get('lines') ?? [],
                                            TenantContext::outlet(),
                                            (string) ($get('payment_method') ?? 'cash'),
                                        ),
                                        'cashReceived' => $get('cash_received'),
                                    ]),
                            ]),
                    ]),

            ]);
    }

    public function create(): void
    {
        try {
            $data = $this->isPosUi()
                ? $this->validatedPosData()
                : $this->form->getState();
        } catch (ValidationException $e) {
            if ($this->isPosUi()) {
                Notification::make()
                    ->title(collect($e->errors())->flatten()->first() ?: 'Order tidak bisa dibuat')
                    ->danger()
                    ->send();
            }

            throw $e;
        }
        $tenant = Filament::getTenant();
        $user = auth()->user();

        if (! $tenant instanceof Restaurant || ! $user instanceof User) {
            return;
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
            );
        } catch (ValidationException $e) {
            Notification::make()
                ->title(collect($e->errors())->flatten()->first() ?: 'Order tidak bisa dibuat')
                ->danger()
                ->send();

            throw $e;
        }

        Notification::make()
            ->title('Order kasir masuk antrian')
            ->success()
            ->send();

        $this->redirect(OrderResource::getUrl('view', ['record' => $order], tenant: $tenant));
    }

    public function content(Schema $schema): Schema
    {
        if ($this->isPosUi()) {
            return $schema
                ->components([
                    View::make('filament.pages.partials.cashier-pos-shell')
                        ->viewData(fn (): array => $this->posShellViewData()),
                ]);
        }

        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('create')
                    ->footer([
                        Actions::make([
                            Action::make('create')
                                ->label('Buat pesanan')
                                ->submit('create'),
                        ]),
                    ]),
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

        if ($item['has_modifiers']) {
            return $this->posUiState();
        }

        $this->incrementOrPushLine($menuItemId, [], null);

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
        if (! $this->isPosUi() || ! in_array($field, ['table_id', 'customer_name', 'customer_wa', 'send_receipt', 'cash_received'], true)) {
            return;
        }

        if ($field === 'send_receipt') {
            $this->data[$field] = (bool) $value;

            return;
        }

        $this->data[$field] = $value === '' ? null : $value;

        if ($field === 'customer_wa') {
            if (blank($this->data['customer_wa'] ?? null)) {
                $this->data['send_receipt'] = false;
            } elseif (TenantContext::restaurant()?->hasFonnteKey()) {
                $this->data['send_receipt'] = true;
            }
        }
    }

    #[Renderless]
    public function commitPosEditor(string $type, int $menuItemId, ?int $index, array $modifierIds, ?string $notes): array
    {
        if (! $this->isPosUi()) {
            return $this->posUiState();
        }

        $modifierIds = $this->normalizedModifierIds($modifierIds);
        $notes = filled($notes) ? (string) $notes : null;

        if ($menuItemId < 1 || $this->posItem($menuItemId) === null) {
            return $this->posUiState();
        }

        if ($type === 'edit') {
            $lines = array_values($this->data['lines'] ?? []);

            if (isset($lines[(int) $index])) {
                $lines[(int) $index]['modifier_ids'] = $modifierIds;
                $lines[(int) $index]['notes'] = $notes;
                $this->data['lines'] = $lines;
            }
        } else {
            $this->incrementOrPushLine($menuItemId, $modifierIds, $notes);
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

        return [
            'catalog' => $catalog,
            'categories' => CashierMenuCatalog::categories($restaurantId),
            'hasUncategorized' => collect($catalog)->contains(fn (array $item): bool => blank($item['category_id'] ?? null)),
            'tables' => $this->posTableOptions(),
            'hasFonnte' => TenantContext::restaurant()?->hasFonnteKey() ?? false,
            'qrisImageUrl' => CmsMedia::url(TenantContext::outlet()?->qris_image_path),
            'tableId' => $this->data['table_id'] ?? '',
            'customerName' => $this->data['customer_name'] ?? '',
            'customerWa' => $this->data['customer_wa'] ?? '',
            'sendReceipt' => (bool) ($this->data['send_receipt'] ?? false),
            'cashReceived' => $this->data['cash_received'] ?? null,
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

        return [
            'plainQtyByItem' => $this->plainQtyByItem($lines),
            'cartLines' => $this->posCartLines($catalog, $lines),
            'preview' => $this->presentPreview(
                CashierOrderPreview::estimateFromLines(
                    $lines,
                    TenantContext::outlet(),
                    $paymentMethod,
                ),
            ),
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
            ->where('needs_cleaning', false)
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

            $notes = filled($line['notes'] ?? null) ? (string) $line['notes'] : null;
            $total = CashierOrderPreview::lineTotal($line);

            $cart[] = [
                'index' => $index,
                'menu_item_id' => $itemId,
                'qty' => (int) ($line['qty'] ?? 0),
                'name' => is_array($item) ? (string) $item['name'] : 'Item',
                'photo_url' => is_array($item) ? ($item['photo_url'] ?? null) : null,
                'extras' => $extras,
                'modifier_ids' => $modifierIds,
                'notes' => $notes,
                'total' => $total,
                'total_label' => CmsMedia::formatIdr($total),
                'is_plain' => $modifierIds === [] && $notes === null,
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
    private function incrementOrPushLine(int $menuItemId, array $modifierIds, ?string $notes): void
    {
        $lines = array_values($this->data['lines'] ?? []);
        $modifierIds = $this->normalizedModifierIds($modifierIds);
        $notes = filled($notes) ? $notes : null;

        foreach ($lines as $index => $line) {
            if ((int) ($line['menu_item_id'] ?? 0) !== $menuItemId) {
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
        return $this->normalizedModifierIds($line['modifier_ids'] ?? []) === []
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
                'qty' => max(1, (int) ($line['qty'] ?? 1)),
                'modifier_ids' => $this->normalizedModifierIds($line['modifier_ids'] ?? []),
                'notes' => filled($line['notes'] ?? null) ? (string) $line['notes'] : null,
            ];
        }

        return $normalized;
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function linesForForm(array $lines): array
    {
        $normalized = $this->linesForPos($lines);

        return $normalized === [] ? [['qty' => 1]] : $normalized;
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
            'lines' => $lines,
        ];
    }
}
