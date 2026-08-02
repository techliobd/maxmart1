<x-admin-layout>
    <x-slot name="title">Edit Order #{{ $order->order_number }}</x-slot>
    <x-slot name="subtitle">Update Order Details</x-slot>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="order_number" class="block text-sm font-medium text-gray-700">Order Number</label>
                            <input type="text" name="order_number" id="order_number" value="{{ old('order_number', $order->order_number) }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                        </div>
                        <div>
                            <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date</label>
                            <input type="text" value="{{ $order->created_at->format('M d, Y H:i') }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                        </div>
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                            <a href="{{ route('admin.customers.show', $order->customer) }}" class="mt-1 block text-primary hover:text-primary-dark font-medium">
                                {{ $order->customer_name }} ({{ $order->customer_email }})
                            </a>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Current Status</label>
                            <span class="mt-1 inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping Address</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Street Address *</label>
                            <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address', $order->shipping_address) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_address') border-red-500 @enderror">
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city', $order->shipping_city) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_city') border-red-500 @enderror">
                            @error('shipping_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state', $order->shipping_state) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_state') border-red-500 @enderror">
                            @error('shipping_state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code', $order->shipping_postal_code) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_postal_code') border-red-500 @enderror">
                            @error('shipping_postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country *</label>
                            <select name="shipping_country" id="shipping_country" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_country') border-red-500 @enderror">
                                @foreach(config('countries', ['BD' => 'Bangladesh', 'US' => 'United States', 'GB' => 'United Kingdom', 'IN' => 'India']) as $code => $name)
                                    <option value="{{ $code }}" {{ old('shipping_country', $order->shipping_country) === $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('shipping_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                            <input type="text" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone', $order->shipping_phone) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_phone') border-red-500 @enderror">
                            @error('shipping_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Billing Address</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700">Street Address *</label>
                            <input type="text" name="billing_address" id="billing_address" value="{{ old('billing_address', $order->billing_address) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_address') border-red-500 @enderror">
                            @error('billing_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="billing_city" id="billing_city" value="{{ old('billing_city', $order->billing_city) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_city') border-red-500 @enderror">
                            @error('billing_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="billing_state" id="billing_state" value="{{ old('billing_state', $order->billing_state) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_state') border-red-500 @enderror">
                            @error('billing_state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="billing_postal_code" id="billing_postal_code" value="{{ old('billing_postal_code', $order->billing_postal_code) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_postal_code') border-red-500 @enderror">
                            @error('billing_postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700">Country *</label>
                            <select name="billing_country" id="billing_country" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_country') border-red-500 @enderror">
                                @foreach(config('countries', ['BD' => 'Bangladesh', 'US' => 'United States', 'GB' => 'United Kingdom', 'IN' => 'India']) as $code => $name)
                                    <option value="{{ $code }}" {{ old('billing_country', $order->billing_country) === $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('billing_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                            @if($item->variation)
                                                <div class="text-sm text-gray-500">{{ $item->variation }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900">{{ number_format($item->price, 2) }} {{ defaultCurrency()->code }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <input type="number" name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}" min="1"
                                                class="w-20 text-center rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                            {{ number_format($item->price * $item->quantity, 2) }} {{ defaultCurrency()->code }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Notes</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="customer_notes" class="block text-sm font-medium text-gray-700">Customer Notes</label>
                            <textarea name="customer_notes" id="customer_notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('customer_notes') border-red-500 @enderror">{{ old('customer_notes', $order->customer_notes) }}</textarea>
                            @error('customer_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="internal_notes" class="block text-sm font-medium text-gray-700">Internal Notes</label>
                            <textarea name="internal_notes" id="internal_notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('internal_notes') border-red-500 @enderror">{{ old('internal_notes', $order->internal_notes) }}</textarea>
                            @error('internal_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                            <dd class="text-gray-900">{{ number_format($order->subtotal, 2) }} {{ defaultCurrency()->code }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Shipping</dt>
                            <dd>
                                <input type="number" name="shipping_cost" step="0.01" min="0" value="{{ old('shipping_cost', $order->shipping_cost) }}"
                                    class="w-24 text-right rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Tax</dt>
                            <dd>
                                <input type="number" name="tax_amount" step="0.01" min="0" value="{{ old('tax_amount', $order->tax_amount) }}"
                                    class="w-24 text-right rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Discount</dt>
                            <dd>
                                <input type="number" name="discount_amount" step="0.01" min="0" value="{{ old('discount_amount', $order->discount_amount) }}"
                                    class="w-24 text-right rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            </dd>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between">
                                <dt class="text-base font-semibold text-gray-800">Total</dt>
                                <dd class="text-xl font-bold text-primary">{{ number_format($order->total, 2) }} {{ defaultCurrency()->code }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <!-- Payment & Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment & Status</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="payment_method" id="payment_method"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">-- Select Method --</option>
                                <option value="cod" {{ old('payment_method', $order->payment_method) === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                                <option value="bank_transfer" {{ old('payment_method', $order->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="stripe" {{ old('payment_method', $order->payment_method) === 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="paypal" {{ old('payment_method', $order->payment_method) === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="sslcommerz" {{ old('payment_method', $order->payment_method) === 'sslcommerz' ? 'selected' : '' }}>SSLCommerz</option>
                                <option value="bkash" {{ old('payment_method', $order->payment_method) === 'bkash' ? 'selected' : '' }}>bKash</option>
                                <option value="nagad" {{ old('payment_method', $order->payment_method) === 'nagad' ? 'selected' : '' }}>Nagad</option>
                            </select>
                        </div>
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status *</label>
                            <select name="payment_status" id="payment_status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('payment_status') border-red-500 @enderror">
                                <option value="pending" {{ old('payment_status', $order->payment_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status', $order->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ old('payment_status', $order->payment_status) === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="refunded" {{ old('payment_status', $order->payment_status) === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                <option value="failed" {{ old('payment_status', $order->payment_status) === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="order_status" class="block text-sm font-medium text-gray-700">Order Status *</label>
                            <select name="order_status" id="order_status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('order_status') border-red-500 @enderror">
                                <option value="pending" {{ old('order_status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('order_status', $order->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ old('order_status', $order->status) === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ old('order_status', $order->status) === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ old('order_status', $order->status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ old('order_status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ old('order_status', $order->status) === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('order_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full btn-primary justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Order
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" class="w-full btn-secondary justify-center">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status History</h3>
                    <div class="space-y-3">
                        @foreach($order->statusHistory ?? [] as $history)
                            <div class="flex items-start gap-2">
                                <div class="w-2 h-2 mt-1.5 rounded-full bg-primary"></div>
                                <div>
                                    <p class="text-sm text-gray-900">{{ ucfirst($history['status'] ?? 'Unknown') }}</p>
                                    <p class="text-xs text-gray-500">{{ $history['date'] ?? 'Unknown date' }}</p>
                                </div>
                            </div>
                        @endforeach
                        @if(empty($order->statusHistory))
                            <p class="text-sm text-gray-500">No status history available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
