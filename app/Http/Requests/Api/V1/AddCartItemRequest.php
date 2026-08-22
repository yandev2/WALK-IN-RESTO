<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'menu_variant_id' => ['nullable', 'integer', 'exists:menu_variants,id'],
            'qty' => ['sometimes', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
            'modifier_ids' => ['nullable', 'array'],
            'modifier_ids.*' => ['integer', 'exists:modifiers,id'],
        ];
    }
}
