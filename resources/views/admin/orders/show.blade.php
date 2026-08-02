<x-admin-layout>
    <x-slot name="title">Order #{{ $order->order_number }}</x-slot>
    <x-slot name="subtitle">Order details and management</x-slot>

    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-2xl font-bold text-gray-900">#{{ $order->order_number }}</span>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-gray-100 text-gray-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    ← Back to Orders
                </a>
                <button type="button" onclick="window.print()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->product && $item->product->images->first())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded mr-3">
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                                                @if($item->variation_options)
                                                    <p class="text-xs text-gray-500">{{ $item->variation_options }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->product_sku ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal:</td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Discount:</td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-red-600">-${{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Shipping:</td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tax:</td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($order->tax_amount, 2) }}</td>
                                </tr>
                                <tr class="border-t border-gray-200">
                                    <td colspan="4" class="px-6 py-4 text-right text-base font-bold text-gray-900">Total:</td>
                                    <td class="px-6 py-4 text-right text-base font-bold text-blue-600">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Timeline / Activity -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Order Timeline</h3>
                    </div>
                    <div class="p-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @php
                                    $timelineEvents = [
                                        ['status' => 'pending', 'label' => 'Order Placed', 'icon' => 'document-text'],
                                        ['status' => 'processing', 'label' => 'Processing', 'icon' => 'cog'],
                                        ['status' => 'shipped', 'label' => 'Shipped', 'icon' => 'truck'],
                                        ['status' => 'delivered', 'label' => 'Delivered', 'icon' => 'check-circle'],
                                    ];
                                    $currentStatusIndex = array_search($order->status, array_column($timelineEvents, 'status'));
                                    if ($currentStatusIndex === false) $currentStatusIndex = -1;
                                @endphp
                                @foreach($timelineEvents as $index => $event)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $index <= $currentStatusIndex ? 'bg-blue-500' : 'bg-gray-200' }}">
                                                    <svg class="h-5 w-5 {{ $index <= $currentStatusIndex ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">{{ $event['label'] }}</p>
                                                </div>
                                                @if($index <= $currentStatusIndex)
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time>{{ $order->created_at->format('M d, Y H:i') }}</time>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer</h3>
                    @if($order->customer)
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->customer->email }}</p>
                            </div>
                        </div>
                        @if($order->customer->phone)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <p class="text-sm text-gray-900">{{ $order->customer->phone }}</p>
                        </div>
                        @endif
                        <a href="{{ route('admin.customers.show', $order->customer) }}" class="block text-sm text-blue-600 hover:text-blue-900 mt-2">View Customer Profile →</a>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">Guest Checkout</p>
                    </div>
                    @endif
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                    <address class="not-italic text-sm text-gray-600 space-y-1">
                        <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        @if($order->shipping_address2)
                        <p>{{ $order->shipping_address2 }}</p>
                        @endif
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
                        <p>{{ $order->shipping_country }}</p>
                        @if($order->shipping_phone)
                        <p class="mt-2">{{ $order->shipping_phone }}</p>
                        @endif
                    </address>
                </div>

                <!-- Billing Address -->
                @if($order->billing_address && $order->billing_address !== $order->shipping_address)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h3>
                    <address class="not-italic text-sm text-gray-600 space-y-1">
                        <p class="font-medium text-gray-900">{{ $order->billing_name }}</p>
                        <p>{{ $order->billing_address }}</p>
                        @if($order->billing_address2)
                        <p>{{ $order->billing_address2 }}</p>
                        @endif
                        <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}</p>
                        <p>{{ $order->billing_country }}</p>
                    </address>
                </div>
                @endif

                <!-- Payment Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Payment Method</p>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Payment Status</p>
                            <p class="text-sm">
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </p>
                        </div>
                        @if($order->transaction_id)
                        <div>
                            <p class="text-xs text-gray-500">Transaction ID</p>
                            <p class="text-sm font-mono text-gray-900">{{ $order->transaction_id }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Meta -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Order Date:</span>
                            <span class="text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Last Updated:</span>
                            <span class="text-gray-900">{{ $order->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">IP Address:</span>
                            <span class="text-gray-900">{{ $order->ip_address ?? 'N/A' }}</span>
                        </div>
                        @if($order->coupon_code)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Coupon:</span>
                            <span class="text-gray-900 font-medium">{{ $order->coupon_code }}</span>
                        </div>
                        @endif
                        @if($order->notes)
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-1">Customer Notes:</p>
                            <p class="text-sm text-gray-900">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
