<?php

namespace App\Services;

use App\Enums\InvoiceType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CommissionReconciliationService
{
    public function __construct(
        private readonly DailyOmzetService $dailyOmzetService,
    ) {}

    /**
     * @return array{
     *     from: Carbon,
     *     to: Carbon,
     *     timezone: string,
     *     total_orders: int,
     *     gross_sales: int,
     *     discount_amount: int,
     *     points_discount_amount: int,
     *     points_redeemed: int,
     *     void_cut_amount: int,
     *     void_waste_amount: int,
     *     net_sales: int,
     *     tax_service_amount: int,
     *     is_commission_plan: bool,
     *     commission_rate: float,
     *     commission_eligible_omzet: int,
     *     commission_amount: int,
     *     net_payout: int,
     *     cash_collected: int,
     *     qris_collected: int,
     *     total_collected: int,
     *     is_trial_active_throughout: bool,
     *     trial_ends_at: Carbon|null,
     *     related_invoice: array{number: string, status: string, amount: int, due_at: string|null}|null
     * }
     */
    public function summaryForPeriod(Restaurant $restaurant, CarbonInterface $from, CarbonInterface $to): array
    {
        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $localFrom = Carbon::parse($from)->timezone($timezone)->startOfDay();
        $localTo = Carbon::parse($to)->timezone($timezone)->endOfDay();

        $startUtc = $localFrom->copy()->utc();
        $endUtc = $localTo->copy()->utc();

        $orders = $this->dailyOmzetService
            ->paidOrdersQuery($restaurant, $startUtc, $endUtc)
            ->with(['items', 'payments'])
            ->get();

        $grossSales = 0;
        $discountAmount = 0;
        $pointsDiscountAmount = 0;
        $pointsRedeemed = 0;
        $voidCutAmount = 0;
        $voidWasteAmount = 0;
        $netSales = 0;
        $cashCollected = 0;
        $qrisCollected = 0;
        $taxServiceAmount = 0;
        $commissionEligibleOmzet = 0;

        $trialEndsAt = $restaurant->trial_ends_at ? $restaurant->trial_ends_at->copy()->timezone($timezone) : null;
        $isCommissionPlan = $restaurant->isCommissionPlan();
        $commissionRate = $restaurant->effectiveCommissionPercentage();

        // Check if the entire period is within trial
        $isTrialActiveThroughout = $trialEndsAt && $trialEndsAt->gt($localTo);

        foreach ($orders as $order) {
            $subtotal = (int) $order->subtotal;
            $discount = (int) $order->discount_amount;
            $orderPointsRedeemed = (int) $order->points_redeemed;
            $netMenu = $this->dailyOmzetService->netMenuOmzet($order);
            $orderPaid = (int) ($order->grand_payable ?: $order->grand_before);

            $grossSales += $subtotal;
            $discountAmount += $discount;
            $pointsDiscountAmount += $orderPointsRedeemed > 0 ? $discount : 0;
            $pointsRedeemed += $orderPointsRedeemed;
            $netSales += $netMenu;

            $cut = (int) $order->items
                ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'cut')
                ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);
            $voidCutAmount += $cut;

            $waste = (int) $order->items
                ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'waste')
                ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);
            $voidWasteAmount += $waste;

            $taxService = (int) $order->pb1_amount + (int) $order->service_amount;
            $taxServiceAmount += $taxService;

            // Cash collections breakdown based on actual paid order
            $orderRealCollected = max(0, $orderPaid - $cut);
            if ($order->payment_method === 'cash') {
                $cashCollected += $orderRealCollected;
            } elseif ($order->payment_method === 'qris') {
                $qrisCollected += $orderRealCollected;
            }

            // Commission eligibility check per order (matches CashierCommissionBillingService trial cut-off)
            $isOrderTrialExempt = false;
            if ($trialEndsAt) {
                $paidAtLocal = $order->paid_at ? $order->paid_at->copy()->timezone($timezone) : null;
                if ($paidAtLocal && $paidAtLocal->lt($trialEndsAt)) {
                    $isOrderTrialExempt = true;
                }
            }

            if (! $isOrderTrialExempt && $netMenu > 0 && $isCommissionPlan) {
                $commissionEligibleOmzet += $netMenu;
            }
        }

        $commissionAmount = $isCommissionPlan
            ? (int) round($commissionEligibleOmzet * ($commissionRate / 100))
            : 0;

        $totalCollected = $cashCollected + $qrisCollected;
        $netPayout = max(0, $totalCollected - $commissionAmount);

        // Find related monthly commission invoice if looking at a single month
        $relatedInvoice = null;
        if ($localFrom->format('Y-m') === $localTo->format('Y-m')) {
            $periodMonth = $localFrom->format('Y-m');
            $invoice = SubscriptionInvoice::query()
                ->where('restaurant_id', $restaurant->id)
                ->where('invoice_type', InvoiceType::CashierCommission->value)
                ->where('period_month', $periodMonth)
                ->first();

            if ($invoice) {
                $relatedInvoice = [
                    'number' => $invoice->invoice_number,
                    'status' => $invoice->status instanceof \BackedEnum ? $invoice->status->value : (string) $invoice->status,
                    'amount' => (int) $invoice->amount,
                    'due_at' => $invoice->due_at?->timezone($timezone)->toDateString(),
                ];
            }
        }

        return [
            'from' => $localFrom,
            'to' => $localTo,
            'timezone' => $timezone,
            'total_orders' => $orders->whereIn('status', Order::ACCEPTED_STATUSES)->count(),
            'gross_sales' => $grossSales,
            'discount_amount' => $discountAmount,
            'points_discount_amount' => $pointsDiscountAmount,
            'points_redeemed' => $pointsRedeemed,
            'void_cut_amount' => $voidCutAmount,
            'void_waste_amount' => $voidWasteAmount,
            'net_sales' => $netSales,
            'tax_service_amount' => $taxServiceAmount,
            'is_commission_plan' => $isCommissionPlan,
            'commission_rate' => $commissionRate,
            'commission_eligible_omzet' => $commissionEligibleOmzet,
            'commission_amount' => $commissionAmount,
            'net_payout' => $netPayout,
            'cash_collected' => $cashCollected,
            'qris_collected' => $qrisCollected,
            'total_collected' => $totalCollected,
            'is_trial_active_throughout' => (bool) $isTrialActiveThroughout,
            'trial_ends_at' => $trialEndsAt,
            'related_invoice' => $relatedInvoice,
        ];
    }

    /**
     * @return array{
     *     subtotal: int,
     *     discount: int,
     *     points_redeemed: int,
     *     points_discount: int,
     *     void_cut: int,
     *     void_waste: int,
     *     net_sales: int,
     *     tax_service: int,
     *     is_exempt: bool,
     *     exempt_reason: string|null,
     *     commission_rate: float,
     *     commission_amount: int,
     *     net_resto: int
     * }
     */
    public function orderCommissionDetail(Order $order, Restaurant $restaurant): array
    {
        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $subtotal = (int) $order->subtotal;
        $discount = (int) $order->discount_amount;
        $orderPointsRedeemed = (int) $order->points_redeemed;
        $netMenu = $this->dailyOmzetService->netMenuOmzet($order);
        $orderPaid = (int) ($order->grand_payable ?: $order->grand_before);

        $voidCut = (int) $order->items
            ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'cut')
            ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);

        $voidWaste = (int) $order->items
            ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'waste')
            ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);

        $totalNetOrder = max(0, $orderPaid - $voidCut);

        $trialEndsAt = $restaurant->trial_ends_at ? $restaurant->trial_ends_at->copy()->timezone($timezone) : null;
        $paidAtLocal = $order->paid_at ? $order->paid_at->copy()->timezone($timezone) : null;

        $isTrialExempt = $trialEndsAt && $paidAtLocal && $paidAtLocal->lt($trialEndsAt);
        $isCommissionPlan = $restaurant->isCommissionPlan();
        $commissionRate = $restaurant->effectiveCommissionPercentage();

        $isExempt = false;
        $exemptReason = null;

        if (! $isCommissionPlan) {
            $isExempt = true;
            $exemptReason = 'Paket Langganan Tetap (Bebas Komisi)';
        } elseif ($isTrialExempt) {
            $isExempt = true;
            $exemptReason = 'Masa Uji Coba (Bebas Komisi)';
        } elseif ($netMenu <= 0) {
            $isExempt = true;
            $exemptReason = $order->status === Order::STATUS_VOIDED ? 'Pesanan Dibatalkan (Void Rp 0)' : 'Penjualan Rp 0';
        }

        $commissionAmount = (! $isExempt && $netMenu > 0)
            ? (int) round($netMenu * ($commissionRate / 100))
            : 0;

        $netResto = max(0, $totalNetOrder - $commissionAmount);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'points_redeemed' => $orderPointsRedeemed,
            'points_discount' => $orderPointsRedeemed > 0 ? $discount : 0,
            'void_cut' => $voidCut,
            'void_waste' => $voidWaste,
            'net_sales' => $netMenu,
            'tax_service' => (int) $order->pb1_amount + (int) $order->service_amount,
            'is_exempt' => $isExempt,
            'exempt_reason' => $exemptReason,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'net_resto' => $netResto,
        ];
    }

    /**
     * @return Builder<Order>
     */
    public function ordersQuery(Restaurant $restaurant, CarbonInterface $from, CarbonInterface $to): Builder
    {
        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $startUtc = Carbon::parse($from)->timezone($timezone)->startOfDay()->utc();
        $endUtc = Carbon::parse($to)->timezone($timezone)->endOfDay()->utc();

        return $this->dailyOmzetService
            ->paidOrdersQuery($restaurant, $startUtc, $endUtc)
            ->with(['items', 'visit.diningTable', 'payments']);
    }
}
