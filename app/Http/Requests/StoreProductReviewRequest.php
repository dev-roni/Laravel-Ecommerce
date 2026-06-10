<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductReviewRequest extends FormRequest
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
            'rating' => 'nullable|integer|min:1|max:5',
            'title'  => 'nullable|string|max:100',
            'body'   => 'required|string|min:5|max:1000',
        ];
    }
    public function messages(): array
    {
        return [
            'body.required'   => 'Review লিখতে হবে।',
            'body.min'        => 'Review কমপক্ষে 5 অক্ষর হতে হবে।',
        ];
    }
}
