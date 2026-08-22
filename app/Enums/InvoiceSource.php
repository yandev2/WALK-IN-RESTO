<?php

namespace App\Enums;

enum InvoiceSource: string
{
    case AutoRenewal = 'auto_renewal';
    case Manual = 'manual';
}
