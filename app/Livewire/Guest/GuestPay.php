<?php

namespace App\Livewire\Guest;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentProofService;
use App\Support\GuestContext;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.guest-order', ['title' => 'Menunggu kasir'])]
class GuestPay extends Component
{
    use WithFileUploads;

    public Order $order;

    public mixed $proof = null;

    public bool $pausePoll = false;

    public string $proofMessage = '';

    public function mount(Order $order): void
    {
        $visit = GuestContext::visit();

        abort_unless($visit && (int) $order->visit_id === (int) $visit->id, 403);

        $this->order = $order->load(['payments', 'items']);
    }

    public function updatedProof(PaymentProofService $proofs): void
    {
        $this->pausePoll = false;
        $this->proofMessage = '';

        $visit = GuestContext::visit();

        if (! $visit || ! $this->proof instanceof TemporaryUploadedFile) {
            $this->reset('proof');

            return;
        }

        $this->validate([
            'proof' => ['required', 'file', 'max:5120', 'mimetypes:image/jpeg,image/png,image/webp,image/heic,image/heif'],
        ], [
            'proof.required' => 'Pilih foto bukti transfer.',
            'proof.file' => 'Berkas tidak valid.',
            'proof.max' => 'Ukuran foto maksimal 5 MB.',
            'proof.mimetypes' => 'Unggah foto JPG, PNG, atau WEBP.',
        ]);

        $proofs->store($visit, $this->order, $this->proof);
        $this->reset('proof');
        $this->proofMessage = 'Bukti terkirim. Kasir akan mencocokkan.';
        $this->order->load('payments');
    }

    public function removeProof(PaymentProofService $proofs): void
    {
        $visit = GuestContext::visit();

        if (! $visit) {
            return;
        }

        $proofs->delete($visit, $this->order);
        $this->proofMessage = 'Bukti dihapus. Anda bisa unggah ulang.';
        $this->order->load('payments');
    }

    public function render()
    {
        $this->order->refresh()->load(['payments', 'items']);
        $payment = $this->currentPayment();
        $visit = GuestContext::visit();

        return view('livewire.guest.pay', [
            'payment' => $payment,
            'visit' => $visit,
            'cartCount' => $visit?->cartItems()->sum('qty') ?? 0,
        ]);
    }

    private function currentPayment(): ?Payment
    {
        $payment = $this->order->payments->sortByDesc('id')->first();

        return $payment instanceof Payment ? $payment : null;
    }
}
