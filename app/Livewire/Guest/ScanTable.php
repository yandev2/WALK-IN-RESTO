<?php

namespace App\Livewire\Guest;

use App\Models\Customer;
use App\Models\DiningTable;
use App\Models\VisitDevice;
use App\Services\StaleOperationsService;
use App\Services\VisitClaimService;
use App\Support\GuestContext;
use App\Support\TableQrToken;
use App\Support\WhatsAppNumber;
use Illuminate\Support\Facades\RateLimiter;
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

    public bool $waChecked = false;

    public bool $isExistingCustomer = false;

    public bool $isNameReadOnly = false;

    public string $join_pin = '';

    public string $tableCode = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->resolveState();
    }

    public function updatedCustomerWa(): void
    {
        $this->message = '';
        $this->resetErrorBag('customer_wa');
        $this->resetErrorBag('customer_name');

        $this->customer_wa = trim($this->customer_wa);
        $raw = preg_replace('/\D+/', '', $this->customer_wa);

        if (strlen((string) $raw) < 9) {
            $this->waChecked = false;
            $this->isExistingCustomer = false;
            $this->isNameReadOnly = false;
            $this->customer_name = '';

            return;
        }

        $phone = WhatsAppNumber::normalize($this->customer_wa);
        if (! is_string($phone) || ! WhatsAppNumber::isValid($phone)) {
            $this->waChecked = false;
            $this->isExistingCustomer = false;
            $this->isNameReadOnly = false;
            $this->customer_name = '';

            return;
        }

        $table = $this->table();
        if (! $table) {
            return;
        }

        $customer = Customer::withoutRestaurantScope()
            ->where('restaurant_id', $table->restaurant_id)
            ->where('phone', $phone)
            ->first();

        $this->waChecked = true;

        if ($customer && filled($customer->name)) {
            $this->isExistingCustomer = true;
            $this->customer_name = (string) $customer->name;
            $this->isNameReadOnly = true;
        } else {
            $this->isExistingCustomer = false;
            $this->isNameReadOnly = false;
            $this->customer_name = '';
        }
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

        $this->message = '';

        $phone = WhatsAppNumber::normalize($this->customer_wa);
        if (! is_string($phone) || ! WhatsAppNumber::isValid($phone)) {
            $this->addError('customer_wa', 'Nomor WhatsApp wajib diisi dengan benar (contoh: 08123456789).');

            return null;
        }

        $customer = Customer::withoutRestaurantScope()
            ->where('restaurant_id', $table->restaurant_id)
            ->where('phone', $phone)
            ->first();

        if ($customer && filled($customer->name)) {
            $nameToSave = (string) $customer->name;
            $this->customer_name = $nameToSave;
            $this->isExistingCustomer = true;
            $this->isNameReadOnly = true;
            $this->waChecked = true;
        } else {
            $trimmedName = trim($this->customer_name);
            if (mb_strlen($trimmedName) < 2) {
                $this->waChecked = true;
                $this->isExistingCustomer = false;
                $this->isNameReadOnly = false;
                $this->addError('customer_name', 'Nama wajib diisi. Masukkan nama Anda yang valid.');

                return null;
            }
            $nameToSave = $trimmedName;
        }

        $throttleKey = 'claim-table:'.$device.':'.request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->mode = 'claim';
            $this->message = 'Terlalu banyak permintaan klaim meja. Coba lagi dalam '.$seconds.' detik.';

            return null;
        }
        RateLimiter::hit($throttleKey, 60);

        try {
            $claims->claim($table, $device, $this->customer_wa, $nameToSave, (string) request()->userAgent());
            RateLimiter::clear($throttleKey);
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

        $throttleKey = 'join-table:'.$device.':'.request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->message = 'Terlalu banyak percobaan memasukkan PIN. Coba lagi dalam '.$seconds.' detik.';

            return null;
        }
        RateLimiter::hit($throttleKey, 60);

        try {
            $claims->join($table, $device, $this->join_pin, (string) request()->userAgent());
            RateLimiter::clear($throttleKey);
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
            $table->loadMissing('outlet');
            if ($table->outlet?->simple_mode) {
                $table->update(['needs_cleaning' => false]);
            } else {
                $this->mode = 'blocked';
                $this->message = 'Meja sedang dibersihkan. Tunggu kasir menandai siap.';

                return;
            }
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
