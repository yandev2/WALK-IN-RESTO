<?php

namespace App\Console\Commands;

use App\Services\CashierCommissionBillingService;
use Illuminate\Console\Command;

class FinalizeCashierCommissionCommand extends Command
{
    protected $signature = 'subscription:finalize-commission';

    protected $description = 'Finalisasi omzet dan invoice komisi kasir bulanan';

    public function handle(CashierCommissionBillingService $service): int
    {
        $count = $service->finalizeMonthEndInvoices();

        $this->info("Invoice komisi difinalisasi untuk {$count} restoran.");

        return self::SUCCESS;
    }
}
