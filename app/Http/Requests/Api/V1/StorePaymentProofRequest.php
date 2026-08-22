<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proof' => ['required', 'file', 'max:5120', 'mimetypes:image/jpeg,image/png,image/webp,image/heic,image/heif'],
        ];
    }

    public function messages(): array
    {
        return [
            'proof.required' => 'Pilih foto bukti transfer.',
            'proof.file' => 'Berkas tidak valid.',
            'proof.max' => 'Ukuran foto maksimal 5 MB.',
            'proof.mimetypes' => 'Unggah foto JPG, PNG, atau WEBP.',
        ];
    }
}
