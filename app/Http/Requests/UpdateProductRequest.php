<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product')->id;
        return [
            'name'        => "required|string|max:200|unique:products,name,{$productId}",
            'category_id' => 'required|exists:categories,id',
            'base_price'  => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lt:base_price',
            'stock'       => 'required_if:has_variants,false|integer|min:0',
            'brand'       => 'nullable|string|max:100',
            'sku'         => "nullable|string|unique:products,sku,{$productId}",
            'weight'      => 'nullable|string',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'has_variants' => 'boolean',
            'short_description' => 'nullable|string|max:300',
            'description'       => 'nullable|string',
            'images'            => 'nullable|array|max:5',
            'images.*'          => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants'                       => 'required_if:has_variants,true|array',
            'variants.*.price'               => 'required_if:has_variants,true|numeric|min:0',
            'variants.*.stock'               => 'required_if:has_variants,true|integer|min:0',
            'variants.*.attribute_value_ids' => 'required_if:has_variants,true|array',
        ];
    }
}
