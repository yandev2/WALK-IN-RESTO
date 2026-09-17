<?php

namespace App\Jobs;

use App\Models\WhatsappMessage;
use App\Services\FonnteClient;
use App\Support\FonnteErrorMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendWhatsappReceiptJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * @return list<int>
     */
    public function backoff(): array
    {
        return [15, 60, 180];
    }

    public function __construct(public int $whatsappMessageId)
    {
        $this->afterCommit = true;
    }

    public function handle(FonnteClient $fonnte): void
    {
        $message = WhatsappMessage::query()
            ->with(['restaurant', 'order'])
            ->find($this->whatsappMessageId);

        if (! $message || $message->status === 'sent') {
            return;
        }

        $message->increment('attempts');
        $message->refresh();

        $apiKey = $message->restaurant?->fonnteApiKey();

        if (blank($apiKey)) {
            $this->markPermanentFailure(
                $message,
                $message->restaurant?->hasFonnteKey()
                    ? 'API key Fonnte tidak bisa dibaca. Simpan ulang di Profil CMS.'
                    : 'API key Fonnte kosong. Isi di Profil CMS → WhatsApp (Fonnte).',
                null,
            );
            $this->fail(new \RuntimeException('API key Fonnte tidak tersedia.'));

            return;
        }

        $result = $fonnte->sendReceiptMessage(
            $apiKey,
            (string) $message->to_wa,
            (string) $message->body,
        );

        if ($result['ok']) {
            $message->forceFill([
                'status' => 'sent',
                'sent_at' => now(),
                'failed_at' => null,
                'last_error' => null,
                'provider_ref' => $result['provider_ref'],
                'provider_payload' => $result['payload'],
            ])->save();

            return;
        }

        $message->forceFill([
            'last_error' => substr((string) $result['error'], 0, 500),
            'provider_payload' => $result['payload'],
        ])->save();

        $exception = $fonnte->toException($result);

        if (! $result['retryable']) {
            $this->markPermanentFailure($message, (string) $result['error'], $result['payload']);
            $this->fail($exception);

            return;
        }

        throw $exception;
    }

    public function failed(?Throwable $exception): void
    {
        $message = WhatsappMessage::query()->find($this->whatsappMessageId);

        if (! $message || $message->status === 'sent') {
            return;
        }

        $this->markPermanentFailure(
            $message,
            FonnteErrorMessage::fromThrowable($exception ?? new \RuntimeException('Gagal kirim Fonnte.')),
            is_array($message->provider_payload) ? $message->provider_payload : null,
        );
    }

    /**
     * @param  array<string, mixed>|null  $payload
     */
    private function markPermanentFailure(WhatsappMessage $message, string $error, ?array $payload): void
    {
        $message->forceFill([
            'status' => 'failed',
            'failed_at' => now(),
            'last_error' => substr($error, 0, 500),
            'provider_payload' => $payload ?? $message->provider_payload,
        ])->save();
    }
}
