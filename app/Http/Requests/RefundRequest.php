<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
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
            'reason'         => 'required|string|min:20|max:500',
            'refund_method'  => 'required|in:bkash,nagad,bank',
            'refund_account' => 'required|string|max:50',
            'amount'         => 'required|numeric|min:1|max:' . $order->total,
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required'         => 'কারণ লিখতে হবে।',
            'reason.min'              => 'কারণ কমপক্ষে ২০ অক্ষর হতে হবে।',
            'refund_method.required'  => 'Refund পদ্ধতি নির্বাচন করুন।',
            'refund_account.required' => 'Account নম্বর দিতে হবে।',
            'amount.max'              => 'Refund amount order total-এর বেশি হতে পারবে না।',
        ];
    }
}
