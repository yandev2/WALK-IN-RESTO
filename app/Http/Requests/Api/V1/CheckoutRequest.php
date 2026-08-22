<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => ['required', 'in:qris,cash'],
            'send_receipt' => ['sometimes', 'boolean'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
            'gps' => ['sometimes', 'array'],
            'gps.lat' => ['nullable', 'numeric'],
            'gps.lng' => ['nullable', 'numeric'],
            'gps.accuracy' => ['nullable', 'numeric'],
            'gps.gps_status' => ['nullable', 'string', 'max:32'],
        ];
    }
}
