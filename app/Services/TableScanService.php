<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\Visit;
use App\Models\VisitDevice;
use App\Support\GuestContext;
use App\Support\TableQrToken;
use Illuminate\Validation\ValidationException;

class TableScanService
{
    public function __construct(
        private VisitClaimService $claims,
        private StaleOperationsService $stale,
    ) {}

    /**
     * @return array{mode: string, message: string, table: ?DiningTable, visit: ?Visit}
     */
    public function inspect(string $token): array
    {
        $table = TableQrToken::resolve($token);

        if (! $table) {
            return [
                'mode' => 'invalid',
                'message' => 'Stiker tidak valid atau sudah diganti. Hubungi kasir.',
                'table' => null,
                'visit' => null,
            ];
        }

        if ($table->openVisit) {
            $this->stale->sweepVisit($table->openVisit);
            $table->refresh();
            $table->load(['outlet.restaurant', 'openVisit']);
        }

        $deviceToken = GuestContext::deviceToken();

        if ($deviceToken && filled($table->open_visit_id)) {
            $already = VisitDevice::query()
                ->where('visit_id', $table->open_visit_id)
                ->where('device_token', $deviceToken)
                ->exists();

            if ($already && $table->openVisit?->status === 'open') {
                return [
                    'mode' => 'ready',
                    'message' => 'Anda sudah di meja ini.',
                    'table' => $table,
                    'visit' => $table->openVisit,
                ];
            }
        }

        try {
            $this->claims->assertRestaurantOpen($table);
        } catch (ValidationException $e) {
            return [
                'mode' => 'blocked',
                'message' => collect($e->errors())->flatten()->first() ?: 'Restoran tutup.',
                'table' => $table,
                'visit' => null,
            ];
        }

        if ($table->is_out_of_service) {
            return [
                'mode' => 'blocked',
                'message' => 'Meja ini tidak dipakai. Silakan pilih meja lain.',
                'table' => $table,
                'visit' => null,
            ];
        }

        if ($table->needs_cleaning) {
            return [
                'mode' => 'blocked',
                'message' => 'Meja sedang dibersihkan. Tunggu kasir menandai siap.',
                'table' => $table,
                'visit' => null,
            ];
        }

        if (filled($table->open_visit_id)) {
            return [
                'mode' => 'join',
                'message' => 'Meja ini sudah ada tamu. Masukkan PIN rombongan, atau pilih meja lain.',
                'table' => $table,
                'visit' => null,
            ];
        }

        return [
            'mode' => 'claim',
            'message' => 'Duduk dulu, lalu isi WhatsApp.',
            'table' => $table,
            'visit' => null,
        ];
    }
}
