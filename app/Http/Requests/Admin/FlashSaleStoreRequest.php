<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FlashSaleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:300',
            'description' => 'nullable|string',
            'starts_at' => 'required|date|after_or_equal:today',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.discount_percentage' => 'required|numeric|min:1|max:99',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Flash sale title is required.',
            'starts_at.required' => 'Start date is required.',
            'ends_at.required' => 'End date is required.',
            'ends_at.after' => 'End date must be after start date.',
            'products.required' => 'At least one product is required.',
            'products.min' => 'At least one product is required.',
            'products.*.discount_percentage.min' => 'Discount must be at least 1%.',
            'products.*.discount_percentage.max' => 'Discount cannot exceed 99%.',
        ];
    }
}
