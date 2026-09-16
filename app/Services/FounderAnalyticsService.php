<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FounderAnalyticsService
{
    /**
     * Aggregated executive KPI metrics for the Founder Dashboard.
     *
     * @return array<string, mixed>
     */
    public function getKpiData(): array
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // 1. Restaurant Counts & Growth
        $totalRestaurants = Restaurant::count();
        $activeRestaurants = Restaurant::where('is_active', true)->count();
        $newRestaurantsThisMonth = Restaurant::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $newRestaurantsLastMonth = Restaurant::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $restaurantGrowthPct = $newRestaurantsLastMonth > 0
            ? round((($newRestaurantsThisMonth - $newRestaurantsLastMonth) / $newRestaurantsLastMonth) * 100, 1)
            : ($newRestaurantsThisMonth > 0 ? 100.0 : 0.0);

        // 2. Revenue (Rent / Subscription & Commission)
        $paidInvoicesThisMonth = SubscriptionInvoice::query()
            ->where('status', InvoiceStatus::Paid)
            ->where(function (Builder $q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
                    ->orWhere(function (Builder $sq) use ($startOfMonth, $endOfMonth) {
                        $sq->whereNull('paid_at')
                            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth]);
                    });
            });

        $revenueThisMonth = (int) (clone $paidInvoicesThisMonth)->sum('amount');
        $revenueFlatThisMonth = (int) (clone $paidInvoicesThisMonth)
            ->where(fn (Builder $q) => $q->where('invoice_type', InvoiceType::MonthlyFlat)->orWhereNull('invoice_type'))
            ->sum('amount');
        $revenueCommissionThisMonth = (int) (clone $paidInvoicesThisMonth)
            ->where('invoice_type', InvoiceType::CashierCommission)
            ->sum('amount');

        $revenueLastMonth = (int) SubscriptionInvoice::query()
            ->where('status', InvoiceStatus::Paid)
            ->where(function (Builder $q) use ($startOfLastMonth, $endOfLastMonth) {
                $q->whereBetween('paid_at', [$startOfLastMonth, $endOfLastMonth])
                    ->orWhere(function (Builder $sq) use ($startOfLastMonth, $endOfLastMonth) {
                        $sq->whereNull('paid_at')
                            ->whereBetween('updated_at', [$startOfLastMonth, $endOfLastMonth]);
                    });
            })
            ->sum('amount');

        $revenueGrowthPct = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100.0 : 0.0);

        // 3. Overdue / Delinquent Restaurants ("Restoran Mangkir")
        // Defined as: subscription_status is Grace or Expired OR has unpaid invoices past due_at
        $overdueRestaurantsQuery = $this->getOverdueRestaurantsQuery();
        $overdueRestaurantsCount = (int) (clone $overdueRestaurantsQuery)->count();

        $totalOverdueDebt = (int) SubscriptionInvoice::query()
            ->where('status', InvoiceStatus::Sent)
            ->where('due_at', '<', $now)
            ->sum('amount');

        $graceCount = Restaurant::where('subscription_status', SubscriptionStatus::Grace)->count();
        $expiredCount = Restaurant::where('subscription_status', SubscriptionStatus::Expired)->count();

        // 4. Invoices Pending Verification ("Menunggu Konfirmasi")
        $pendingInvoicesQuery = SubscriptionInvoice::query()->where('status', InvoiceStatus::AwaitingVerification);
        $pendingVerificationCount = (int) (clone $pendingInvoicesQuery)->count();
        $pendingVerificationAmount = (int) (clone $pendingInvoicesQuery)->sum('amount');

        return [
            'totalRestaurants' => $totalRestaurants,
            'activeRestaurants' => $activeRestaurants,
            'newRestaurantsThisMonth' => $newRestaurantsThisMonth,
            'newRestaurantsLastMonth' => $newRestaurantsLastMonth,
            'restaurantGrowthPct' => $restaurantGrowthPct,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueFlatThisMonth' => $revenueFlatThisMonth,
            'revenueCommissionThisMonth' => $revenueCommissionThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueGrowthPct' => $revenueGrowthPct,
            'overdueRestaurantsCount' => $overdueRestaurantsCount,
            'totalOverdueDebt' => $totalOverdueDebt,
            'graceCount' => $graceCount,
            'expiredCount' => $expiredCount,
            'pendingVerificationCount' => $pendingVerificationCount,
            'pendingVerificationAmount' => $pendingVerificationAmount,
        ];
    }

    /**
     * Monthly revenue and registration trends for ApexCharts.
     *
     * @return array{labels: array<int, string>, flatRevenue: array<int, int>, commissionRevenue: array<int, int>, totalRevenue: array<int, int>, registrations: array<int, int>}
     */
    public function getRevenueTrend(int $months = 6): array
    {
        $months = in_array($months, [6, 12], true) ? $months : 6;
        $now = now();
        $start = $now->copy()->subMonths($months - 1)->startOfMonth();

        $period = CarbonPeriod::create($start, '1 month', $now->copy()->endOfMonth());

        $labels = [];
        $flatRevenue = [];
        $commissionRevenue = [];
        $totalRevenue = [];
        $registrations = [];

        foreach ($period as $date) {
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $monthLabel = $date->translatedFormat('M Y');
            $labels[] = $monthLabel;

            // Invoices paid in this month
            $paidInvoices = SubscriptionInvoice::query()
                ->where('status', InvoiceStatus::Paid)
                ->where(function (Builder $q) use ($monthStart, $monthEnd) {
                    $q->whereBetween('paid_at', [$monthStart, $monthEnd])
                        ->orWhere(function (Builder $sq) use ($monthStart, $monthEnd) {
                            $sq->whereNull('paid_at')
                                ->whereBetween('updated_at', [$monthStart, $monthEnd]);
                        });
                });

            $flat = (int) (clone $paidInvoices)
                ->where(fn (Builder $q) => $q->where('invoice_type', InvoiceType::MonthlyFlat)->orWhereNull('invoice_type'))
                ->sum('amount');

            $comm = (int) (clone $paidInvoices)
                ->where('invoice_type', InvoiceType::CashierCommission)
                ->sum('amount');

            $flatRevenue[] = $flat;
            $commissionRevenue[] = $comm;
            $totalRevenue[] = $flat + $comm;

            $registrations[] = Restaurant::query()
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
        }

        return [
            'labels' => $labels,
            'flatRevenue' => $flatRevenue,
            'commissionRevenue' => $commissionRevenue,
            'totalRevenue' => $totalRevenue,
            'registrations' => $registrations,
        ];
    }

    /**
     * Subscription status and plan distribution for donut / radial charts.
     *
     * @return array{status: array<string, int>, plans: array<string, int>, planLabels: array<string, string>}
     */
    public function getSubscriptionDistribution(): array
    {
        $statusCounts = [
            'active' => Restaurant::where('subscription_status', SubscriptionStatus::Active)->count(),
            'trial' => Restaurant::where('subscription_status', SubscriptionStatus::Trial)->count(),
            'grace' => Restaurant::where('subscription_status', SubscriptionStatus::Grace)->count(),
            'expired' => Restaurant::where('subscription_status', SubscriptionStatus::Expired)->count(),
        ];

        // Plans map
        $planNames = SubscriptionPlan::query()->pluck('name', 'code')->toArray();

        $planCounts = Restaurant::query()
            ->select('plan_code', DB::raw('count(*) as count'))
            ->groupBy('plan_code')
            ->pluck('count', 'plan_code')
            ->toArray();

        return [
            'status' => $statusCounts,
            'plans' => $planCounts,
            'planLabels' => $planNames,
        ];
    }

    /**
     * Query for invoices waiting for founder verification.
     */
    public function getPendingInvoicesQuery(): Builder
    {
        return SubscriptionInvoice::query()
            ->where('status', InvoiceStatus::AwaitingVerification)
            ->with(['restaurant'])
            ->orderByDesc('payment_submitted_at')
            ->orderByDesc('created_at');
    }

    /**
     * Query for overdue/delinquent restaurants ("Resto Mangkir").
     */
    public function getOverdueRestaurantsQuery(): Builder
    {
        return Restaurant::query()
            ->where(function (Builder $query) {
                $query->whereIn('subscription_status', [SubscriptionStatus::Grace, SubscriptionStatus::Expired])
                    ->orWhereHas('subscriptionInvoices', function (Builder $iq) {
                        $iq->where('status', InvoiceStatus::Sent)
                            ->where('due_at', '<', now());
                    });
            })
            ->with([
                'subscriptionInvoices' => function ($iq) {
                    $iq->where('status', InvoiceStatus::Sent)
                        ->where('due_at', '<', now())
                        ->orderBy('due_at');
                },
                'users',
            ])
            ->withSum([
                'subscriptionInvoices as overdue_amount' => function ($iq) {
                    $iq->where('status', InvoiceStatus::Sent)
                        ->where('due_at', '<', now());
                },
            ], 'amount')
            ->withCount([
                'subscriptionInvoices as overdue_invoices_count' => function ($iq) {
                    $iq->where('status', InvoiceStatus::Sent)
                        ->where('due_at', '<', now());
                },
            ])
            ->orderByDesc('overdue_amount')
            ->orderBy('subscription_status');
    }

    /**
     * Query for latest registered restaurants.
     */
    public function getRecentTenantsQuery(): Builder
    {
        return Restaurant::query()
            ->withCount(['outlets', 'users'])
            ->latest('created_at');
    }
}
