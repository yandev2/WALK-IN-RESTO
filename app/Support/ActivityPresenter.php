<?php

namespace App\Support;

use App\Models\Activity;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ActivityPresenter
{
    public static function eventLabel(?string $event): string
    {
        return match ($event) {
            'created' => 'Dibuat',
            'updated' => 'Diubah',
            'deleted' => 'Dihapus',
            'order.create' => 'Buat order',
            'order.approve_payment' => 'Setujui bayar',
            'order.reject_payment' => 'Tolak bayar',
            'order.expire_awaiting' => 'Expire menunggu',
            'order.void' => 'Void order',
            'order.void_item' => 'Void item',
            'visit.open_cashier' => 'Buka visit kasir',
            'visit.move' => 'Pindah meja',
            'visit.close' => 'Tutup visit',
            'visit.expire_claim' => 'Expire klaim',
            'visit.reset_pin' => 'Reset PIN',
            'visit.update_wa' => 'Update WA',
            'table.regenerate_qr' => 'Regenerate QR',
            'table.mark_ready' => 'Meja siap',
            'table.delete' => 'Hapus meja',
            'table.move_layout' => 'Geser layout',
            'menu.update_price' => 'Ubah harga',
            'outlet.override_open_closed' => 'Override buka/tutup',
            'cms.update_profile' => 'Update profil',
            'cms.update_landing_layout' => 'Update layout',
            'kds.update_status' => 'Status KDS',
            'receipt.resend' => 'Kirim ulang struk',
            'receipt.print' => 'Cetak struk',
            default => Str::headline($event ?? 'Aktivitas'),
        };
    }

    public static function eventColor(?string $event): string
    {
        $event = (string) $event;

        if ($event === 'created' || str_contains($event, 'approve') || str_ends_with($event, '.create')) {
            return 'success';
        }

        if ($event === 'deleted' || str_contains($event, 'void') || str_contains($event, 'reject')) {
            return 'danger';
        }

        if ($event === 'updated' || str_starts_with($event, 'cms.') || str_starts_with($event, 'menu.')) {
            return 'info';
        }

        return 'gray';
    }

    public static function causerName(Activity $activity): string
    {
        $name = $activity->causer?->name;

        return filled($name) ? $name : 'Sistem';
    }

    public static function subjectLabel(Activity $activity): string
    {
        if (! $activity->subject_type) {
            return '-';
        }

        $shortType = class_basename($activity->subject_type);
        $id = $activity->subject_id;

        return $id ? "{$shortType} #{$id}" : $shortType;
    }

    public static function subjectTypeLabel(?string $subjectType): string
    {
        if (! $subjectType) {
            return '-';
        }

        return Str::headline(class_basename($subjectType));
    }

    public static function summary(Activity $activity): string
    {
        $actor = static::causerName($activity);
        $subject = static::subjectTypeLabel($activity->subject_type);
        $action = Str::lower(static::eventLabel($activity->event));
        $description = trim((string) $activity->description);

        if ($description !== '' && $description !== (string) $activity->event) {
            return "{$actor}: {$description} ({$subject}).";
        }

        return "{$actor} melakukan aksi {$action} pada data {$subject}.";
    }

    /**
     * @return array{old: array<string, mixed>, new: array<string, mixed>}
     */
    public static function resolvePropertyBuckets(Activity $activity): array
    {
        $attributeChanges = static::normalizeArray($activity->attribute_changes);
        $properties = static::normalizeArray($activity->properties);

        $old = $attributeChanges['old'] ?? $properties['old'] ?? [];
        $new = $attributeChanges['attributes'] ?? $properties['attributes'] ?? [];

        return [
            'old' => static::flattenForDisplay(is_array($old) ? $old : []),
            'new' => static::flattenForDisplay(is_array($new) ? $new : []),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function normalizeArray(mixed $value): array
    {
        if ($value instanceof Collection) {
            return $value->toArray();
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function flattenForDisplay(array $data): array
    {
        $flat = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $flat[(string) $key] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                continue;
            }

            if (is_bool($value)) {
                $flat[(string) $key] = $value ? 'Ya' : 'Tidak';

                continue;
            }

            if ($value === null || $value === '') {
                $flat[(string) $key] = '-';

                continue;
            }

            $flat[(string) $key] = (string) $value;
        }

        return $flat;
    }
}
