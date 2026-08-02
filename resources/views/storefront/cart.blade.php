@extends('layouts.storefront')

@section('title', 'Shopping Cart - MaxMart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Cart Items --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach(session('cart') as $itemId => $item)
                            <li class="p-6 flex items-center space-x-4">
                                {{-- Product Image --}}
                                <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="{{ $item['image'] ?? asset('images/placeholder.jpg') }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="w-full h-full object-cover">
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-medium text-gray-900 truncate">
                                        {{ $item['name'] }}
                                    </h3>
                                    @if(isset($item['variation']))
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ implode(', ', $item['variation']) }}
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-500 mt-1">
                                        Price: {{ $item['price'] }}
                                    </p>
                                </div>

                                {{-- Quantity Controls --}}
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button wire:click="updateQuantity({{ $itemId }}, {{ $item['quantity'] - 1 }})" 
                                            @disabled($item['quantity'] <= 1)
                                            class="px-3 py-2 text-gray-600 hover:bg-gray-100 disabled:opacity-50">
                                        -
                                    </button>
                                    <span class="px-4 py-2 text-gray-900 font-medium w-12 text-center">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button wire:click="updateQuantity({{ $itemId }}, {{ $item['quantity'] + 1 }})" 
                                            class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                        +
                                    </button>
                                </div>

                                {{-- Subtotal --}}
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </p>
                                </div>

                                {{-- Remove Button --}}
                                <button wire:click="removeItem({{ $itemId }})" 
                                        class="text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Continue Shopping --}}
                <div class="mt-6">
                    <a href="{{ route('shop') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">{{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if(isset($discount) && $discount > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Discount</span>
                                <span class="font-medium">-{{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-base font-semibold">
                                <span>Total</span>
                                <span>{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Coupon Code --}}
                    <div class="mb-6">
                        <form wire:submit="applyCoupon" class="flex space-x-2">
                            <input type="text" wire:model="couponCode" 
                                   placeholder="Coupon code" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <button type="submit" 
                                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                                Apply
                            </button>
                        </form>
                        @error('couponCode')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        @if(session('coupon_message'))
                            <p class="mt-1 text-xs text-green-600">{{ session('coupon_message') }}</p>
                        @endif
                    </div>

                    {{-- Checkout Button --}}
                    <a href="{{ route('checkout') }}" 
                       class="block w-full bg-blue-600 text-white text-center px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Proceed to Checkout
                    </a>

                    {{-- Trust Badges --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Secure
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Fast Shipping
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-8">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('shop') }}" 
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
