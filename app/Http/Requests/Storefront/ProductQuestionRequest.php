<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class ProductQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:200',
            'customer_email' => 'required|email|max:255',
            'question' => 'required|string|min:10|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required.',
            'customer_name.required' => 'Please enter your name.',
            'customer_email.required' => 'Please enter your email address.',
            'customer_email.email' => 'Please provide a valid email address.',
            'question.required' => 'Please enter your question.',
            'question.min' => 'Question must be at least 10 characters.',
        ];
    }
}
