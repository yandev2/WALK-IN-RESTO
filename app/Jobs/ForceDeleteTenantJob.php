<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\TenantPurgeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ForceDeleteTenantJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 300;

    public function __construct(
        public int $restaurantId,
        public string $restaurantName,
        public string $restaurantSlug,
        public ?User $initiator = null,
    ) {}

    public function handle(TenantPurgeService $purgeService): void
    {
        Log::info("Executing ForceDeleteTenantJob for tenant [ID: {$this->restaurantId}, Name: {$this->restaurantName}, Slug: {$this->restaurantSlug}].");

        $purgeService->purge(
            $this->restaurantId,
            $this->restaurantName,
            $this->restaurantSlug,
            $this->initiator,
        );
    }

    public function failed(?Throwable $exception): void
    {
        Log::error("ForceDeleteTenantJob failed for tenant [ID: {$this->restaurantId}, Name: {$this->restaurantName}]: " . ($exception ? $exception->getMessage() : 'Unknown error'));
    }
}
