<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_item_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'cart_item_id.required' => 'Cart item is required.',
            'cart_item_id.exists' => 'Invalid cart item.',
            'quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
