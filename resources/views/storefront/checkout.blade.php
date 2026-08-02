@extends('layouts.storefront')
@section('title', 'Checkout - MaxMart')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>
    <form action="{{ route('checkout.process') }}" method="POST" class="grid lg:grid-cols-3 gap-8">
        @csrf
        <div class="lg:col-span-2 space-y-8">
            {{-- Contact Info --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
                <x-form-input label="Email Address" name="email" type="email" required placeholder="you@example.com" />
                <x-form-input label="Phone Number" name="phone" type="tel" required placeholder="+1 (555) 000-0000" />
            </div>
            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4">Shipping Address</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <x-form-input label="First Name" name="first_name" required />
                    <x-form-input label="Last Name" name="last_name" required />
                </div>
                <x-form-input label="Address" name="address" required placeholder="123 Main St" />
                <x-form-input label="Apartment, suite, etc. (optional)" name="address2" />
                <div class="grid md:grid-cols-3 gap-4">
                    <x-form-input label="City" name="city" required />
                    <x-form-input label="State/Province" name="state" required />
                    <x-form-input label="Postal Code" name="postal_code" required />
                </div>
                <x-form-input label="Country" name="country" type="select" required>
                    <option value="">Select Country</option>
                    <option value="US">United States</option>
                    <option value="CA">Canada</option>
                    <option value="GB">United Kingdom</option>
                    <option value="AU">Australia</option>
                </x-form-input>
            </div>
            {{-- Payment Method --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-500">
                        <input type="radio" name="payment_method" value="stripe" class="text-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-3 font-medium">Credit Card (Stripe)</span>
                    </label>
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-500">
                        <input type="radio" name="payment_method" value="paypal" class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-3 font-medium">PayPal</span>
                    </label>
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-500">
                        <input type="radio" name="payment_method" value="cod" class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-3 font-medium">Cash on Delivery</span>
                    </label>
                </div>
            </div>
        </div>
        {{-- Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                    @foreach(session('cart', []) as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                            <span>{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format($subtotal ?? 0, 2) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>{{ number_format($shipping ?? 5.99, 2) }}</span></div>
                    <div class="flex justify-between text-lg font-bold"><span>Total</span><span>{{ number_format(($subtotal ?? 0) + ($shipping ?? 5.99), 2) }}</span></div>
                </div>
                <button type="submit" class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">Place Order</button>
            </div>
        </div>
    </form>
</div>
@endsection
