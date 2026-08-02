<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'variants'                       => 'required|array|min:1',
            'variants.*.price'               => 'required|numeric|min:0',
            'variants.*.stock'               => 'required|integer|min:0',
            'variants.*.sale_price'          => 'nullable|numeric|min:0',
            'variants.*.sku'                 => 'nullable|string',
            'variants.*.attribute_value_ids' => 'required|array|min:1',
        ];
    }
}
