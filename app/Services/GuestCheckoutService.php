<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use App\Models\Outlet;
use App\Models\Payment;
use App\Models\Visit;
use App\Models\VisitCartItem;
use App\Support\CashTender;
use App\Support\CmsMedia;
use App\Support\GeoDistance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GuestCheckoutService
{
    public function __construct(private MenuModifierService $modifiers) {}

    /**
     * @param  array{lat?: float|null, lng?: float|null, accuracy?: float|null, gps_status?: string|null}  $gps
     */
    public function checkout(Visit $visit, string $method, bool $sendReceipt, string $idempotencyKey, array $gps = []): Order
    {
        $this->assertMethod($method);
        $this->assertVisitReady($visit);

        $existing = $this->existingOrder($visit, $idempotencyKey);

        if ($existing) {
            return $existing;
        }

        $visit->loadMissing(['outlet.restaurant', 'diningTable']);
        $outlet = $visit->outlet;
        $this->assertOutletOpen($outlet, enforceHours: true);

        $gpsResult = $this->evaluateGps($method, $outlet, $gps);

        if ($gpsResult['reject']) {
            throw ValidationException::withMessages([
                'gps' => 'Anda di luar area restoran. Pakai QRIS, atau pastikan berada di resto.',
            ]);
        }

        return DB::transaction(function () use ($visit, $method, $sendReceipt, $idempotencyKey, $outlet, $gpsResult, $gps) {
            $cart = VisitCartItem::query()
                ->where('visit_id', $visit->id)
                ->with(['menuItem.station', 'variant', 'modifiers'])
                ->lockForUpdate()
                ->get();

            if ($cart->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Keranjang kosong.']);
            }

            $lineInputs = $cart->map(fn (VisitCartItem $row): array => [
                'menu_item_id' => $row->menu_item_id,
                'variant_id' => $row->menu_variant_id,
                'qty' => (int) $row->qty,
                'notes' => $row->notes,
                'modifier_ids' => $row->modifiers->pluck('id')->all(),
            ])->all();

            $order = $this->createOrder(
                $visit,
                $outlet,
                $method,
                $sendReceipt,
                $idempotencyKey,
                'guest',
                $lineInputs,
                $gpsResult,
                $gps,
                null,
            );

            VisitCartItem::query()->where('visit_id', $visit->id)->delete();
            $this->extendClaimIfNeeded($visit, $outlet);

            return $order;
        });
    }

    /**
     * @param  list<array{menu_item_id: int, variant_id?: int|null, qty: int, notes?: string|null}>  $lineInputs
     * @param  array{lat?: float|null, lng?: float|null, accuracy?: float|null, gps_status?: string|null}  $gps
     */
    public function placeOrder(
        Visit $visit,
        string $method,
        bool $sendReceipt,
        string $idempotencyKey,
        string $source,
        array $lineInputs,
        array $gps = [],
        ?int $createdByUserId = null,
        mixed $cashReceived = null,
    ): Order {
        $this->assertMethod($method);
        if ($source !== 'cashier') {
            $this->assertVisitReady($visit);
        }

        $existing = $this->existingOrder($visit, $idempotencyKey);

        if ($existing) {
            return $existing;
        }

        $visit->loadMissing(['outlet.restaurant', 'diningTable']);
        $outlet = $visit->outlet;
        $this->assertOutletOpen($outlet, enforceHours: $source !== 'cashier');

        $gpsResult = $source === 'cashier'
            ? ['reject' => false, 'status' => 'not_required', 'distance' => null]
            : $this->evaluateGps($method, $outlet, $gps);

        if ($gpsResult['reject']) {
            throw ValidationException::withMessages([
                'gps' => 'Anda di luar area restoran. Pakai QRIS, atau pastikan berada di resto.',
            ]);
        }

        if ($lineInputs === []) {
            throw ValidationException::withMessages(['lines' => 'Pilih minimal satu menu.']);
        }

        return DB::transaction(function () use ($visit, $outlet, $method, $sendReceipt, $idempotencyKey, $source, $lineInputs, $gpsResult, $gps, $createdByUserId, $cashReceived) {
            $order = $this->createOrder(
                $visit,
                $outlet,
                $method,
                $sendReceipt,
                $idempotencyKey,
                $source,
                $lineInputs,
                $gpsResult,
                $gps,
                $createdByUserId,
                $cashReceived,
            );

            $this->extendClaimIfNeeded($visit, $outlet);

            return $order;
        });
    }

    /**
     * @param  list<array{menu_item_id: int, variant_id?: int|null, qty: int, notes?: string|null}>  $lineInputs
     * @param  array{reject: bool, status: string, distance: float|null}  $gpsResult
     * @param  array{lat?: float|null, lng?: float|null, accuracy?: float|null, gps_status?: string|null}  $gps
     */
    private function createOrder(
        Visit $visit,
        Outlet $outlet,
        string $method,
        bool $sendReceipt,
        string $idempotencyKey,
        string $source,
        array $lineInputs,
        array $gpsResult,
        array $gps,
        ?int $createdByUserId,
        mixed $cashReceived = null,
    ): Order {
        $lines = $this->resolveLines($visit, $lineInputs);
        $subtotal = collect($lines)->sum(fn (array $line): int => $line['unit'] * $line['qty']);

        $pb1Pct = (float) $outlet->pb1_pct;
        $servicePct = (float) $outlet->service_pct;
        $service = (int) round($subtotal * $servicePct / 100);
        $pb1 = (int) round(($subtotal + $service) * $pb1Pct / 100);
        $grandBefore = $subtotal + $service + $pb1;

        $uniqueAdd = 0;
        $holdAmount = null;

        if ($method === 'qris') {
            [$uniqueAdd, $holdAmount] = $this->allocateUniqueAdd($outlet->id, $grandBefore);
        }

        $grandPayable = $method === 'qris' ? $grandBefore + $uniqueAdd : $grandBefore;
        $tender = CashTender::resolve($method, $grandPayable, $cashReceived);
        $number = $this->nextOrderNumber($visit->restaurant_id, $outlet->id);
        $hasFonnte = $outlet->restaurant?->hasFonnteKey() ?? false;
        $canSendReceipt = $hasFonnte && $sendReceipt && filled($visit->customer_wa);
        $isSimple = (bool) $outlet->simple_mode;
        $isSimpleCashier = $source === 'cashier' && $isSimple;

        $order = Order::query()->create([
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'number' => $number,
            'status' => $isSimpleCashier ? Order::STATUS_COMPLETED : 'awaiting_cashier',
            'source' => $source,
            'payment_method' => $method,
            'created_by_user_id' => $createdByUserId,
            'idempotency_key' => $idempotencyKey,
            'currency' => $outlet->restaurant?->currency ?: 'IDR',
            'pb1_pct_snapshot' => $pb1Pct,
            'service_pct_snapshot' => $servicePct,
            'tax_mode_snapshot' => $outlet->tax_mode ?: 'exclusive',
            'subtotal' => $subtotal,
            'discount_amount' => 0,
            'service_amount' => $service,
            'pb1_amount' => $pb1,
            'grand_before' => $grandBefore,
            'grand_payable' => $grandPayable,
            'send_receipt' => $canSendReceipt,
            'receipt_wa_snapshot' => $canSendReceipt ? $visit->customer_wa : null,
            'paid_at' => $isSimpleCashier ? now() : null,
        ]);

        foreach ($lines as $line) {
            $orderItem = OrderItem::query()->create([
                'restaurant_id' => $visit->restaurant_id,
                'outlet_id' => $outlet->id,
                'order_id' => $order->id,
                'menu_item_id' => $line['item']->id,
                'station_id' => $line['item']->station_id,
                'name_snapshot' => $line['item']->name,
                'variant_name_snapshot' => $line['variant']?->name,
                'unit_price' => $line['unit'],
                'qty' => $line['qty'],
                'notes' => $line['notes'],
                'kds_status' => $isSimple ? 'served' : 'queued',
                'queued_at' => now(),
                'served_at' => $isSimple ? now() : null,
            ]);

            foreach ($line['modifiers'] as $modifier) {
                OrderItemModifier::query()->create([
                    'restaurant_id' => $visit->restaurant_id,
                    'outlet_id' => $outlet->id,
                    'order_item_id' => $orderItem->id,
                    'modifier_id' => $modifier->id,
                    'name_snapshot' => $modifier->name,
                    'price_snapshot' => (int) $modifier->price,
                ]);
            }
        }

        $ttl = (int) ($outlet->awaiting_cashier_ttl_minutes ?: 20);

        $paymentPublicId = (string) Str::ulid();
        $qrisSnapshotPath = $method === 'qris'
            ? $this->copyQrisSnapshot($outlet->qris_image_path, $visit->restaurant_id, $paymentPublicId)
            : null;

        Payment::query()->create([
            'public_id' => $paymentPublicId,
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $outlet->id,
            'order_id' => $order->id,
            'method' => $method,
            'provider' => 'manual',
            'status' => $isSimpleCashier ? 'paid' : 'awaiting_cashier',
            'amount' => $grandPayable,
            'cash_received' => $tender['cash_received'],
            'change_amount' => $tender['change_amount'],
            'unique_add' => $uniqueAdd,
            'qris_hold_amount' => $isSimpleCashier ? null : $holdAmount,
            'qris_image_path_snapshot' => $qrisSnapshotPath,
            'gps_status' => $gpsResult['status'],
            'gps_latitude' => $gps['lat'] ?? null,
            'gps_longitude' => $gps['lng'] ?? null,
            'gps_accuracy_m' => $gps['accuracy'] ?? null,
            'gps_distance_m' => $gpsResult['distance'],
            'awaiting_expires_at' => $isSimpleCashier ? null : now()->addMinutes($ttl),
            'paid_at' => $isSimpleCashier ? now() : null,
            'paid_by_user_id' => $isSimpleCashier ? $createdByUserId : null,
        ]);

        if ($isSimpleCashier && $canSendReceipt && $createdByUserId) {
            try {
                $cashier = \App\Models\User::query()->find($createdByUserId);
                if ($cashier) {
                    app(\App\Services\OrderReceiptService::class)->afterPaid($order, $cashier);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($isSimpleCashier) {
            try {
                app(\App\Services\CashierCommissionBillingService::class)->recordOrderPaidHook($order);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $order;
    }

    /**
     * @param  list<array{menu_item_id: int, variant_id?: int|null, qty: int, notes?: string|null, modifier_ids?: list<int>}>  $lineInputs
     * @return list<array{item: MenuItem, variant: ?MenuVariant, qty: int, notes: ?string, unit: int, modifiers: Collection}>
     */
    private function resolveLines(Visit $visit, array $lineInputs): array
    {
        $lines = [];

        foreach ($lineInputs as $input) {
            $qty = (int) ($input['qty'] ?? 0);

            if ($qty < 1) {
                throw ValidationException::withMessages(['qty' => 'Jumlah tidak valid.']);
            }

            $item = MenuItem::query()
                ->with('station')
                ->where('outlet_id', $visit->outlet_id)
                ->whereKey($input['menu_item_id'])
                ->first();

            if (! $item || ! $item->is_active || $item->is_out_of_stock) {
                throw ValidationException::withMessages([
                    'menu' => ($item?->name ?: 'Item').' tidak tersedia.',
                ]);
            }

            if (! $item->station_id) {
                if ($visit->outlet?->simple_mode) {
                    $item->station_id = $visit->outlet->kdsStations()->value('id');
                }

                if (! $item->station_id) {
                    throw ValidationException::withMessages([
                        'menu' => $item->name.' belum punya stasiun dapur. Hubungi kasir.',
                    ]);
                }
            }

            $variant = null;
            $variantId = $input['variant_id'] ?? null;

            if ($variantId) {
                $variant = MenuVariant::query()
                    ->where('menu_item_id', $item->id)
                    ->where('is_active', true)
                    ->whereKey($variantId)
                    ->first();

                if (! $variant) {
                    throw ValidationException::withMessages(['variant' => 'Varian tidak valid.']);
                }
            }

            $modifiers = $this->modifiers->resolve($item, $input['modifier_ids'] ?? []);
            $unit = (int) $item->effectivePrice() + (int) ($variant?->price_delta ?? 0) + (int) $modifiers->sum('price');
            $lines[] = [
                'item' => $item,
                'variant' => $variant,
                'qty' => $qty,
                'notes' => $input['notes'] ?? null,
                'unit' => $unit,
                'modifiers' => $modifiers,
            ];
        }

        return $lines;
    }

    private function assertMethod(string $method): void
    {
        if (! in_array($method, ['qris', 'cash'], true)) {
            throw ValidationException::withMessages(['method' => 'Metode bayar tidak valid.']);
        }
    }

    private function assertVisitReady(Visit $visit): void
    {
        if (blank($visit->customer_wa)) {
            throw ValidationException::withMessages(['customer_wa' => 'Nomor WhatsApp tamu wajib ada.']);
        }
    }

    private function assertOutletOpen(?Outlet $outlet, bool $enforceHours = false): void
    {
        if (! $outlet?->is_open || ! $outlet->is_active || ! $outlet->restaurant?->is_active) {
            throw ValidationException::withMessages(['outlet' => 'Restoran sedang tutup. Pesanan baru tidak diterima.']);
        }

        if (! $enforceHours) {
            return;
        }

        $outlet->loadMissing(['operatingHours', 'closedDates', 'restaurant']);

        if (! $outlet->isOpenNow()) {
            throw ValidationException::withMessages(['outlet' => 'Restoran sedang tutup. Pesanan baru tidak diterima.']);
        }
    }

    private function existingOrder(Visit $visit, string $idempotencyKey): ?Order
    {
        return Order::query()
            ->where('outlet_id', $visit->outlet_id)
            ->where('idempotency_key', $idempotencyKey)
            ->first();
    }

    private function extendClaimIfNeeded(Visit $visit, Outlet $outlet): void
    {
        if ($visit->claim_expires_at?->isPast()) {
            $visit->forceFill([
                'claim_expires_at' => now()->addMinutes((int) ($outlet->claim_ttl_minutes ?: 10)),
            ])->save();
        }
    }

    /**
     * @param  array{lat?: float|null, lng?: float|null, accuracy?: float|null, gps_status?: string|null}  $gps
     * @return array{reject: bool, status: string, distance: float|null}
     */
    private function evaluateGps(string $method, Outlet $outlet, array $gps): array
    {
        if ($method !== 'cash') {
            return ['reject' => false, 'status' => 'not_required', 'distance' => null];
        }

        if (($gps['gps_status'] ?? null) === 'denied' || ! isset($gps['lat'], $gps['lng'])) {
            return ['reject' => false, 'status' => 'denied', 'distance' => null];
        }

        $lat = (float) $gps['lat'];
        $lng = (float) $gps['lng'];
        $accuracy = (float) ($gps['accuracy'] ?? 9999);
        $distance = GeoDistance::meters(
            (float) $outlet->latitude,
            (float) $outlet->longitude,
            $lat,
            $lng,
        );

        $radius = (int) ($outlet->geofence_radius_m ?: 30);
        $maxAccuracy = (int) ($outlet->gps_accuracy_max_m ?: 50);

        if ($distance > $radius && $accuracy <= $maxAccuracy) {
            return ['reject' => true, 'status' => 'outside_radius', 'distance' => $distance];
        }

        if ($accuracy > $maxAccuracy) {
            return ['reject' => false, 'status' => 'low_accuracy', 'distance' => $distance];
        }

        if ($distance > $radius) {
            return ['reject' => true, 'status' => 'outside_radius', 'distance' => $distance];
        }

        return ['reject' => false, 'status' => 'passed', 'distance' => $distance];
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function allocateUniqueAdd(int $outletId, int $grandBefore): array
    {
        $used = Payment::query()
            ->where('outlet_id', $outletId)
            ->where('status', 'awaiting_cashier')
            ->whereNotNull('qris_hold_amount')
            ->pluck('qris_hold_amount')
            ->all();

        $usedLookup = array_flip($used);

        foreach (range(0, 999) as $add) {
            $hold = $grandBefore + $add;
            if (! isset($usedLookup[$hold])) {
                return [$add, $hold];
            }
        }

        throw ValidationException::withMessages([
            'qris' => 'Antrian bayar penuh. Coba lagi 1 menit.',
        ]);
    }

    private function nextOrderNumber(int $restaurantId, int $outletId): int
    {
        $row = DB::table('outlet_sequences')
            ->where('outlet_id', $outletId)
            ->where('seq_key', 'order')
            ->lockForUpdate()
            ->first();

        if (! $row) {
            DB::table('outlet_sequences')->insert([
                'restaurant_id' => $restaurantId,
                'outlet_id' => $outletId,
                'seq_key' => 'order',
                'next_value' => 2,
            ]);

            return 1;
        }

        $number = (int) $row->next_value;

        DB::table('outlet_sequences')
            ->where('outlet_id', $outletId)
            ->where('seq_key', 'order')
            ->update(['next_value' => $number + 1]);

        return $number;
    }

    private function copyQrisSnapshot(?string $sourcePath, int $restaurantId, string $paymentPublicId): ?string
    {
        if (blank($sourcePath)) {
            return null;
        }

        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
        $destination = 'payment-qris-snapshots/'.$restaurantId.'/'.$paymentPublicId.'.'.$extension;

        return CmsMedia::copy($sourcePath, $destination);
    }
}
