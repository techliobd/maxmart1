<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_status_id' => 'required|exists:order_statuses,id',
            'tracking_number' => 'nullable|string|max:200',
            'shipping_carrier' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:2000',
            'refund_amount' => 'nullable|numeric|min:0|lte:total',
        ];
    }

    public function messages(): array
    {
        return [
            'order_status_id.required' => 'Order status is required.',
            'order_status_id.exists' => 'Invalid order status selected.',
            'refund_amount.lte' => 'Refund amount cannot exceed order total.',
        ];
    }
}
