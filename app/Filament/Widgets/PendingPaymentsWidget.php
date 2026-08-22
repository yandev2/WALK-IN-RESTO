<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Support\TableRightClick;
use App\Models\Order;
use App\Models\User;
use App\Support\SubscriptionAccess;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingPaymentsWidget extends TableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = [
        'default' => 'full',
    ];

    public static function canView(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin()
            || $user->can('order.verify_payment')
            || $user->can('order.reject_payment'))
            && SubscriptionAccess::allows('operations');
    }

    public function table(Table $table): Table
    {
        $table = $table
            ->heading('Antrian kasir')
            ->description('Pesanan menunggu verifikasi pembayaran.')
            ->query(fn (): Builder => Order::query()
                ->with(['visit.diningTable'])
                ->when(Filament::getTenant()?->getKey(), fn (Builder $query, $tenantId) => $query->where('restaurant_id', $tenantId))
                ->where('status', 'awaiting_cashier')
                ->latest())
            ->columns([
                TextColumn::make('number')->label('No'),
                TextColumn::make('visit.diningTable.code')->label('Meja'),
                TextColumn::make('payment_method')->label('Metode'),
                TextColumn::make('grand_payable')->label('Tagihan')->money('IDR', locale: 'id'),
                TextColumn::make('visit.customer_wa')->label('WA'),
                TextColumn::make('created_at')->label('Masuk')->since(),
            ])
            ->paginated([5, 10])
            ->emptyStateHeading('Antrian kosong')
            ->emptyStateDescription('Pesanan baru akan muncul di sini setelah tamu checkout.');

        return TableRightClick::apply($table, fn (): array => [
            ViewAction::make()
                ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record])),
        ]);
    }
}
