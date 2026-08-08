<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone' => [
                                    'required',
                                    'string',
                                    'regex:/^(\+8801|8801|01)[3-9][0-9]{8}$/',
                                ],
            'shipping_address' => 'required|string|min:5|max:500',
            'shipping_city'    => 'required|string|min:3|max:100',
            'payment_method'   => 'required|in:cod,online',
            'notes'            => 'nullable|string|max:300',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_name.required'    => 'নাম দিতে হবে।',
            'shipping_phone.required'   => 'ফোন নম্বর দিতে হবে।',
            'shipping_address.required' => 'ঠিকানা দিতে হবে।',
            'shipping_city.required'    => 'শহর দিতে হবে।',
            'payment_method.required'   => 'Payment পদ্ধতি নির্বাচন করুন।',
        ];
    }
}
