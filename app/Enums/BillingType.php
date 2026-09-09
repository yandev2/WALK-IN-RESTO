<?php

namespace App\Enums;

enum BillingType: string
{
    case FixedMonthly = 'fixed_monthly';
    case Commission = 'commission';

    public function label(): string
    {
        return match ($this) {
            self::FixedMonthly => 'Bulanan Tetap',
            self::Commission => 'Komisi Transaksi Kasir',
        };
    }
}
