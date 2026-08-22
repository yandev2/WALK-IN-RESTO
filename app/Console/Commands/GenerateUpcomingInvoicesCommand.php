<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use App\Services\SubscriptionInvoiceService;
use App\Support\SubscriptionGate;
use Illuminate\Console\Command;

class GenerateUpcomingInvoicesCommand extends Command
{
    protected $signature = 'subscription:generate-invoices';

    protected $description = 'Terbitkan invoice otomatis H-7 sebelum langganan berakhir';

    public function handle(SubscriptionInvoiceService $invoices, SubscriptionGate $gate): int
    {
        $created = 0;

        Restaurant::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->each(function (Restaurant $restaurant) use ($invoices, $gate, &$created): void {
                if (! $gate->shouldAutoInvoice($restaurant)) {
                    return;
                }

                if ($invoices->generateUpcoming($restaurant)) {
                    $created++;
                }
            });

        $this->info("Invoice terbit: {$created}.");

        return self::SUCCESS;
    }
}
