<?php

namespace App\Console\Commands;

use App\Services\CashierCommissionBillingService;
use Illuminate\Console\Command;

class SendCashierCommissionReminderCommand extends Command
{
    protected $signature = 'subscription:remind-commission';

    protected $description = 'Kirim notifikasi pengingat H-3 akhir bulan untuk tagihan komisi kasir';

    public function handle(CashierCommissionBillingService $service): int
    {
        $count = $service->sendH3Reminders();

        $this->info("Pengingat komisi terkirim ke {$count} restoran.");

        return self::SUCCESS;
    }
}
