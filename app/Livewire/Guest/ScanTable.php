<?php

namespace App\Livewire\Guest;

use App\Models\DiningTable;
use App\Models\VisitDevice;
use App\Services\StaleOperationsService;
use App\Services\VisitClaimService;
use App\Support\GuestContext;
use App\Support\TableQrToken;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest-order', ['title' => 'Meja'])]
class ScanTable extends Component
{
    public string $token;

    public string $mode = 'loading';

    public string $message = '';

    public string $customer_name = '';

    public string $customer_wa = '';

    public string $join_pin = '';

    public string $tableCode = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->resolveState();
    }

    public function claim(VisitClaimService $claims): mixed
    {
        $table = $this->table();
        $device = GuestContext::deviceToken();

        if (! $table || ! $device) {
            $this->mode = 'invalid';
            $this->message = 'Stiker tidak valid. Hubungi kasir.';

            return null;
        }

        try {
            $claims->claim($table, $device, $this->customer_wa, $this->customer_name ?: null, (string) request()->userAgent());
        } catch (ValidationException $e) {
            $this->mode = filled($table->open_visit_id) ? 'join' : 'claim';
            $this->message = collect($e->errors())->flatten()->first() ?: 'Tidak bisa klaim meja.';

            return null;
        }

        return $this->redirect(route('guest.menu'), navigate: true);
    }

    public function join(VisitClaimService $claims): mixed
    {
        $this->join_pin = preg_replace('/\D+/', '', $this->join_pin) ?? '';

        $table = $this->table();
        $device = GuestContext::deviceToken();

        if (! $table || ! $device) {
            $this->mode = 'invalid';

            return null;
        }

        try {
            $claims->join($table, $device, $this->join_pin, (string) request()->userAgent());
        } catch (ValidationException $e) {
            $this->message = collect($e->errors())->flatten()->first() ?: 'PIN salah.';

            return null;
        }

        return $this->redirect(route('guest.menu'), navigate: true);
    }

    public function render()
    {
        return view('livewire.guest.scan-table');
    }

    private function resolveState(): void
    {
        $table = $this->table();

        if (! $table) {
            $this->mode = 'invalid';
            $this->message = 'Stiker tidak valid atau sudah diganti. Hubungi kasir.';

            return;
        }

        $this->tableCode = $table->code;
        $deviceToken = GuestContext::deviceToken();

        if ($table->openVisit) {
            app(StaleOperationsService::class)->sweepVisit($table->openVisit);
            $table->refresh();
        }

        if ($deviceToken && $table->open_visit_id) {
            $already = VisitDevice::query()
                ->where('visit_id', $table->open_visit_id)
                ->where('device_token', $deviceToken)
                ->exists();

            if ($already) {
                $this->redirect(route('guest.menu'), navigate: true);

                return;
            }
        }

        try {
            app(VisitClaimService::class)->assertRestaurantOpen($table);
        } catch (ValidationException $e) {
            $this->mode = 'blocked';
            $this->message = collect($e->errors())->flatten()->first() ?: 'Restoran tutup.';

            return;
        }

        if ($table->is_out_of_service) {
            $this->mode = 'blocked';
            $this->message = 'Meja ini tidak dipakai. Silakan pilih meja lain.';

            return;
        }

        if ($table->needs_cleaning) {
            $this->mode = 'blocked';
            $this->message = 'Meja sedang dibersihkan. Tunggu kasir menandai siap.';

            return;
        }

        if (filled($table->open_visit_id)) {
            $this->mode = 'join';
            $this->message = 'Meja ini sudah ada tamu. Masukkan PIN rombongan, atau pilih meja lain.';

            return;
        }

        $this->mode = 'claim';
    }

    private function table(): ?DiningTable
    {
        return TableQrToken::resolve($this->token);
    }
}
