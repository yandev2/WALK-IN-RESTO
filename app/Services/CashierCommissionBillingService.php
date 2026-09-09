<?php

namespace App\Services;

use App\Enums\InvoiceSource;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CashierCommissionBillingService
{
    public function __construct(
        private readonly DailyOmzetService $dailyOmzetService,
        private readonly SubscriptionInvoiceService $invoiceService,
        private readonly HandleNotification $notifications,
    ) {}

    public function calculateMonthNetOmzet(Restaurant $restaurant, CarbonInterface $month): int
    {
        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $carbonMonth = Carbon::parse($month)->timezone($timezone);
        $start = $carbonMonth->copy()->startOfMonth()->startOfDay();
        $end = $carbonMonth->copy()->endOfMonth()->endOfDay();

        // If restaurant trial ends in the future after this month, omzet is 0 (fully exempt)
        if ($restaurant->trial_ends_at && $restaurant->trial_ends_at->gt($end)) {
            return 0;
        }

        // If trial ended during this month, only count transactions on or after trial_ends_at
        if ($restaurant->trial_ends_at && $restaurant->trial_ends_at->between($start, $end)) {
            $start = $restaurant->trial_ends_at->copy()->timezone($timezone);
        }

        $startUtc = $start->copy()->utc();
        $endUtc = $end->copy()->utc();

        $orders = $this->dailyOmzetService
            ->paidOrdersQuery($restaurant, $startUtc, $endUtc)
            ->with('items')
            ->get();

        $netOmzet = 0;
        foreach ($orders as $order) {
            $netOmzet += $this->dailyOmzetService->netOmzet($order);
        }

        return $netOmzet;
    }

    public function syncRealtimeMonthInvoice(Restaurant $restaurant, ?CarbonInterface $month = null): ?SubscriptionInvoice
    {
        if (! $restaurant->isCommissionPlan()) {
            return null;
        }

        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $now = now()->timezone($timezone);
        $targetMonth = $month ? Carbon::parse($month)->timezone($timezone) : $now;
        $periodMonth = $targetMonth->format('Y-m');

        // Check if currently within active trial for this target month
        if ($restaurant->isTrialActive($targetMonth->copy()->endOfMonth())) {
            return null;
        }

        return DB::transaction(function () use ($restaurant, $targetMonth, $periodMonth, $timezone): SubscriptionInvoice {
            $netOmzet = $this->calculateMonthNetOmzet($restaurant, $targetMonth);
            $commissionRate = $restaurant->effectiveCommissionPercentage();
            $amount = (int) round($netOmzet * ($commissionRate / 100));

            $dueAt = $targetMonth->copy()->endOfMonth()->endOfDay();
            $periodStart = $targetMonth->copy()->startOfMonth()->startOfDay();
            $periodEnd = $targetMonth->copy()->endOfMonth()->endOfDay();

            /** @var SubscriptionInvoice|null $invoice */
            $invoice = SubscriptionInvoice::query()
                ->where('restaurant_id', $restaurant->id)
                ->where('invoice_type', InvoiceType::CashierCommission->value)
                ->where('period_month', $periodMonth)
                ->lockForUpdate()
                ->first();

            $isPastMonth = $targetMonth->format('Y-m') < now()->timezone($timezone)->format('Y-m');

            if (! $invoice) {
                // If past month and omzet is 0, auto-mark paid
                $initialStatus = ($isPastMonth && $amount === 0)
                    ? InvoiceStatus::Paid
                    : InvoiceStatus::Sent;

                return SubscriptionInvoice::query()->create([
                    'invoice_number' => $this->invoiceService->nextNumber(),
                    'restaurant_id' => $restaurant->id,
                    'plan_code' => $restaurant->plan_code,
                    'invoice_type' => InvoiceType::CashierCommission,
                    'requested_plan_code' => $restaurant->plan_code,
                    'billing_months' => 1,
                    'amount' => $amount,
                    'total_omzet' => $netOmzet,
                    'commission_percentage' => $commissionRate,
                    'status' => $initialStatus,
                    'source' => InvoiceSource::AutoRenewal,
                    'period_key' => $periodMonth,
                    'period_month' => $periodMonth,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                    'due_at' => $dueAt,
                    'paid_at' => ($initialStatus === InvoiceStatus::Paid) ? now() : null,
                ]);
            }

            // If already paid, do not change status or amount
            if ($invoice->status === InvoiceStatus::Paid) {
                return $invoice;
            }

            // If month ended and omzet is 0, auto-paid
            if ($isPastMonth && $amount === 0) {
                $invoice->forceFill([
                    'total_omzet' => 0,
                    'amount' => 0,
                    'commission_percentage' => $commissionRate,
                    'status' => InvoiceStatus::Paid,
                    'paid_at' => now(),
                ])->save();

                return $invoice->fresh();
            }

            // Realtime update open invoice
            $invoice->forceFill([
                'total_omzet' => $netOmzet,
                'commission_percentage' => $commissionRate,
                'amount' => $amount,
                'due_at' => $dueAt,
            ])->save();

            return $invoice->fresh();
        });
    }

    public function recordOrderPaidHook(Order $order): void
    {
        $restaurant = $order->relationLoaded('restaurant') ? $order->restaurant : $order->restaurant()->first();

        if ($restaurant instanceof Restaurant && $restaurant->isCommissionPlan()) {
            $this->syncRealtimeMonthInvoice($restaurant, $order->paid_at ?? now());
        }
    }

    public function sendH3Reminders(?CarbonInterface $now = null): int
    {
        $now = $now ? Carbon::parse($now) : now();
        $daysInMonth = $now->daysInMonth;
        $day = $now->day;

        // Check if within H-3 (i.e. from day (daysInMonth - 2) until daysInMonth)
        if ($day < ($daysInMonth - 2)) {
            return 0;
        }

        $periodMonth = $now->format('Y-m');
        $sentCount = 0;

        Restaurant::query()
            ->where('is_active', true)
            ->each(function (Restaurant $restaurant) use ($now, $periodMonth, &$sentCount): void {
                if (! $restaurant->isCommissionPlan() || $restaurant->isTrialActive($now)) {
                    return;
                }

                $invoice = $this->syncRealtimeMonthInvoice($restaurant, $now);

                if (! $invoice || ! $invoice->isOpen() || $invoice->amount <= 0) {
                    return;
                }

                $owners = $restaurant->users()
                    ->wherePivot('is_active', true)
                    ->get();

                $formattedAmount = 'Rp '.number_format($invoice->amount, 0, ',', '.');
                $formattedOmzet = 'Rp '.number_format($invoice->total_omzet, 0, ',', '.');
                $percent = rtrim(rtrim(number_format($invoice->commission_percentage, 2, ',', '.'), '0'), ',');

                foreach ($owners as $owner) {
                    /** @var User $owner */
                    $this->notifications->sendWebNotification(
                        $owner,
                        'Pengingat Tagihan Komisi Kasir Akhir Bulan',
                        "Estimasi tagihan komisi kasir bulan {$now->translatedFormat('F Y')} adalah {$formattedAmount} ({$percent}% dari omzet {$formattedOmzet}). Mohon selesaikan sebelum akhir bulan.",
                        url: null,
                        type: 'info',
                    );
                }

                $sentCount++;
            });

        return $sentCount;
    }

    public function finalizeMonthEndInvoices(?CarbonInterface $now = null): int
    {
        $now = $now ? Carbon::parse($now) : now();
        $finalized = 0;

        // Check previous month
        $prevMonth = $now->copy()->subMonth();

        Restaurant::query()
            ->where('is_active', true)
            ->each(function (Restaurant $restaurant) use ($prevMonth, &$finalized): void {
                if (! $restaurant->isCommissionPlan()) {
                    return;
                }

                $invoice = $this->syncRealtimeMonthInvoice($restaurant, $prevMonth);
                if ($invoice) {
                    $finalized++;
                }
            });

        return $finalized;
    }
}
