<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitDevice;
use App\Support\ActivityLogger;
use App\Support\WhatsAppNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VisitClaimService
{
    public function __construct(private StaleOperationsService $stale) {}

    public function claim(DiningTable $table, string $deviceToken, string $customerWa, ?string $customerName, string $userAgent): Visit
    {
        $wa = WhatsAppNumber::normalize($customerWa);

        if (! WhatsAppNumber::isValid($wa)) {
            throw ValidationException::withMessages([
                'customer_wa' => 'Nomor WhatsApp wajib. Gunakan 08… atau 62…',
            ]);
        }

        return DB::transaction(function () use ($table, $deviceToken, $wa, $customerName, $userAgent) {
            $table = DiningTable::query()->whereKey($table->id)->lockForUpdate()->firstOrFail();
            $table->load(['outlet.restaurant', 'openVisit.orders']);

            $this->assertRestaurantOpen($table);

            if ($table->openVisit) {
                $this->stale->sweepVisit($table->openVisit);
                $table->refresh();
            }

            if ($table->is_out_of_service) {
                throw ValidationException::withMessages(['table' => 'Meja ini tidak dipakai. Silakan pilih meja lain.']);
            }

            if ($table->needs_cleaning) {
                throw ValidationException::withMessages(['table' => 'Meja sedang dibersihkan. Mohon tunggu kasir menandai siap.']);
            }

            if (filled($table->open_visit_id)) {
                throw ValidationException::withMessages(['table' => 'Meja baru diambil. Pilih meja lain, atau minta PIN jika satu rombongan.']);
            }

            $ttl = (int) ($table->outlet?->claim_ttl_minutes ?: 10);
            $pin = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            $visit = Visit::query()->create([
                'restaurant_id' => $table->restaurant_id,
                'outlet_id' => $table->outlet_id,
                'table_id' => $table->id,
                'status' => 'open',
                'join_pin' => $pin,
                'customer_name' => $customerName ?: null,
                'customer_wa' => $wa,
                'claimed_at' => now(),
                'claim_expires_at' => now()->addMinutes($ttl),
            ]);

            $table->update(['open_visit_id' => $visit->id]);

            $this->attachDevice($visit, $deviceToken, true, $userAgent);

            return $visit;
        });
    }

    public function join(DiningTable $table, string $deviceToken, string $pin, string $userAgent): Visit
    {
        return DB::transaction(function () use ($table, $deviceToken, $pin, $userAgent) {
            $table = DiningTable::query()->whereKey($table->id)->lockForUpdate()->firstOrFail();
            $visit = $table->openVisit;

            if (! $visit || $visit->status !== 'open') {
                throw ValidationException::withMessages(['pin' => 'Tidak ada tamu di meja ini. Klaim meja kosong dulu.']);
            }

            if ($visit->isJoinLocked()) {
                throw ValidationException::withMessages(['pin' => 'PIN terkunci sementara. Minta kasir mereset, atau coba lagi nanti.']);
            }

            if (strlen($pin) !== 4 || ! hash_equals(trim((string) $visit->join_pin), $pin)) {
                $fails = $visit->pin_fail_count + 1;
                $visit->forceFill([
                    'pin_fail_count' => $fails,
                    'join_locked_until' => $fails >= 5 ? now()->addMinutes(10) : $visit->join_locked_until,
                ])->save();

                throw ValidationException::withMessages(['pin' => 'PIN salah.']);
            }

            $this->attachDevice($visit, $deviceToken, false, $userAgent);

            $visit->forceFill(['pin_fail_count' => 0])->save();

            return $visit;
        });
    }

    public function openByCashier(DiningTable $table, User $user, ?string $customerWa, ?string $customerName = null): Visit
    {
        $wa = WhatsAppNumber::normalize($customerWa);

        if (filled($customerWa) && ! WhatsAppNumber::isValid($wa)) {
            throw ValidationException::withMessages([
                'customer_wa' => 'Nomor WhatsApp tidak valid. Gunakan 08… atau 62…',
            ]);
        }

        return DB::transaction(function () use ($table, $user, $wa, $customerName) {
            $table = DiningTable::query()->whereKey($table->id)->lockForUpdate()->firstOrFail();
            $table->load(['outlet.restaurant', 'openVisit']);

            $this->assertRestaurantOpen($table, enforceHours: false);

            if ($table->openVisit) {
                $this->stale->sweepVisit($table->openVisit);
                $table->refresh();
            }

            if ($table->is_out_of_service) {
                throw ValidationException::withMessages(['table' => 'Meja ini tidak dipakai. Silakan pilih meja lain.']);
            }

            if ($table->needs_cleaning) {
                throw ValidationException::withMessages(['table' => 'Meja sedang dibersihkan. Tandai siap dulu.']);
            }

            if (filled($table->open_visit_id)) {
                throw ValidationException::withMessages(['table' => 'Meja masih terisi. Pilih meja available.']);
            }

            $ttl = (int) ($table->outlet?->claim_ttl_minutes ?: 10);
            $pin = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            $visit = Visit::query()->create([
                'restaurant_id' => $table->restaurant_id,
                'outlet_id' => $table->outlet_id,
                'table_id' => $table->id,
                'status' => 'open',
                'join_pin' => $pin,
                'opened_by_user_id' => $user->id,
                'customer_name' => $customerName ?: null,
                'customer_wa' => $wa,
                'claimed_at' => now(),
                'claim_expires_at' => now()->addMinutes($ttl),
            ]);

            $table->update(['open_visit_id' => $visit->id]);

            ActivityLogger::log('visit.open_cashier', [
                'restaurant_id' => $visit->restaurant_id,
                'outlet_id' => $visit->outlet_id,
                'visit_id' => $visit->id,
                'user_id' => $user->id,
                'new' => ['table_id' => $table->id, 'customer_wa' => $wa],
            ]);

            return $visit;
        });
    }

    public function assertRestaurantOpen(DiningTable $table, bool $enforceHours = true): void
    {
        $outlet = $table->outlet;
        $outlet?->loadMissing(['operatingHours', 'closedDates', 'restaurant']);
        $restaurant = $outlet?->restaurant;

        if (! $restaurant?->is_active || ! $outlet?->is_active || ! $outlet?->is_open) {
            throw ValidationException::withMessages(['table' => 'Restoran sedang tutup.']);
        }

        if ($enforceHours && ! $outlet->isOpenNow()) {
            throw ValidationException::withMessages(['table' => 'Restoran sedang tutup.']);
        }
    }

    private function attachDevice(Visit $visit, string $deviceToken, bool $isHost, string $userAgent): void
    {
        $existing = VisitDevice::query()->where('device_token', $deviceToken)->first();

        if ($existing && (int) $existing->visit_id === (int) $visit->id) {
            $existing->forceFill(['last_seen_at' => now()])->save();

            return;
        }

        VisitDevice::query()->where('device_token', $deviceToken)->delete();

        VisitDevice::query()->create([
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'device_token' => $deviceToken,
            'is_host' => $isHost,
            'user_agent' => substr($userAgent, 0, 255),
            'joined_at' => now(),
            'last_seen_at' => now(),
        ]);
    }
}
