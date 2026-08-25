<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

final class CashTender
{
    /**
     * @return array{cash_received: int|null, change_amount: int|null}
     */
    public static function resolve(string $method, int $payable, mixed $received): array
    {
        if ($method !== 'cash') {
            return [
                'cash_received' => null,
                'change_amount' => null,
            ];
        }

        $parsed = IdrAmount::parse($received);

        if ($parsed === null) {
            return [
                'cash_received' => null,
                'change_amount' => null,
            ];
        }

        if ($parsed < $payable) {
            throw ValidationException::withMessages([
                'cash_received' => 'Uang diterima kurang dari total bayar ('.CmsMedia::formatIdr($payable).').',
            ]);
        }

        return [
            'cash_received' => $parsed,
            'change_amount' => $parsed - $payable,
        ];
    }
}
