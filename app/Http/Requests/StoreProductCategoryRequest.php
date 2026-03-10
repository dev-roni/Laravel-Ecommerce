<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductCategoryRequest extends FormRequest
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
            'product_category_name'        => 'required|string|max:100|min:2',
            'category_slug'        => 'required|string|unique:product_categories,category_slug|max:100', 
            'category_description' => 'nullable|string|max:500', 
            'category_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'order'       => 'nullable|integer|max:200', 
            'is_active'   => 'boolean' 
        ];
    }

    public function messages(): array
    {
        return [
            // ক্যাটাগরির নাম
            'product_category_name.required' => 'ক্যাটাগরির নাম প্রদান করা আবশ্যক।',
            'product_category_name.string'   => 'ক্যাটাগরির নাম অবশ্যই সঠিক বর্ণমালায় হতে হবে।',
            'product_category_name.min'      => 'ক্যাটাগরির নাম অন্তত ২ অক্ষরের হতে হবে।',
            'product_category_name.max'      => 'ক্যাটাগরির নাম ২০ অক্ষরের বেশি হওয়া যাবে না।',

            // স্লাগ
            'category_slug.required'         => 'ক্যাটাগরি স্লাগ (Slug) অবশ্যই দিতে হবে।',
            'category_slug.string'           => 'স্লাগটি সঠিক ফরমেটে হতে হবে।',
            'category_slug.max'              => 'স্লাগ ২০ অক্ষরের বেশি হতে পারবে না।',

            // বিবরণ
            'category_description.required'  => 'ক্যাটাগরির একটি সংক্ষিপ্ত বিবরণ দিন।',
            'category_description.max'       => 'বিবরণ ২০০ অক্ষরের মধ্যে সীমাবদ্ধ রাখুন।',

            // ছবি
            'category_image.string'          => 'ছবির পাথ বা নাম অবশ্যই সঠিক টেক্সট হতে হবে।',
            'category_image.max'             => 'ছবির নাম বা পাথ ৫০ অক্ষরের বেশি হতে পারবে না।',

            //ক্রম
            'order.max'             => 'ক্রম নাম্বার সর্বোচ্চ 200 এর উপর হতে পারবে না',

            // স্ট্যাটাস
            'is_active.boolean'     => 'স্ট্যাটাসটি অবশ্যই সচল (Active) অথবা অচল (Inactive) হতে হবে।',
        ];
    }

}
