@extends('layouts.storefront')

@section('title', 'Order Confirmation - MaxMart')

@section('content')
    <!-- Order Success Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                @if(session('order'))
                    @php
                        $order = session('order');
                    @endphp
                    
                    <!-- Success Message -->
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Thank You for Your Order!</h1>
                        <p class="text-lg text-gray-600 mb-2">Your order has been placed successfully.</p>
                        <p class="text-gray-600">Order Number: <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span></p>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                        <!-- Header -->
                        <div class="bg-gray-50 px-8 py-6 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                                    <p class="text-sm text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                                </div>
                                <div class="flex gap-3">
                                    <a href="{{ route('track-order', ['order_number' => $order->order_number, 'email' => $order->customer_email]) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Track Order
                                    </a>
                                    <a href="{{ route('shop') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Shipping Address -->
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Shipping Address
                                    </h3>
                                    <address class="not-italic text-gray-600 bg-gray-50 rounded-lg p-4">
                                        <p class="font-medium text-gray-900">{{ $order->shipping_address['name'] ?? '' }}</p>
                                        <p>{{ $order->shipping_address['address'] ?? '' }}</p>
                                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                                        <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                                        <p class="mt-2">{{ $order->shipping_address['phone'] ?? '' }}</p>
                                        <p>{{ $order->customer_email }}</p>
                                    </address>
                                </div>

                                <!-- Payment Info -->
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Payment Information
                                    </h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Payment Method</span>
                                                <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Payment Status</span>
                                                @php
                                                    $paymentStatusColors = [
                                                        'pending' => 'text-yellow-600',
                                                        'paid' => 'text-green-600',
                                                        'failed' => 'text-red-600',
                                                        'refunded' => 'text-blue-600',
                                                    ];
                                                @endphp
                                                <span class="font-medium {{ $paymentStatusColors[$order->payment_status] ?? 'text-gray-600' }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </div>
                                            @if($order->transaction_id)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Transaction ID</span>
                                                    <span class="font-mono text-sm text-gray-900">{{ $order->transaction_id }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <h3 class="font-semibold text-gray-900 mb-4">Order Items</h3>
                            <div class="border rounded-lg overflow-hidden mb-8">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Product</th>
                                            <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Quantity</th>
                                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Price</th>
                                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td class="py-4 px-4">
                                                    <div class="flex items-center gap-3">
                                                        @if($item->product)
                                                            <img src="{{ $item->product->primaryImage?->url ?? asset('images/placeholder.png') }}" 
                                                                 alt="{{ $item->product_name }}" 
                                                                 class="w-16 h-16 object-cover rounded-lg">
                                                        @else
                                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                                            @if($item->variation)
                                                                <p class="text-sm text-gray-600">{{ $item->variation }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4 text-center text-gray-700">{{ $item->quantity }}</td>
                                                <td class="py-4 px-4 text-right text-gray-700">${{ number_format($item->unit_price, 2) }}</td>
                                                <td class="py-4 px-4 text-right font-medium text-gray-900">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="py-3 px-4 text-right text-gray-600">Subtotal</td>
                                            <td class="py-3 px-4 text-right font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="py-3 px-4 text-right text-gray-600">Shipping</td>
                                            <td class="py-3 px-4 text-right font-medium text-gray-900">${{ number_format($order->shipping_cost, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="py-3 px-4 text-right text-gray-600">Tax</td>
                                            <td class="py-3 px-4 text-right font-medium text-gray-900">${{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                        @if($order->discount > 0)
                                            <tr>
                                                <td colspan="3" class="py-3 px-4 text-right text-green-600">Discount</td>
                                                <td class="py-3 px-4 text-right font-medium text-green-600">-${{ number_format($order->discount, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="border-t">
                                            <td colspan="3" class="py-4 px-4 text-right font-bold text-gray-900">Total</td>
                                            <td class="py-4 px-4 text-right font-bold text-primary-600 text-lg">${{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- What's Next -->
                            <div class="bg-blue-50 rounded-lg p-6">
                                <h3 class="font-semibold text-gray-900 mb-3">What's Next?</h3>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-200 text-blue-700 rounded-full text-sm font-semibold mr-3 flex-shrink-0">1</span>
                                        <span>You'll receive an email confirmation shortly with your order details.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-200 text-blue-700 rounded-full text-sm font-semibold mr-3 flex-shrink-0">2</span>
                                        <span>We'll send you another email when your order ships with tracking information.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-200 text-blue-700 rounded-full text-sm font-semibold mr-3 flex-shrink-0">3</span>
                                        <span>You can track your order anytime using the tracking link above.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Need Help -->
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">Have questions about your order?</p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('contact') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Contact Support
                            </a>
                            @auth
                                <a href="{{ route('customer.orders') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    View All Orders
                                </a>
                            @endauth
                        </div>
                    </div>
                @else
                    <!-- No Order in Session -->
                    <div class="text-center py-16">
                        <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <h2 class="mt-4 text-2xl font-bold text-gray-900">No Order Found</h2>
                        <p class="mt-2 text-gray-600">It looks like you haven't placed an order yet.</p>
                        <a href="{{ route('shop') }}" class="mt-6 inline-block bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                            Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
