<?php

namespace App\Services;

use App\Enums\InvoiceSource;
use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\SubscriptionInvoices\SubscriptionInvoiceResource;
use App\Filament\Pages\SubscriptionStatus as SubscriptionStatusPage;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Support\CmsMedia;
use App\Support\SubscriptionGate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubscriptionInvoiceService
{
    public function __construct(
        private readonly SubscriptionGate $gate,
        private readonly SubscriptionPlanSync $planSync,
        private readonly HandleNotification $notifications,
    ) {}

    public function calculateAmount(string $planCode, int $months): int
    {
        $plan = SubscriptionPlan::query()->where('code', $planCode)->firstOrFail();

        return (int) $plan->price_monthly * max(1, min(12, $months));
    }

    public function createManual(
        Restaurant $restaurant,
        string $planCode,
        ?User $creator = null,
        ?int $months = null,
        ?string $notes = null,
    ): SubscriptionInvoice {
        return DB::transaction(function () use ($restaurant, $planCode, $creator, $months, $notes): SubscriptionInvoice {
            Restaurant::query()->whereKey($restaurant->id)->lockForUpdate()->firstOrFail();

            $hasOpen = SubscriptionInvoice::query()
                ->where('restaurant_id', $restaurant->id)
                ->whereIn('status', [InvoiceStatus::Sent->value, InvoiceStatus::AwaitingVerification->value])
                ->lockForUpdate()
                ->exists();

            if ($hasOpen) {
                throw ValidationException::withMessages([
                    'invoice' => 'Masih ada invoice yang belum lunas. Hapus atau lunasi dulu sebelum membuat yang baru.',
                ]);
            }

            $billingMonths = $months ? max(1, min(12, $months)) : null;

            return SubscriptionInvoice::query()->create([
                'invoice_number' => $this->nextNumber(),
                'restaurant_id' => $restaurant->id,
                'plan_code' => $planCode,
                'requested_plan_code' => $planCode,
                'billing_months' => $billingMonths,
                'amount' => $this->calculateAmount($planCode, $billingMonths ?? 1),
                'status' => InvoiceStatus::Sent,
                'source' => InvoiceSource::Manual,
                'due_at' => $this->gate->effectiveExpiryAt($restaurant) ?? now()->addDays(7),
                'created_by' => $creator?->id,
                'admin_notes' => $notes,
            ]);
        });
    }

    public function generateUpcoming(Restaurant $restaurant): ?SubscriptionInvoice
    {
        return DB::transaction(function () use ($restaurant): ?SubscriptionInvoice {
            /** @var Restaurant $restaurant */
            $restaurant = Restaurant::query()->whereKey($restaurant->id)->lockForUpdate()->firstOrFail();

            $expiry = $this->gate->effectiveExpiryAt($restaurant);
            $periodKey = $expiry?->toDateString();

            if (blank($periodKey)) {
                return null;
            }

            $existing = SubscriptionInvoice::query()
                ->where('restaurant_id', $restaurant->id)
                ->where('source', InvoiceSource::AutoRenewal->value)
                ->where('period_key', $periodKey)
                ->first();

            if ($existing) {
                return $existing;
            }

            if (! $this->gate->shouldAutoInvoice($restaurant)) {
                return null;
            }

            $planCode = $restaurant->plan_code ?: $restaurant->subscriptionPlan?->code;

            if (blank($planCode)) {
                return null;
            }

            return SubscriptionInvoice::query()->create([
                'invoice_number' => $this->nextNumber(),
                'restaurant_id' => $restaurant->id,
                'plan_code' => $planCode,
                'requested_plan_code' => $planCode,
                'billing_months' => null,
                'amount' => $this->calculateAmount($planCode, 1),
                'status' => InvoiceStatus::Sent,
                'source' => InvoiceSource::AutoRenewal,
                'period_key' => $periodKey,
                'due_at' => $expiry,
            ]);
        });
    }

    public function submitProof(
        SubscriptionInvoice $invoice,
        string $planCode,
        int $months,
        string $proofPath,
        ?string $paymentNotes = null,
    ): SubscriptionInvoice {
        $submitted = DB::transaction(function () use ($invoice, $planCode, $months, $proofPath, $paymentNotes): SubscriptionInvoice {
            /** @var SubscriptionInvoice $invoice */
            $invoice = SubscriptionInvoice::query()->whereKey($invoice->id)->lockForUpdate()->firstOrFail();

            if (! $invoice->isOpen()) {
                throw ValidationException::withMessages([
                    'invoice' => 'Invoice ini sudah tidak bisa dibayar.',
                ]);
            }

            $months = max(1, min(12, $months));
            $plan = SubscriptionPlan::query()
                ->where('code', $planCode)
                ->where('is_active', true)
                ->firstOrFail();

            $previousProof = $invoice->payment_proof_path;

            $invoice->forceFill([
                'requested_plan_code' => $plan->code,
                'billing_months' => $months,
                'amount' => $this->calculateAmount($plan->code, $months),
                'payment_proof_path' => $proofPath,
                'payment_submitted_at' => now(),
                'status' => InvoiceStatus::AwaitingVerification,
                'payment_notes' => $paymentNotes,
                'rejection_notes' => null,
                'verified_by' => null,
            ])->save();

            if (filled($previousProof) && $previousProof !== $proofPath) {
                $this->deleteProofFile($previousProof);
            }

            return $invoice->fresh(['restaurant']);
        });

        $this->notifyPlatformOperators($submitted);

        return $submitted;
    }

    public function approve(SubscriptionInvoice $invoice, User $verifier): SubscriptionInvoice
    {
        $becamePaid = false;

        $paid = DB::transaction(function () use ($invoice, $verifier, &$becamePaid): SubscriptionInvoice {
            /** @var SubscriptionInvoice $invoice */
            $invoice = SubscriptionInvoice::query()->whereKey($invoice->id)->lockForUpdate()->firstOrFail();

            if ($invoice->status === InvoiceStatus::Paid) {
                return $invoice;
            }

            if ($invoice->status === InvoiceStatus::Void) {
                throw ValidationException::withMessages([
                    'invoice' => 'Invoice yang dibatalkan tidak bisa dilunasi.',
                ]);
            }

            if ($invoice->status !== InvoiceStatus::AwaitingVerification || blank($invoice->payment_proof_path)) {
                throw ValidationException::withMessages([
                    'invoice' => 'Hanya invoice dengan bukti transfer yang bisa dilunasi.',
                ]);
            }

            /** @var Restaurant $restaurant */
            $restaurant = Restaurant::query()->whereKey($invoice->restaurant_id)->lockForUpdate()->firstOrFail();

            $months = max(1, min(12, (int) ($invoice->billing_months ?: 1)));
            $planCode = $invoice->effectivePlanCode();
            [$start, $until] = $this->resolvePaidPeriod($restaurant, $planCode, $months);

            $invoice->forceFill([
                'status' => InvoiceStatus::Paid,
                'plan_code' => $planCode,
                'period_start' => $start,
                'period_end' => $until,
                'paid_at' => now(),
                'verified_by' => $verifier->id,
                'amount' => $this->calculateAmount($planCode, $months),
            ])->save();

            $restaurant->forceFill([
                'subscription_status' => SubscriptionStatus::Active,
                'subscribed_until' => $until,
                'grace_ends_at' => null,
            ])->save();

            $this->planSync->apply($restaurant, $planCode);
            $becamePaid = true;

            return $invoice->fresh();
        });

        if ($becamePaid) {
            $this->notifyRestaurantOwners(
                $paid,
                'Pembayaran berhasil',
                $this->paidNotificationBody($paid),
            );
        }

        return $paid;
    }

    public function reject(SubscriptionInvoice $invoice, string $notes, User $verifier): SubscriptionInvoice
    {
        $rejected = DB::transaction(function () use ($invoice, $notes, $verifier): SubscriptionInvoice {
            /** @var SubscriptionInvoice $invoice */
            $invoice = SubscriptionInvoice::query()->whereKey($invoice->id)->lockForUpdate()->firstOrFail();

            if ($invoice->status !== InvoiceStatus::AwaitingVerification) {
                throw ValidationException::withMessages([
                    'invoice' => 'Hanya invoice menunggu verifikasi yang bisa ditolak.',
                ]);
            }

            $previousProof = $invoice->payment_proof_path;

            $invoice->forceFill([
                'status' => InvoiceStatus::Sent,
                'rejection_notes' => $notes,
                'verified_by' => $verifier->id,
                'payment_proof_path' => null,
                'payment_submitted_at' => null,
            ])->save();

            $this->deleteProofFile($previousProof);

            return $invoice->fresh(['restaurant']);
        });

        $this->notifyRestaurantOwners(
            $rejected,
            'Bukti transfer ditolak',
            "Bukti transfer invoice {$rejected->invoice_number} ditolak. {$notes}",
            'warning',
        );

        return $rejected;
    }

    public function void(SubscriptionInvoice $invoice): SubscriptionInvoice
    {
        return DB::transaction(function () use ($invoice): SubscriptionInvoice {
            /** @var SubscriptionInvoice $invoice */
            $invoice = SubscriptionInvoice::query()->whereKey($invoice->id)->lockForUpdate()->firstOrFail();

            if ($invoice->status === InvoiceStatus::Paid) {
                throw ValidationException::withMessages([
                    'invoice' => 'Invoice lunas tidak bisa dibatalkan.',
                ]);
            }

            $previousProof = $invoice->payment_proof_path;

            $invoice->forceFill([
                'status' => InvoiceStatus::Void,
                'period_key' => $invoice->period_key ? 'void-'.$invoice->id : $invoice->period_key,
                'payment_proof_path' => null,
                'payment_submitted_at' => null,
            ])->save();

            $this->deleteProofFile($previousProof);

            return $invoice->fresh();
        });
    }

    public function deleteUnpaid(SubscriptionInvoice $invoice): void
    {
        DB::transaction(function () use ($invoice): void {
            /** @var SubscriptionInvoice $invoice */
            $invoice = SubscriptionInvoice::query()->whereKey($invoice->id)->lockForUpdate()->firstOrFail();

            if ($invoice->status === InvoiceStatus::Paid) {
                throw ValidationException::withMessages([
                    'invoice' => 'Riwayat invoice lunas tidak bisa dihapus.',
                ]);
            }

            if (! $invoice->isOpen()) {
                throw ValidationException::withMessages([
                    'invoice' => 'Hanya invoice yang belum lunas yang bisa dihapus.',
                ]);
            }

            $previousProof = $invoice->payment_proof_path;
            $invoice->delete();
            $this->deleteProofFile($previousProof);
        });
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolvePaidPeriod(Restaurant $restaurant, string $planCode, int $months): array
    {
        $now = now();
        $expiry = $this->gate->effectiveExpiryAt($restaurant);
        $samePlan = $planCode === $restaurant->plan_code;
        $hasRemainder = $expiry && $expiry->isFuture();

        if ($samePlan && $hasRemainder) {
            $start = $expiry->copy();
            $until = $expiry->copy()->addMonths($months);

            return [$start, $until];
        }

        $start = $now->copy();
        $until = $now->copy()->addMonths($months);

        return [$start, $until];
    }

    public function nextNumber(): string
    {
        $prefix = 'INV-'.now()->format('Ym').'-';

        $last = SubscriptionInvoice::query()
            ->where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('invoice_number')
            ->lockForUpdate()
            ->value('invoice_number');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    private function paidNotificationBody(SubscriptionInvoice $invoice): string
    {
        $until = $invoice->period_end?->timezone(config('app.timezone'))->translatedFormat('d M Y');

        return filled($until)
            ? "Invoice {$invoice->invoice_number} sudah dikonfirmasi. Langganan aktif sampai {$until}."
            : "Invoice {$invoice->invoice_number} sudah dikonfirmasi. Pembayaran langganan berhasil.";
    }

    private function deleteProofFile(?string $path): void
    {
        CmsMedia::delete($path, 'local');
    }

    private function notifyRestaurantOwners(
        SubscriptionInvoice $invoice,
        string $title,
        string $body,
        string $type = 'success',
    ): void {
        $restaurant = $invoice->restaurant ?? Restaurant::query()->find($invoice->restaurant_id);

        if (! $restaurant instanceof Restaurant) {
            return;
        }

        $url = SubscriptionStatusPage::getUrl(panel: 'admin', tenant: $restaurant);

        $restaurant->users()
            ->where('users.is_active', true)
            ->wherePivot('is_active', true)
            ->whereHasRestaurantRole('owner', $restaurant->id)
            ->each(function (User $user) use ($title, $body, $url, $type): void {
                $this->notifications->sendWebNotification(
                    recipient: $user,
                    title: $title,
                    body: $body,
                    url: $url,
                    type: $type,
                    actionLabel: 'Lihat langganan',
                );
            });
    }

    private function notifyPlatformOperators(SubscriptionInvoice $invoice): void
    {
        $restaurantName = $invoice->restaurant?->name ?? 'Restoran';
        $body = "Invoice {$invoice->invoice_number} dari {$restaurantName} sudah diunggah bukti transfernya dan menunggu tinjauan.";
        $url = SubscriptionInvoiceResource::getUrl('view', ['record' => $invoice], panel: 'founder');

        User::query()
            ->where('is_active', true)
            ->whereHasGlobalRole(['founder', 'super_admin'])
            ->each(function (User $user) use ($body, $url): void {
                $this->notifications->sendWebNotification(
                    recipient: $user,
                    title: 'Invoice perlu ditinjau',
                    body: $body,
                    url: $url,
                    type: 'info',
                    actionLabel: 'Tinjau invoice',
                );
            });
    }
}
