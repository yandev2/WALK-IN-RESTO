<?php

namespace App\Services;

use App\Jobs\SendWhatsappReceiptJob;
use App\Models\Order;
use App\Models\OrderReceipt;
use App\Models\User;
use App\Models\WhatsappMessage;
use App\Support\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
            return;
        }

        $this->enqueue($order, $receipt, $to, $user);
    }

    public function generate(Order $order): OrderReceipt
    {
        $existing = $order->receipt ?? $order->receipt()->first();

        if ($existing) {
            return $existing;
        }

        $order->loadMissing([
            'items',
            'items.modifiers',
            'visit.diningTable',
            'restaurant',
            'outlet',
            'payments',
        ]);

        $pdf = Pdf::loadView('receipts.order', ['order' => $order]);
        $path = 'receipts/'.$order->public_id.'.pdf';
        Storage::disk('local')->put($path, $pdf->output());

        return OrderReceipt::query()->create([
            'restaurant_id' => $order->restaurant_id,
            'outlet_id' => $order->outlet_id,
            'order_id' => $order->id,
            'file_path' => $path,
            'generated_at' => now(),
            'created_at' => now(),
        ]);
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
                'fonnte' => 'Owner harus isi API key Fonnte di pengaturan restoran.',
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
        $table = $order->visit?->diningTable?->code ?: '-';
        $body = sprintf(
            'Struk %s order #%s meja %s. Total Rp %s',
            $order->restaurant?->name ?: 'restoran',
            $order->number,
            $table,
            number_format((int) $order->grand_payable, 0, ',', '.'),
        );

        $message = WhatsappMessage::query()->create([
            'restaurant_id' => $order->restaurant_id,
            'outlet_id' => $order->outlet_id,
            'visit_id' => $order->visit_id,
            'order_id' => $order->id,
            'receipt_id' => $receipt->id,
            'requested_by_user_id' => $user?->id,
            'kind' => 'receipt',
            'provider' => 'fonnte',
            'to_wa' => $to,
            'body' => $body,
            'media_path' => $receipt->file_path,
            'status' => 'queued',
            'attempts' => 0,
            'queued_at' => now(),
        ]);

        SendWhatsappReceiptJob::dispatch($message->id);

        return $message;
    }
}
