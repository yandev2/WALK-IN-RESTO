<?php

namespace App\Enums;

enum InvoiceType: string
{
    case MonthlyFlat = 'monthly_flat';
    case CashierCommission = 'cashier_commission';

    public function label(): string
    {
        return match ($this) {
            self::MonthlyFlat => 'Langganan Flat Bulanan',
            self::CashierCommission => 'Komisi Kasir Bulanan',
        };
    }
}
