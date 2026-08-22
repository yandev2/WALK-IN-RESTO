<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Trial = 'trial';
    case Active = 'active';
    case Grace = 'grace';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Trial => 'Uji coba',
            self::Active => 'Aktif',
            self::Grace => 'Masa tenggang',
            self::Expired => 'Berakhir',
        };
    }

    public function isPanelAccessible(): bool
    {
        return $this !== self::Expired;
    }

    public function isReadOnly(): bool
    {
        return $this === self::Grace;
    }

    public function color(): string
    {
        return match ($this) {
            self::Trial => 'info',
            self::Active => 'success',
            self::Grace => 'warning',
            self::Expired => 'danger',
        };
    }
}
