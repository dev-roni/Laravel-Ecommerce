<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:200|unique:products,name',
            'category_id'       => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:300',
            'description'       => 'nullable|string',
            'brand'             => 'nullable|string|max:100',
            'sku'               => 'nullable|string|unique:products,sku',
            'base_price'        => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:base_price',
            'stock'             => 'required_if:has_variants,false|integer|min:0',
            'weight'            => 'nullable|string',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'has_variants'      => 'boolean',

            // ছবি
            'images'            => 'nullable|array|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'primary_image'     => 'nullable|integer', // images array-এর index

            // variant
            'variants'                        => 'required_if:has_variants,true|array',
            'variants.*.sku'                  => 'nullable|string|distinct',
            'variants.*.price'                => 'required_if:has_variants,true|numeric|min:0',
            'variants.*.stock'                => 'required_if:has_variants,true|integer|min:0',
            'variants.*.attribute_value_ids'  => 'required_if:has_variants,true|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Product-এর নাম দিতে হবে।',
            'name.unique'          => 'এই নামে Product আগে থেকেই আছে।',
            'category_id.required' => 'Category নির্বাচন করুন।',
            'base_price.required'  => 'মূল দাম দিতে হবে।',
            'sale_price.lt'        => 'ছাড়ের দাম মূল দামের চেয়ে কম হতে হবে।',
        ];
    }
}
