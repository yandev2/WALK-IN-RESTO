<?php

namespace App\Services;

use App\Models\Visit;

class StaleOperationsService
{
    public function __construct(
        private OrderPaymentService $payments,
        private VisitLifecycleService $visits,
    ) {}

    /**
     * @return array{cancelled: int, closed: int}
     */
    public function sweep(): array
    {
        $cancelled = $this->payments->expireStaleAwaiting();
        $closed = $this->visits->expireStaleClaims();
        $simpleClosed = $this->visits->expireSimpleModeCompletedVisits();

        return [
            'cancelled' => $cancelled,
            'closed' => $closed + $simpleClosed,
        ];
    }

    public function sweepVisit(Visit $visit): void
    {
        $this->payments->expireStaleAwaitingForVisit($visit);
        $this->visits->expireIfNeeded($visit->fresh(['orders', 'diningTable', 'outlet']));
    }
}
