<?php

namespace App\Services;

use App\Support\FonnteErrorMessage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class FonnteClient
{
    private const ENDPOINT = 'https://api.fonnte.com/send';

    /**
     * @return array{ok: bool, provider_ref: ?string, error: ?string, retryable: bool, payload: ?array<string, mixed>}
     */
    public function sendReceiptMessage(string $apiKey, string $target, string $message): array
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders(['Authorization' => $apiKey])
                ->post(self::ENDPOINT, [
                    'target' => $target,
                    'message' => $message,
                    'preview' => false,
                ]);
        } catch (ConnectionException $exception) {
            $error = FonnteErrorMessage::fromThrowable($exception);

            return [
                'ok' => false,
                'provider_ref' => null,
                'error' => $error,
                'retryable' => true,
                'payload' => ['exception' => $exception->getMessage()],
            ];
        }

        /** @var array<string, mixed>|null $payload */
        $payload = $response->json();
        $payload ??= ['raw' => $response->body()];

        if ($this->isSuccessful($response, $payload)) {
            return [
                'ok' => true,
                'provider_ref' => $this->extractProviderRef($payload),
                'error' => null,
                'retryable' => false,
                'payload' => $payload,
            ];
        }

        $error = FonnteErrorMessage::fromResponse($payload, $response->status());

        return [
            'ok' => false,
            'provider_ref' => null,
            'error' => $error,
            'retryable' => FonnteErrorMessage::isRetryable($error),
            'payload' => $payload,
        ];
    }

    /**
     * @param  array<string, mixed>|null  $payload
     */
    private function isSuccessful(Response $response, ?array $payload): bool
    {
        if (! $response->successful()) {
            return false;
        }

        $status = $payload['status'] ?? $payload['Status'] ?? null;

        return $status === true || $status === 'true' || $status === 1 || $status === '1';
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function extractProviderRef(array $payload): ?string
    {
        $id = $payload['id'] ?? null;

        if (is_array($id)) {
            $id = $id[0] ?? null;
        }

        if (filled($id)) {
            return (string) $id;
        }

        $requestId = $payload['requestid'] ?? null;

        return filled($requestId) ? (string) $requestId : null;
    }

    /**
     * @param  array{ok: bool, provider_ref: ?string, error: ?string, retryable: bool, payload: ?array<string, mixed>}  $result
     */
    public function toException(array $result): RuntimeException
    {
        return new RuntimeException(substr((string) ($result['error'] ?: 'Gagal kirim Fonnte.'), 0, 400));
    }
}
