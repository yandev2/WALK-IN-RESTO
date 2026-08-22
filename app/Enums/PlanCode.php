<?php

namespace App\Enums;

enum PlanCode: string
{
    case LandingOnly = 'landing_only';
    case ManagementKds = 'management_kds';

    public function label(): string
    {
        return match ($this) {
            self::LandingOnly => 'Landing Page Only',
            self::ManagementKds => 'Management KDS',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
