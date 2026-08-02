@extends('layouts.storefront')

@section('title', 'Track Your Order - MaxMart')

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">Track Order</li>
            </ol>
        </div>
    </nav>

    <!-- Track Order Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Track Your Order</h1>

                <!-- Track Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-8">
                    <form action="{{ route('track-order') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Order Number</label>
                            <input type="text" 
                                   id="order_number" 
                                   name="order_number" 
                                   value="{{ request('order_number') }}"
                                   placeholder="e.g., ORD-2024-001234"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                   required>
                        </div>
                        <div class="flex-1">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ request('email') }}"
                                   placeholder="Your email address"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                   required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full md:w-auto bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                                Track Order
                            </button>
                        </div>
                    </form>
                </div>

                @if(request('order_number') && request('email'))
                    @php
                        $order = \App\Models\Order::where('order_number', request('order_number'))
                            ->where('customer_email', request('email'))
                            ->first();
                    @endphp

                    @if($order)
                        <!-- Order Found -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <!-- Order Header -->
                            <div class="bg-gray-50 px-8 py-6 border-b border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                                        <p class="text-sm text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'processing' => 'bg-purple-100 text-purple-800',
                                            'shipped' => 'bg-indigo-100 text-indigo-800',
                                            'out_for_delivery' => 'bg-orange-100 text-orange-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'confirmed' => 'Confirmed',
                                            'processing' => 'Processing',
                                            'shipped' => 'Shipped',
                                            'out_for_delivery' => 'Out for Delivery',
                                            'delivered' => 'Delivered',
                                            'cancelled' => 'Cancelled',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-3">Shipping Address</h3>
                                        <address class="not-italic text-gray-600">
                                            {{ $order->shipping_address['name'] ?? '' }}<br>
                                            {{ $order->shipping_address['address'] ?? '' }}<br>
                                            {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                                            {{ $order->shipping_address['country'] ?? '' }}<br>
                                            {{ $order->shipping_address['phone'] ?? '' }}
                                        </address>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-3">Order Summary</h3>
                                        <div class="space-y-2 text-gray-600">
                                            <div class="flex justify-between">
                                                <span>Subtotal</span>
                                                <span>${{ number_format($order->subtotal, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Shipping</span>
                                                <span>${{ number_format($order->shipping_cost, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Tax</span>
                                                <span>${{ number_format($order->tax, 2) }}</span>
                                            </div>
                                            @if($order->discount > 0)
                                                <div class="flex justify-between text-green-600">
                                                    <span>Discount</span>
                                                    <span>-${{ number_format($order->discount, 2) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex justify-between font-bold text-gray-900 pt-2 border-t">
                                                <span>Total</span>
                                                <span>${{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <h3 class="font-semibold text-gray-900 mb-4">Order Items</h3>
                                <div class="space-y-4">
                                    @foreach($order->items as $item)
                                        <div class="flex gap-4 py-4 border-b border-gray-100 last:border-0">
                                            @if($item->product)
                                                <img src="{{ $item->product->primaryImage?->url ?? asset('images/placeholder.png') }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="w-20 h-20 object-cover rounded-lg">
                                            @else
                                                <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                                @if($item->variation)
                                                    <p class="text-sm text-gray-600">{{ $item->variation }}</p>
                                                @endif
                                                <p class="text-sm text-gray-600 mt-1">Qty: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                                            </div>
                                            <p class="font-medium text-gray-900">${{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Timeline -->
                                <h3 class="font-semibold text-gray-900 mb-4 mt-8">Order Timeline</h3>
                                <div class="relative">
                                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                                    <div class="space-y-6">
                                        @php
                                            $timelineEvents = [
                                                ['status' => 'pending', 'label' => 'Order Placed', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                                ['status' => 'confirmed', 'label' => 'Order Confirmed', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                ['status' => 'processing', 'label' => 'Processing', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                ['status' => 'shipped', 'label' => 'Shipped', 'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                                                ['status' => 'out_for_delivery', 'label' => 'Out for Delivery', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                                ['status' => 'delivered', 'label' => 'Delivered', 'icon' => 'M5 13l4 4L19 7'],
                                            ];
                                            
                                            $statusOrder = ['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered'];
                                            $currentStatusIndex = array_search($order->status, $statusOrder);
                                            if ($currentStatusIndex === false) $currentStatusIndex = 0;
                                        @endphp

                                        @foreach($timelineEvents as $index => $event)
                                            @php
                                                $isActive = $index <= $currentStatusIndex;
                                                $isCurrent = $index == $currentStatusIndex;
                                            @endphp
                                            <div class="relative pl-12">
                                                <div class="absolute left-0 w-8 h-8 rounded-full flex items-center justify-center {{ $isActive ? 'bg-primary-600' : 'bg-gray-200' }}">
                                                    <svg class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $event['icon'] }}"/>
                                                    </svg>
                                                </div>
                                                <div class="{{ $isActive ? 'text-gray-900' : 'text-gray-400' }}">
                                                    <p class="font-medium">{{ $event['label'] }}</p>
                                                    @if($isActive && $order->statusHistory)
                                                        @php
                                                            $historyItem = $order->statusHistory->firstWhere('status', $event['status']);
                                                        @endphp
                                                        @if($historyItem)
                                                            <p class="text-sm">{{ $historyItem->created_at->format('M d, Y \a\t h:i A') }}</p>
                                                            @if($historyItem->notes && $isCurrent)
                                                                <p class="text-sm text-gray-600 mt-1">{{ $historyItem->notes }}</p>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Order Not Found -->
                        <div class="bg-red-50 border border-red-200 rounded-xl p-8 text-center">
                            <svg class="mx-auto h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-4 text-xl font-medium text-red-900">Order Not Found</h3>
                            <p class="mt-2 text-red-700">We couldn't find an order with that order number and email address. Please check your information and try again.</p>
                        </div>
                    @endif
                @else
                    <!-- Initial State -->
                    <div class="bg-gray-50 rounded-xl p-8 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-4 text-xl font-medium text-gray-900">Enter Your Order Details</h3>
                        <p class="mt-2 text-gray-600">You can find your order number in the confirmation email we sent you.</p>
                    </div>
                @endif

                <!-- Help Section -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600">Need help? <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">Contact our support team</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
