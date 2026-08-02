<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'guest_email' => 'nullable|email|required_without:customer_id',
            'shipping_address_id' => 'required|exists:customer_addresses,id',
            'billing_address_id' => 'nullable|exists:customer_addresses,id',
            'payment_method' => 'required|in:stripe,paypal,sslcommerz,bkash,nagad,cod',
            'coupon_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'guest_email.email' => 'Please provide a valid email address.',
            'guest_email.required_without' => 'Email is required for guest checkout.',
            'shipping_address_id.required' => 'Shipping address is required.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
        ];
    }
}
