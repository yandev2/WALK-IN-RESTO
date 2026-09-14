<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerLoyaltyPoint;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\WhatsAppNumber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomerCrmService
{
    public function recordOrderLoyalty(Order $order): ?Customer
    {
        $order->loadMissing(['restaurant', 'visit']);

        $restaurant = $order->restaurant;
        if (! $restaurant instanceof Restaurant) {
            return null;
        }

        $rawPhone = $order->receipt_wa_snapshot ?: $order->visit?->customer_wa;
        if (blank($rawPhone)) {
            return null;
        }

        $phone = WhatsAppNumber::normalize($rawPhone);
        if (! is_string($phone) || ! WhatsAppNumber::isValid($phone)) {
            return null;
        }

        // Idempotency guard: cegah double-counting jika poin belanja pesanan ini sudah tercatat
        $alreadyProcessed = CustomerLoyaltyPoint::withoutRestaurantScope()
            ->where('restaurant_id', $restaurant->id)
            ->where('order_id', $order->id)
            ->where('type', 'earn')
            ->exists();

        if ($alreadyProcessed) {
            return Customer::withoutRestaurantScope()
                ->where('restaurant_id', $restaurant->id)
                ->where('phone', $phone)
                ->first();
        }

        return DB::transaction(function () use ($order, $restaurant, $phone): Customer {
            $customer = Customer::withoutRestaurantScope()
                ->where('restaurant_id', $restaurant->id)
                ->where('phone', $phone)
                ->lockForUpdate()
                ->first();

            $customerName = $order->visit?->customer_name;

            if (! $customer instanceof Customer) {
                $customer = Customer::withoutRestaurantScope()->create([
                    'restaurant_id' => $restaurant->id,
                    'phone' => $phone,
                    'name' => $customerName,
                    'tier' => 'reguler',
                    'points_balance' => 0,
                    'total_spent' => 0,
                    'total_orders' => 0,
                    'last_visit_at' => now(),
                ]);
            } else {
                if (blank($customer->name) && filled($customerName)) {
                    $customer->name = $customerName;
                }
                $customer->last_visit_at = now();
            }

            // Selalu catat statistik CRM belanja & frekuensi pesanan
            $customer->total_spent = (int) $customer->total_spent + (int) $order->grand_payable;
            $customer->total_orders = (int) $customer->total_orders + 1;

            $settings = $restaurant->loyaltySettings();
            $earnedPoints = 0;

            if ($settings['enabled']) {
                $spendPerPoint = max(1, (int) $settings['spend_per_point']);
                $pointsEarnedMultiplier = max(1, (int) $settings['points_earned']);
                $earnedPoints = (int) floor((int) $order->grand_payable / $spendPerPoint) * $pointsEarnedMultiplier;

                if ($earnedPoints > 0) {
                    $customer->points_balance = (int) $customer->points_balance + $earnedPoints;
                }

                // Evaluasi otomatis kenaikan Tier
                $customer->tier = $this->resolveTier((int) $customer->total_spent, $settings);
            }

            CustomerLoyaltyPoint::withoutRestaurantScope()->create([
                'restaurant_id' => $restaurant->id,
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'type' => 'earn',
                'points' => $earnedPoints,
                'balance_after' => $customer->points_balance,
                'description' => $earnedPoints > 0
                    ? 'Poin belanja pesanan #'.$order->number
                    : 'Pencatatan pesanan #'.$order->number,
            ]);

            $customer->save();

            return $customer;
        });
    }

    /**
     * @param  array{silver_min_spent: int, gold_min_spent: int, vip_min_spent: int}  $settings
     */
    public function resolveTier(int $totalSpent, array $settings): string
    {
        if ($totalSpent >= $settings['vip_min_spent']) {
            return 'vip';
        }

        if ($totalSpent >= $settings['gold_min_spent']) {
            return 'gold';
        }

        if ($totalSpent >= $settings['silver_min_spent']) {
            return 'silver';
        }

        return 'reguler';
    }

    public function adjustPoints(Customer $customer, int $points, string $reason, ?User $user = null): Customer
    {
        return DB::transaction(function () use ($customer, $points, $reason, $user): Customer {
            $locked = Customer::withoutRestaurantScope()
                ->whereKey($customer->id)
                ->lockForUpdate()
                ->firstOrFail();

            $newBalance = max(0, (int) $locked->points_balance + $points);
            $delta = $newBalance - (int) $locked->points_balance;

            $locked->points_balance = $newBalance;
            $locked->save();

            if ($delta !== 0) {
                CustomerLoyaltyPoint::withoutRestaurantScope()->create([
                    'restaurant_id' => $locked->restaurant_id,
                    'customer_id' => $locked->id,
                    'order_id' => null,
                    'type' => 'adjustment',
                    'points' => $delta,
                    'balance_after' => $newBalance,
                    'description' => $reason,
                    'created_by_user_id' => $user?->id,
                ]);
            }

            return $locked;
        });
    }

    /**
     * @return array{
     *     allowed: bool,
     *     points: int,
     *     discount_amount: int,
     *     reason: string|null
     * }
     */
    public function calculateRedemption(
        ?Customer $customer,
        int $subtotal,
        int $pointsRequested,
        ?Restaurant $restaurant = null,
    ): array {
        if ($pointsRequested <= 0 || ! $customer instanceof Customer) {
            return [
                'allowed' => false,
                'points' => 0,
                'discount_amount' => 0,
                'reason' => null,
            ];
        }

        $restaurant = $restaurant ?? $customer->restaurant;
        if (! $restaurant instanceof Restaurant) {
            return [
                'allowed' => false,
                'points' => 0,
                'discount_amount' => 0,
                'reason' => 'Restoran tidak ditemukan.',
            ];
        }

        $settings = $restaurant->loyaltySettings();
        if (! ($settings['enabled'] ?? true)) {
            return [
                'allowed' => false,
                'points' => 0,
                'discount_amount' => 0,
                'reason' => 'Program poin loyalitas sedang dinonaktifkan.',
            ];
        }

        $rate = (int) ($settings['point_redemption_rate'] ?? 1000);
        $minPoints = (int) ($settings['min_redeem_points'] ?? 10);
        $maxPct = (int) ($settings['max_redeem_percentage'] ?? 50);

        if ($customer->points_balance < $minPoints) {
            return [
                'allowed' => false,
                'points' => 0,
                'discount_amount' => 0,
                'reason' => 'Minimal poin untuk penukaran adalah '.$minPoints.' poin (Saldo: '.$customer->points_balance.').',
            ];
        }

        // Batas maksimal potongan berdasarkan persentase subtotal
        $maxDiscount = (int) floor($subtotal * ($maxPct / 100));
        if ($maxDiscount <= 0) {
            return [
                'allowed' => false,
                'points' => 0,
                'discount_amount' => 0,
                'reason' => 'Subtotal pesanan tidak mencukupi untuk diskon poin.',
            ];
        }

        $maxPointsForDiscount = (int) floor($maxDiscount / $rate);
        $pointsToRedeem = min($pointsRequested, (int) $customer->points_balance, $maxPointsForDiscount);

        if ($pointsToRedeem < $minPoints) {
            return [
                'allowed' => false,
                'points' => 0,
                'discount_amount' => 0,
                'reason' => 'Poin yang dapat ditukarkan ('.$pointsToRedeem.') kurang dari batas minimal ('.$minPoints.').',
            ];
        }

        $discountAmount = $pointsToRedeem * $rate;

        return [
            'allowed' => true,
            'points' => $pointsToRedeem,
            'discount_amount' => $discountAmount,
            'reason' => null,
        ];
    }

    public function redeemPointsForOrder(Customer $customer, Order $order, int $points, int $discountAmount): CustomerLoyaltyPoint
    {
        return DB::transaction(function () use ($customer, $order, $points, $discountAmount): CustomerLoyaltyPoint {
            $locked = Customer::withoutRestaurantScope()
                ->whereKey($customer->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->points_balance < $points) {
                throw new \InvalidArgumentException('Saldo poin tidak mencukupi untuk penukaran.');
            }

            $locked->points_balance = max(0, (int) $locked->points_balance - $points);
            $locked->save();

            return CustomerLoyaltyPoint::withoutRestaurantScope()->create([
                'restaurant_id' => $locked->restaurant_id,
                'customer_id' => $locked->id,
                'order_id' => $order->id,
                'type' => 'redeem',
                'points' => -$points,
                'balance_after' => $locked->points_balance,
                'description' => 'Penukaran '.$points.' poin (Diskon Rp '.number_format($discountAmount, 0, ',', '.').') pesanan #'.$order->number,
            ]);
        });
    }

    public function refundPointsForOrder(Order $order): ?CustomerLoyaltyPoint
    {
        $pointsRedeemed = (int) $order->points_redeemed;
        if ($pointsRedeemed <= 0) {
            return null;
        }

        $order->loadMissing(['restaurant', 'visit']);
        $restaurant = $order->restaurant;
        if (! $restaurant instanceof Restaurant) {
            return null;
        }

        // Idempotency: cek jika sudah pernah di-refund
        $alreadyRefunded = CustomerLoyaltyPoint::withoutRestaurantScope()
            ->where('restaurant_id', $restaurant->id)
            ->where('order_id', $order->id)
            ->where('type', 'refund')
            ->exists();

        if ($alreadyRefunded) {
            return null;
        }

        $rawPhone = $order->receipt_wa_snapshot ?: $order->visit?->customer_wa;
        if (blank($rawPhone)) {
            return null;
        }

        $phone = WhatsAppNumber::normalize($rawPhone);
        if (! is_string($phone) || ! WhatsAppNumber::isValid($phone)) {
            return null;
        }

        return DB::transaction(function () use ($order, $restaurant, $phone, $pointsRedeemed): ?CustomerLoyaltyPoint {
            $customer = Customer::withoutRestaurantScope()
                ->where('restaurant_id', $restaurant->id)
                ->where('phone', $phone)
                ->lockForUpdate()
                ->first();

            if (! $customer instanceof Customer) {
                return null;
            }

            $customer->points_balance = (int) $customer->points_balance + $pointsRedeemed;
            $customer->save();

            return CustomerLoyaltyPoint::withoutRestaurantScope()->create([
                'restaurant_id' => $restaurant->id,
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'type' => 'refund',
                'points' => $pointsRedeemed,
                'balance_after' => $customer->points_balance,
                'description' => 'Pengembalian '.$pointsRedeemed.' poin (pesanan dibatalkan #'.$order->number.')',
            ]);
        });
    }

    /**
     * @return array{
     *     total_customers: int,
     *     repeat_customers: int,
     *     one_time_customers: int,
     *     repeat_rate: float,
     *     total_ltv: int,
     *     average_ltv: int,
     *     total_orders_count: int,
     *     average_order_value: int,
     *     avg_orders_per_customer: float,
     *     total_points: int,
     *     active_30d_customers: int,
     *     dormant_customers: int,
     *     new_this_month: int,
     *     tier_counts: array{reguler: int, silver: int, gold: int, vip: int},
     *     top_frequent: Collection<int, Customer>,
     *     top_spenders: Collection<int, Customer>
     * }
     */
    public function getAnalytics(Restaurant $restaurant): array
    {
        $base = Customer::withoutRestaurantScope()
            ->where('restaurant_id', $restaurant->id);

        $totalCustomers = (clone $base)->count();
        $repeatCustomers = (clone $base)->where('total_orders', '>', 1)->count();
        $oneTimeCustomers = max(0, $totalCustomers - $repeatCustomers);
        $repeatRate = $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0.0;
        $totalLtv = (int) (clone $base)->sum('total_spent');
        $averageLtv = $totalCustomers > 0 ? (int) round($totalLtv / $totalCustomers) : 0;
        $totalOrdersCount = (int) (clone $base)->sum('total_orders');
        $averageOrderValue = $totalOrdersCount > 0 ? (int) round($totalLtv / $totalOrdersCount) : 0;
        $avgOrdersPerCustomer = $totalCustomers > 0 ? round($totalOrdersCount / $totalCustomers, 1) : 0.0;
        $totalPoints = (int) (clone $base)->sum('points_balance');

        $active30dCustomers = (clone $base)->where('last_visit_at', '>=', now()->subDays(30))->count();
        $dormantCustomers = (clone $base)->where(function ($q) {
            $q->where('last_visit_at', '<', now()->subDays(30))
                ->orWhere(function ($sub) {
                    $sub->whereNull('last_visit_at')
                        ->where('created_at', '<', now()->subDays(30));
                });
        })->count();
        $newThisMonth = (clone $base)->where('created_at', '>=', now()->startOfMonth())->count();

        $tierCountsRaw = (clone $base)
            ->selectRaw('LOWER(tier) as tier_key, count(*) as total')
            ->groupBy('tier_key')
            ->pluck('total', 'tier_key')
            ->toArray();

        $tierCounts = [
            'reguler' => (int) ($tierCountsRaw['reguler'] ?? 0),
            'silver' => (int) ($tierCountsRaw['silver'] ?? 0),
            'gold' => (int) ($tierCountsRaw['gold'] ?? 0),
            'vip' => (int) ($tierCountsRaw['vip'] ?? 0),
        ];

        $topFrequent = (clone $base)
            ->orderByDesc('total_orders')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        $topSpenders = (clone $base)
            ->orderByDesc('total_spent')
            ->orderByDesc('total_orders')
            ->limit(10)
            ->get();

        return [
            'total_customers' => $totalCustomers,
            'repeat_customers' => $repeatCustomers,
            'one_time_customers' => $oneTimeCustomers,
            'repeat_rate' => $repeatRate,
            'total_ltv' => $totalLtv,
            'average_ltv' => $averageLtv,
            'total_orders_count' => $totalOrdersCount,
            'average_order_value' => $averageOrderValue,
            'avg_orders_per_customer' => $avgOrdersPerCustomer,
            'total_points' => $totalPoints,
            'active_30d_customers' => $active30dCustomers,
            'dormant_customers' => $dormantCustomers,
            'new_this_month' => $newThisMonth,
            'tier_counts' => $tierCounts,
            'top_frequent' => $topFrequent,
            'top_spenders' => $topSpenders,
        ];
    }
}
