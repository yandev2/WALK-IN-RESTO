<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\User;
use App\Models\Visit;
use App\Support\ActivityLogger;
use App\Support\TableQrToken;
use App\Support\WhatsAppNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TableOpsService
{
    public function resetPin(Visit $visit, User $user): string
    {
        if ($visit->status !== 'open') {
            throw ValidationException::withMessages([
                'visit' => 'Hanya visit terbuka yang bisa direset PIN-nya.',
            ]);
        }

        $oldFails = $visit->pin_fail_count;
        $hadLock = filled($visit->join_locked_until);
        $pin = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $visit->forceFill([
            'join_pin' => $pin,
            'pin_fail_count' => 0,
            'join_locked_until' => null,
        ])->save();

        ActivityLogger::log('visit.reset_pin', [
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'user_id' => $user->id,
            'old' => ['pin_fail_count' => $oldFails, 'had_lock' => $hadLock],
            'new' => ['pin_fail_count' => 0, 'had_lock' => false],
        ]);

        return $pin;
    }

    public function updateCustomerWa(Visit $visit, User $user, string $customerWa): void
    {
        if ($visit->status !== 'open') {
            throw ValidationException::withMessages([
                'visit' => 'Hanya visit terbuka yang nomor WA-nya bisa diubah.',
            ]);
        }

        $wa = WhatsAppNumber::normalize($customerWa);

        if (! is_string($wa) || ! WhatsAppNumber::isValid($wa)) {
            throw ValidationException::withMessages([
                'customer_wa' => 'Nomor WhatsApp wajib. Gunakan 08… atau 62…',
            ]);
        }

        $old = $visit->customer_wa;
        $visit->forceFill(['customer_wa' => $wa])->save();

        ActivityLogger::log('visit.update_wa', [
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'user_id' => $user->id,
            'old' => ['customer_wa' => $old],
            'new' => ['customer_wa' => $wa],
        ]);
    }

    public function regenerateQr(DiningTable $table, User $user): DiningTable
    {
        $oldVersion = (int) $table->qr_version;

        $table->forceFill([
            'qr_version' => $oldVersion + 1,
            'qr_secret' => Str::random(64),
        ])->save();

        ActivityLogger::log('table.regenerate_qr', [
            'restaurant_id' => $table->restaurant_id,
            'outlet_id' => $table->outlet_id,
            'user_id' => $user->id,
            'old' => ['table_id' => $table->id, 'qr_version' => $oldVersion],
            'new' => ['table_id' => $table->id, 'qr_version' => $table->qr_version],
        ]);

        return $table;
    }

    public function moveVisit(Visit $visit, DiningTable $destination, User $user): void
    {
        if ($visit->status !== 'open') {
            throw ValidationException::withMessages([
                'visit' => 'Hanya visit terbuka yang bisa dipindah.',
            ]);
        }

        if ((int) $visit->table_id === (int) $destination->id) {
            throw ValidationException::withMessages([
                'table' => 'Meja tujuan sama dengan meja sekarang.',
            ]);
        }

        if ((int) $visit->outlet_id !== (int) $destination->outlet_id) {
            throw ValidationException::withMessages([
                'table' => 'Meja tujuan harus di outlet yang sama.',
            ]);
        }

        DB::transaction(function () use ($visit, $destination, $user): void {
            $ids = collect([$visit->table_id, $destination->id])->sort()->values()->all();
            $lockedTables = DiningTable::query()
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $source = $lockedTables->get($visit->table_id);
            $dest = $lockedTables->get($destination->id);
            $lockedVisit = Visit::query()->whereKey($visit->id)->lockForUpdate()->firstOrFail();

            if (! $source instanceof DiningTable || ! $dest instanceof DiningTable) {
                throw ValidationException::withMessages(['table' => 'Meja tidak ditemukan.']);
            }

            if ((int) $source->open_visit_id !== (int) $lockedVisit->id) {
                throw ValidationException::withMessages([
                    'visit' => 'Visit ini bukan penghuni meja sumber.',
                ]);
            }

            if ($dest->is_out_of_service || $dest->needs_cleaning || filled($dest->open_visit_id)) {
                throw ValidationException::withMessages([
                    'table' => 'Meja tujuan masih terisi atau tidak tersedia.',
                ]);
            }

            $oldTableId = $source->id;

            $lockedVisit->forceFill(['table_id' => $dest->id])->save();
            $source->forceFill(['open_visit_id' => null])->save();
            $dest->forceFill([
                'open_visit_id' => $lockedVisit->id,
                'needs_cleaning' => false,
            ])->save();

            ActivityLogger::log('visit.move', [
                'restaurant_id' => $lockedVisit->restaurant_id,
                'outlet_id' => $lockedVisit->outlet_id,
                'visit_id' => $lockedVisit->id,
                'user_id' => $user->id,
                'old' => ['table_id' => $oldTableId],
                'new' => ['table_id' => $dest->id],
            ]);
        });
    }

    public function qrPng(DiningTable $table): string
    {
        return TableQrToken::png($table);
    }

    public function qrPdf(DiningTable $table): string
    {
        return TableQrToken::pdf($table);
    }
}
