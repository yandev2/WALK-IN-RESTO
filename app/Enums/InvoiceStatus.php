<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Sent = 'sent';
    case AwaitingVerification = 'awaiting_verification';
    case Paid = 'paid';
    case Void = 'void';

    public function label(): string
    {
        return match ($this) {
            self::Sent => 'Menunggu pembayaran',
            self::AwaitingVerification => 'Menunggu verifikasi',
            self::Paid => 'Lunas',
            self::Void => 'Dibatalkan',
        };
    }

    public function isOpen(): bool
    {
        return in_array($this, [self::Sent, self::AwaitingVerification], true);
    }

    public function color(): string
    {
        return match ($this) {
            self::Sent => 'warning',
            self::AwaitingVerification => 'info',
            self::Paid => 'success',
            self::Void => 'gray',
        };
    }
}
