<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RefundApprovalRequest extends FormRequest
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
            'status'         => 'required|in:approved,rejected,completed',
            'admin_note'     => 'nullable|string|max:500',
            'transaction_id' => 'required_if:status,completed|nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_id.required_if' => 'Completed করতে Transaction ID দিতে হবে।',
        ];
    }
}
