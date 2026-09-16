<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum BlogCommentStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Spam = 'spam';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Menunggu Moderasi',
            self::Approved => 'Disetujui',
            self::Rejected => 'Ditolak',
            self::Spam => 'Spam',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Spam => 'gray',
        };
    }
}
