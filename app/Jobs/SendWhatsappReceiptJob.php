<?php

namespace App\Jobs;

use App\Models\WhatsappMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class SendWhatsappReceiptJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $whatsappMessageId) {}

    public function handle(): void
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
            $message->forceFill([
                'status' => 'failed',
                'failed_at' => now(),
                'last_error' => 'API key Fonnte kosong.',
            ])->save();

            return;
        }

        $path = $message->media_path;
        $file = $path && Storage::disk('local')->exists($path)
            ? Storage::disk('local')->get($path)
            : null;

        $request = Http::timeout(20)
            ->withHeaders(['Authorization' => $apiKey]);

        if (is_string($file)) {
            $filename = 'struk-'.($message->order?->number ?? $message->id).'.pdf';
            $request = $request->attach('file', $file, $filename);
        }

        $response = $request->post('https://api.fonnte.com/send', [
            'target' => $message->to_wa,
            'message' => $message->body,
        ]);

        $ok = $response->successful() && ($response->json('status') === true || $response->json('status') === 'true');

        if (! $ok) {
            $error = $response->json('reason')
                ?? $response->json('message')
                ?? ('HTTP '.$response->status());

            throw new RuntimeException(substr((string) $error, 0, 400));
        }

        $message->forceFill([
            'status' => 'sent',
            'sent_at' => now(),
            'failed_at' => null,
            'last_error' => null,
            'provider_ref' => $response->json('id') ? (string) $response->json('id') : null,
        ])->save();
    }

    public function failed(?Throwable $exception): void
    {
        $message = WhatsappMessage::query()->find($this->whatsappMessageId);

        if (! $message || $message->status === 'sent') {
            return;
        }

        $message->forceFill([
            'status' => 'failed',
            'failed_at' => now(),
            'last_error' => substr($exception?->getMessage() ?: 'Gagal kirim Fonnte', 0, 500),
        ])->save();
    }
}
