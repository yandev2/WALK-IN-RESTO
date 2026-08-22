<?php

namespace App\Console\Commands;

use App\Services\SubscriptionLifecycleService;
use Illuminate\Console\Command;

class ProcessSubscriptionLifecycleCommand extends Command
{
    protected $signature = 'subscription:process-lifecycle';

    protected $description = 'Transisi langganan trial → grace → expired';

    public function handle(SubscriptionLifecycleService $lifecycle): int
    {
        $result = $lifecycle->process();

        $this->info("Diaktifkan ulang: {$result['activated']}. Masuk grace: {$result['grace']}. Kedaluwarsa: {$result['expired']}.");

        return self::SUCCESS;
    }
}
