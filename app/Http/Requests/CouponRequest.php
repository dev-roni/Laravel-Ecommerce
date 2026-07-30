<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'code'              => 'required|string|max:30|unique:coupons,code',
            'type'              => 'required|in:fixed,percent',
            'value'             => 'required|numeric|min:0',
            'min_order_amount'  => 'nullable|numeric|min:0',
            'max_discount'      => 'nullable|numeric|min:0',
            'usage_limit'       => 'nullable|integer|min:1',
            'per_user_limit'    => 'required|integer|min:1',
            'starts_at'         => 'nullable|date',
            'expires_at'        => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'  => 'Coupon code দিতে হবে।',
            'code.unique'    => 'এই code আগে থেকেই আছে।',
            'value.required' => 'মূল্য দিতে হবে।',
        ];
    }
}
