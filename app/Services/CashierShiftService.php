<?php

namespace App\Services;

use App\Models\CashierShift;
use App\Models\CashierShiftMovement;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Payment;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\ReceiptLogo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CashierShiftService
{
    public function getCurrentOpenShift(User $user, Outlet|int $outlet): ?CashierShift
    {
        $outletId = $outlet instanceof Outlet ? $outlet->id : $outlet;
        $restaurantId = $outlet instanceof Outlet ? $outlet->restaurant_id : Outlet::query()->whereKey($outletId)->value('restaurant_id');

        return CashierShift::query()
            ->when($restaurantId, fn ($q) => $q->where('restaurant_id', $restaurantId))
            ->where('outlet_id', $outletId)
            ->where('user_id', $user->id)
            ->where('status', CashierShift::STATUS_OPEN)
            ->latest('id')
            ->first();
    }

    /**
     * @return Collection<int, CashierShift>
     */
    public function getOutletOpenShifts(Outlet|int $outlet): Collection
    {
        $outletId = $outlet instanceof Outlet ? $outlet->id : $outlet;

        return CashierShift::query()
            ->where('outlet_id', $outletId)
            ->where('status', CashierShift::STATUS_OPEN)
            ->with(['user', 'outlet'])
            ->latest('id')
            ->get();
    }

    public function openShift(User $user, Outlet $outlet, int $startingCash, ?string $notes = null): CashierShift
    {
        if ($startingCash < 0) {
            throw ValidationException::withMessages([
                'starting_cash' => 'Modal awal tidak boleh bernilai negatif.',
            ]);
        }

        $existing = $this->getCurrentOpenShift($user, $outlet);
        if ($existing instanceof CashierShift) {
            throw ValidationException::withMessages([
                'shift' => 'Anda masih memiliki shift aktif di outlet ini. Tutup shift sebelumnya terlebih dahulu.',
            ]);
        }

        return DB::transaction(function () use ($user, $outlet, $startingCash, $notes): CashierShift {
            $shift = CashierShift::query()->create([
                'restaurant_id' => $outlet->restaurant_id,
                'outlet_id' => $outlet->id,
                'user_id' => $user->id,
                'status' => CashierShift::STATUS_OPEN,
                'opened_at' => now(),
                'starting_cash' => $startingCash,
                'cash_sales' => 0,
                'non_cash_sales' => 0,
                'cash_in' => 0,
                'cash_out' => 0,
                'notes' => $notes,
            ]);

            ActivityLogger::log('cashier_shift.open', [
                'restaurant_id' => $shift->restaurant_id,
                'outlet_id' => $shift->outlet_id,
                'user_id' => $user->id,
                'new' => [
                    'shift_id' => $shift->id,
                    'starting_cash' => $startingCash,
                    'opened_at' => $shift->opened_at->toIso8601String(),
                ],
            ]);

            return $shift;
        });
    }

    public function recordCashMovement(
        CashierShift $shift,
        User $user,
        string $type,
        int $amount,
        string $category = 'lainnya',
        ?string $notes = null
    ): CashierShiftMovement {
        if (! $shift->isOpen()) {
            throw ValidationException::withMessages([
                'shift' => 'Shift sudah ditutup. Tidak bisa menambah mutasi kas.',
            ]);
        }

        if (! in_array($type, [CashierShiftMovement::TYPE_CASH_IN, CashierShiftMovement::TYPE_CASH_OUT], true)) {
            throw ValidationException::withMessages([
                'type' => 'Tipe mutasi kas harus cash_in atau cash_out.',
            ]);
        }

        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'Nominal mutasi kas harus lebih dari 0.',
            ]);
        }

        return DB::transaction(function () use ($shift, $user, $type, $amount, $category, $notes): CashierShiftMovement {
            $locked = CashierShift::query()->whereKey($shift->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isOpen()) {
                throw ValidationException::withMessages([
                    'shift' => 'Shift sudah ditutup.',
                ]);
            }

            $movement = CashierShiftMovement::query()->create([
                'cashier_shift_id' => $locked->id,
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'category' => $category,
                'notes' => $notes,
            ]);

            if ($type === CashierShiftMovement::TYPE_CASH_IN) {
                $locked->increment('cash_in', $amount);
            } else {
                $locked->increment('cash_out', $amount);
            }

            ActivityLogger::log('cashier_shift.movement', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'user_id' => $user->id,
                'new' => [
                    'shift_id' => $locked->id,
                    'movement_id' => $movement->id,
                    'type' => $type,
                    'amount' => $amount,
                    'category' => $category,
                ],
            ]);

            $shift->refresh();

            return $movement;
        });
    }

    public function recordPayment(Payment $payment, ?User $cashier = null): void
    {
        if ($payment->status !== 'paid') {
            return;
        }

        $cashierUser = $cashier ?? $payment->paidByUser;
        if (! $cashierUser instanceof User) {
            return;
        }

        $shift = $payment->cashierShift;
        if (! $shift instanceof CashierShift) {
            $shift = $this->getCurrentOpenShift($cashierUser, $payment->outlet_id);
            if (! $shift instanceof CashierShift) {
                return;
            }

            $payment->forceFill(['cashier_shift_id' => $shift->id])->save();

            if ($payment->order instanceof Order && blank($payment->order->cashier_shift_id)) {
                $payment->order->forceFill(['cashier_shift_id' => $shift->id])->save();
            }
        }

        if (! $shift->isOpen()) {
            return;
        }

        $amount = (int) $payment->amount;

        if ($payment->method === 'cash') {
            $shift->increment('cash_sales', $amount);
        } else {
            $shift->increment('non_cash_sales', $amount);
        }
    }

    public function recordVoid(Order $order, User $user, ?string $reason = null): void
    {
        $order->loadMissing(['payments', 'outlet']);
        $cashPaid = (int) $order->payments()
            ->where('status', 'paid')
            ->where('method', 'cash')
            ->sum('amount');

        if ($cashPaid <= 0) {
            return;
        }

        $shift = $order->cashierShift ?? $this->getCurrentOpenShift($user, $order->outlet_id);
        if (! $shift instanceof CashierShift || ! $shift->isOpen()) {
            return;
        }

        $this->recordCashMovement(
            shift: $shift,
            user: $user,
            type: CashierShiftMovement::TYPE_CASH_OUT,
            amount: $cashPaid,
            category: 'retur_void',
            notes: 'Void pesanan #'.$order->number.($reason ? ': '.$reason : '')
        );
    }

    /**
     * @return array{starting_cash: int, cash_sales: int, non_cash_sales: int, cash_in: int, cash_out: int, expected_cash: int, total_sales: int, points_redeemed: int, points_discount_amount: int, gross_sales: int}
     */
    public function calculateExpectedCash(CashierShift $shift): array
    {
        $startingCash = (int) $shift->starting_cash;
        $cashSales = (int) $shift->cash_sales;
        $nonCashSales = (int) $shift->non_cash_sales;
        $cashIn = (int) $shift->cash_in;
        $cashOut = (int) $shift->cash_out;

        $expectedCash = $startingCash + $cashSales + $cashIn - $cashOut;
        $totalSales = $cashSales + $nonCashSales;

        $pointsRedeemed = (int) $shift->orders()
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_IN_PRODUCTION, Order::STATUS_COMPLETED])
            ->sum('points_redeemed');

        $pointsDiscountAmount = (int) $shift->orders()
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_IN_PRODUCTION, Order::STATUS_COMPLETED])
            ->sum('discount_amount');

        $grossSales = $totalSales + $pointsDiscountAmount;

        return [
            'starting_cash' => $startingCash,
            'cash_sales' => $cashSales,
            'non_cash_sales' => $nonCashSales,
            'cash_in' => $cashIn,
            'cash_out' => $cashOut,
            'expected_cash' => $expectedCash,
            'total_sales' => $totalSales,
            'points_redeemed' => $pointsRedeemed,
            'points_discount_amount' => $pointsDiscountAmount,
            'gross_sales' => $grossSales,
        ];
    }

    public function closeShift(
        CashierShift $shift,
        User $closedBy,
        int $actualEndingCash,
        ?string $differenceReason = null,
        ?string $notes = null
    ): CashierShift {
        if (! $shift->isOpen()) {
            throw ValidationException::withMessages([
                'shift' => 'Shift ini sudah ditutup sebelumnya.',
            ]);
        }

        if ($actualEndingCash < 0) {
            throw ValidationException::withMessages([
                'actual_ending_cash' => 'Uang fisik di laci tidak boleh bernilai negatif.',
            ]);
        }

        return DB::transaction(function () use ($shift, $closedBy, $actualEndingCash, $differenceReason, $notes): CashierShift {
            $locked = CashierShift::query()->whereKey($shift->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isOpen()) {
                throw ValidationException::withMessages([
                    'shift' => 'Shift ini sudah ditutup sebelumnya.',
                ]);
            }

            $calc = $this->calculateExpectedCash($locked);
            $expectedCash = $calc['expected_cash'];
            $difference = $actualEndingCash - $expectedCash;

            $locked->forceFill([
                'status' => CashierShift::STATUS_CLOSED,
                'closed_at' => now(),
                'expected_ending_cash' => $expectedCash,
                'actual_ending_cash' => $actualEndingCash,
                'cash_difference' => $difference,
                'difference_reason' => $differenceReason,
                'notes' => $notes ?? $locked->notes,
                'closed_by_user_id' => $closedBy->id,
            ])->save();

            ActivityLogger::log('cashier_shift.close', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'user_id' => $closedBy->id,
                'old' => ['status' => CashierShift::STATUS_OPEN],
                'new' => [
                    'shift_id' => $locked->id,
                    'status' => CashierShift::STATUS_CLOSED,
                    'expected_ending_cash' => $expectedCash,
                    'actual_ending_cash' => $actualEndingCash,
                    'cash_difference' => $difference,
                    'closed_at' => $locked->closed_at->toIso8601String(),
                ],
            ]);

            $shift->setRawAttributes($locked->getAttributes());
            $shift->syncOriginal();

            return $shift;
        });
    }

    public function streamPdf(CashierShift $shift): Response
    {
        $shift->loadMissing([
            'user',
            'closedByUser',
            'restaurant',
            'outlet',
            'movements.user',
        ]);

        $calc = $this->calculateExpectedCash($shift);

        $pdf = Pdf::loadView('receipts.shift-report', [
            'shift' => $shift,
            'calc' => $calc,
            'logoDataUri' => ReceiptLogo::dataUri($shift->restaurant),
        ])->setPaper([0, 0, 226.77, 1200], 'portrait');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="rekap-shift-'.$shift->id.'.pdf"',
        ]);
    }
}
