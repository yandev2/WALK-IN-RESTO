<?php

namespace App\Services;

use App\Jobs\SendWhatsappReceiptJob;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderReceipt;
use App\Models\User;
use App\Models\WhatsappMessage;
use App\Support\ActivityLogger;
use App\Support\OrderReceiptDownloadUrl;
use App\Support\OrderReceiptWhatsappMessage;
use App\Support\ReceiptLogo;
use App\Support\WhatsAppNumber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderReceiptService
{
    public function afterPaid(Order $order, ?User $user = null): void
    {
        $receipt = $this->generate($order);
        $order->loadMissing(['restaurant', 'visit']);

        if (! $order->send_receipt || ! $order->restaurant?->hasFonnteKey()) {
            return;
        }

        $to = $order->receipt_wa_snapshot ?: $order->visit?->customer_wa;

        if (blank($to)) {
            $this->recordImmediateFailure($order, $receipt, $to, $user, 'Nomor WhatsApp tamu belum ada.');

            return;
        }

        $this->enqueue($order, $receipt, $to, $user);
    }

    public function generate(Order $order): OrderReceipt
    {
        $order->loadMissing([
            'items.modifiers',
            'visit.diningTable',
            'restaurant',
            'outlet',
        ]);

        $payment = $order->payments()->with('paidByUser')->latest('id')->first();

        $customer = null;
        $earnedPoints = 0;
        $showLoyalty = false;

        $rawPhone = $order->receipt_wa_snapshot ?: $order->visit?->customer_wa;
        if (filled($rawPhone)) {
            $phone = WhatsAppNumber::normalize($rawPhone);
            if (is_string($phone)) {
                $customer = Customer::withoutRestaurantScope()
                    ->where('restaurant_id', $order->restaurant_id)
                    ->where('phone', $phone)
                    ->first();
            }
        }

        $loyaltySettings = $order->restaurant?->loyaltySettings() ?? [];
        if ($customer instanceof Customer && ($loyaltySettings['enabled'] ?? false)) {
            $showLoyalty = true;
            $earnedMutation = $customer->loyaltyPoints()
                ->where('order_id', $order->id)
                ->where('type', 'earn')
                ->first();
            $earnedPoints = $earnedMutation ? (int) $earnedMutation->points : 0;
        }

        $path = 'receipts/'.$order->public_id.'.pdf';
        $pdf = Pdf::loadView('receipts.order', [
            'order' => $order,
            'payment' => $payment,
            'logoDataUri' => ReceiptLogo::dataUri($order->restaurant),
            'customer' => $customer,
            'earnedPoints' => $earnedPoints,
            'showLoyalty' => $showLoyalty,
        ])->setPaper([0, 0, 226.77, 1200], 'portrait');

        Storage::disk('local')->put($path, $pdf->output());

        $existing = $order->receipt ?? $order->receipt()->first();

        if ($existing) {
            $existing->forceFill([
                'file_path' => $path,
                'generated_at' => now(),
            ])->save();

            return $existing;
        }

        return OrderReceipt::query()->create([
            'restaurant_id' => $order->restaurant_id,
            'outlet_id' => $order->outlet_id,
            'order_id' => $order->id,
            'file_path' => $path,
            'generated_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function streamPdf(Order $order, string $disposition = 'inline'): StreamedResponse
    {
        abort_unless(filled($order->paid_at), 404);

        $receipt = $this->generate($order);

        abort_unless(Storage::disk('local')->exists($receipt->file_path), 404);

        return Storage::disk('local')->response(
            $receipt->file_path,
            'struk-'.$order->number.'.pdf',
            ['Content-Type' => 'application/pdf'],
            $disposition,
        );
    }

    public function resend(Order $order, User $user): WhatsappMessage
    {
        $order->loadMissing(['restaurant', 'visit', 'receipt']);

        if (blank($order->paid_at)) {
            throw ValidationException::withMessages([
                'order' => 'Struk hanya untuk pesanan yang sudah lunas.',
            ]);
        }

        if (! $order->restaurant?->hasFonnteKey()) {
            throw ValidationException::withMessages([
                'fonnte' => 'Owner harus isi API key Fonnte di Profil CMS → WhatsApp (Fonnte).',
            ]);
        }

        if (! $order->restaurant->fonnteApiKey()) {
            throw ValidationException::withMessages([
                'fonnte' => 'API key Fonnte tidak bisa dibaca. Simpan ulang key di Profil CMS.',
            ]);
        }

        $to = $order->visit?->customer_wa ?: $order->receipt_wa_snapshot;

        if (blank($to)) {
            throw ValidationException::withMessages([
                'customer_wa' => 'Nomor WhatsApp tamu belum ada.',
            ]);
        }

        $receipt = $this->generate($order);
        $message = $this->enqueue($order, $receipt, $to, $user);

        ActivityLogger::log('receipt.resend', [
            'restaurant_id' => $order->restaurant_id,
            'outlet_id' => $order->outlet_id,
            'visit_id' => $order->visit_id,
            'order_id' => $order->id,
            'whatsapp_message_id' => $message->id,
            'user_id' => $user->id,
        ]);

        return $message;
    }

    public function resendMessage(WhatsappMessage $message, User $user): WhatsappMessage
    {
        $order = $message->order;

        if (! $order) {
            throw ValidationException::withMessages([
                'order' => 'Pesan ini tidak terkait pesanan.',
            ]);
        }

        return $this->resend($order, $user);
    }

    private function enqueue(Order $order, OrderReceipt $receipt, string $to, ?User $user): WhatsappMessage
    {
        $normalized = WhatsAppNumber::normalize($to);

        if (! WhatsAppNumber::isValid($normalized)) {
            return $this->recordImmediateFailure(
                $order,
                $receipt,
                $to,
                $user,
                'Nomor WhatsApp tujuan tidak valid. Gunakan format 08… atau 62…',
            );
        }

        if (blank(OrderReceiptDownloadUrl::signed($order))) {
            return $this->recordImmediateFailure(
                $order,
                $receipt,
                $normalized,
                $user,
                'Link unduh struk tidak bisa dibuat. Periksa APP_URL di server.',
            );
        }

        $message = WhatsappMessage::query()->create([
            'restaurant_id' => $order->restaurant_id,
            'outlet_id' => $order->outlet_id,
            'visit_id' => $order->visit_id,
            'order_id' => $order->id,
            'receipt_id' => $receipt->id,
            'requested_by_user_id' => $user?->id,
            'kind' => 'receipt',
            'provider' => 'fonnte',
            'to_wa' => $normalized,
            'body' => OrderReceiptWhatsappMessage::compose($order),
            'media_path' => $receipt->file_path,
            'status' => 'queued',
            'attempts' => 0,
            'queued_at' => now(),
        ]);

        SendWhatsappReceiptJob::dispatch($message->id);

        return $message;
    }

    private function recordImmediateFailure(
        Order $order,
        OrderReceipt $receipt,
        ?string $to,
        ?User $user,
        string $error,
    ): WhatsappMessage {
        return WhatsappMessage::query()->create([
            'restaurant_id' => $order->restaurant_id,
            'outlet_id' => $order->outlet_id,
            'visit_id' => $order->visit_id,
            'order_id' => $order->id,
            'receipt_id' => $receipt->id,
            'requested_by_user_id' => $user?->id,
            'kind' => 'receipt',
            'provider' => 'fonnte',
            'to_wa' => $to ?: '-',
            'body' => OrderReceiptWhatsappMessage::compose($order),
            'media_path' => $receipt->file_path,
            'status' => 'failed',
            'attempts' => 0,
            'last_error' => substr($error, 0, 500),
            'queued_at' => now(),
            'failed_at' => now(),
        ]);
    }
}
