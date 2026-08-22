<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ClaimTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_wa' => ['required', 'string', 'max:20'],
            'customer_name' => ['nullable', 'string', 'max:120'],
        ];
    }
}
