<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Modifier;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Support\CashierOrderPreview;
use App\Support\CmsMedia;
use App\Support\SubscriptionAccess;
use App\Support\TenantContext;
use Filament\Actions\Action;
use Filament\Facades\Filament;
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
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;
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
            'send_receipt' => TenantContext::restaurant()?->hasFonnteKey() ?? false,
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
                    ->columns(2)
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
                            ->required()
                            ->maxLength(20),
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
                            ->helperText('Kirim ringkasan order ke nomor tamu setelah dibuat.')
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),

                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Menu')
                            ->columnSpan(2)
                            ->description('Tambah satu atau lebih item ke pesanan.')
                            ->icon(Heroicon::OutlinedShoppingBag)
                            ->schema([
                                Repeater::make('lines')
                                    ->hiddenLabel()
                                    ->schema([
                                        Select::make('menu_item_id')
                                            ->label('Menu')
                                            ->options(fn (): array => self::menuItemSelectOptions())
                                            ->getOptionLabelUsing(function (mixed $value): ?string {
                                                if (blank($value)) {
                                                    return null;
                                                }

                                                $item = MenuItem::query()->find($value);

                                                if (! $item instanceof MenuItem) {
                                                    return null;
                                                }

                                                return $item->name.' — '.CmsMedia::formatIdr($item->effectivePrice());
                                            })
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->allowHtml()
                                            ->live()
                                            ->columnSpanFull(),
                                        TextInput::make('qty')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->minValue(1)
                                            ->default(1)
                                            ->required()
                                            ->suffix('x')
                                            ->live(),
                                        Select::make('modifier_ids')
                                            ->label('Extra')
                                            ->multiple()
                                            ->options(function (Get $get): array {
                                                $itemId = $get('menu_item_id');

                                                if (! $itemId) {
                                                    return [];
                                                }

                                                return Modifier::query()
                                                    ->where('is_active', true)
                                                    ->whereHas(
                                                        'group.menuItems',
                                                        fn ($query) => $query->whereKey($itemId),
                                                    )
                                                    ->orderBy('sort_order')
                                                    ->get()
                                                    ->mapWithKeys(fn (Modifier $modifier): array => [
                                                        $modifier->id => $modifier->name.(
                                                            $modifier->price
                                                                ? ' (+'.CmsMedia::formatIdr((int) $modifier->price).')'
                                                                : ''
                                                        ),
                                                    ])
                                                    ->all();
                                            })
                                            ->native(false)
                                            ->live(),
                                        Textarea::make('notes')
                                            ->label('Catatan')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->minItems(1)
                                    ->required()
                                    ->columnSpan(2)
                                    ->live()
                                    ->partiallyRenderComponentsAfterStateUpdated(['/form.cashier-order-totals'])
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): string => filled($state['menu_item_id'] ?? null)
                                        ? (MenuItem::query()->find($state['menu_item_id'])?->name ?? 'Item')
                                        : 'Item baru')
                                    ->addActionLabel('Tambah item'),
                            ]),

                        View::make('filament.pages.partials.cashier-order-totals')
                            ->key('cashier-order-totals')
                            ->viewData(fn (Get $get): array => [
                                'preview' => CashierOrderPreview::estimateFromLines(
                                    $get('lines') ?? [],
                                    TenantContext::outlet(),
                                    (string) ($get('payment_method') ?? 'cash'),
                                ),
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

        try {
            $order = app(CashierOrderService::class)->create(
                $user,
                $table,
                $data['customer_wa'],
                $data['customer_name'] ?? null,
                $data['payment_method'],
                (bool) ($data['send_receipt'] ?? false),
                $data['lines'] ?? [],
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

    /**
     * @return array<int, string>
     */
    protected static function menuItemSelectOptions(): array
    {
        return self::availableMenuItems()
            ->mapWithKeys(fn (MenuItem $item): array => [
                $item->id => view('filament.components.menu-item-select-option', [
                    'item' => $item,
                ])->render(),
            ])
            ->all();
    }

    /**
     * @return Collection<int, MenuItem>
     */
    protected static function availableMenuItems(): Collection
    {
        return MenuItem::query()
            ->where('restaurant_id', TenantContext::restaurantId())
            ->where('is_active', true)
            ->where('is_out_of_stock', false)
            ->whereNotNull('station_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
