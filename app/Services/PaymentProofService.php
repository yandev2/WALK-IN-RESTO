<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Visit;
use App\Support\CmsMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class PaymentProofService
{
    public function store(Visit $visit, Order $order, UploadedFile $file): Payment
    {
        $payment = $this->assertCanMutate($visit, $order);

        $path = $file->store('payment-proofs/'.$order->restaurant_id, 'public');

        if (filled($payment->proof_image_path)) {
            CmsMedia::delete($payment->proof_image_path);
        }

        $payment->forceFill(['proof_image_path' => $path])->save();

        return $payment->refresh();
    }

    public function delete(Visit $visit, Order $order): Payment
    {
        $payment = $this->assertCanMutate($visit, $order);

        if (filled($payment->proof_image_path)) {
            CmsMedia::delete($payment->proof_image_path);
        }

        $payment->forceFill(['proof_image_path' => null])->save();

        return $payment->refresh();
    }

    private function assertCanMutate(Visit $visit, Order $order): Payment
    {
        if ((int) $order->visit_id !== (int) $visit->id) {
            abort(403);
        }

        $payment = $order->payments()->latest('id')->first();

        if (
            $order->status !== 'awaiting_cashier'
            || $order->payment_method !== 'qris'
            || ! $payment instanceof Payment
            || $payment->method !== 'qris'
        ) {
            throw ValidationException::withMessages([
                'proof' => 'Bukti transfer hanya untuk pesanan QRIS yang menunggu kasir.',
            ]);
        }

        return $payment;
    }
}
