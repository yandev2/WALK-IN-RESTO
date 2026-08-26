<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\DiningTable;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Support\CashierMenuCatalog;
use App\Support\CashierOrderPreview;
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
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\ValidationException;

class CreateCashierOrder extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Order kasir';

    protected static ?string $title = 'Buat order kasir';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

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
        $data = $this->form->getState();
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
}
