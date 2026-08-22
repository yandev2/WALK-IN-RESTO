<?php

namespace App\Console\Commands;

use App\Services\StaleOperationsService;
use Illuminate\Console\Command;

class ExpireStaleOperationsCommand extends Command
{
    protected $signature = 'ops:expire-stale';

    protected $description = 'Batalkan antrian kasir 20 menit dan tutup visit claim 10 menit yang kedaluwarsa';

    public function handle(StaleOperationsService $operations): int
    {
        $result = $operations->sweep();

        $this->info("Antrian dibatalkan: {$result['cancelled']}. Visit ditutup: {$result['closed']}.");

        return self::SUCCESS;
    }
}
