<x-layout-storefront>
    <x-slot name="title">My Orders</x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
                <p class="text-sm text-gray-500 mt-1">Track and manage all your orders</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-6 pb-6 border-b border-gray-200">
            <form method="GET" action="{{ route('customer.orders') }}" class="flex-1 min-w-[200px]">
                <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->slug }}" {{ request('status') == $status->slug ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <form method="GET" action="{{ route('customer.orders') }}" class="flex-1 min-w-[200px]">
                <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>Highest Price</option>
                    <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>Lowest Price</option>
                </select>
            </form>
        </div>

        <!-- Orders List -->
        <div class="space-y-4">
            @forelse($orders as $order)
                @php
                    $statusColors = [
                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                        'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                        'shipped' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                        'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                    ];
                    $statusColor = $statusColors[$order->status->slug] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                @endphp
                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Order Header -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <span class="font-semibold text-gray-900">Order #{{ $order->order_number }}</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                                {{ $order->status->name }}
                            </span>
                        </div>
                        <div class="flex items-center gap-6 text-sm">
                            <span class="text-gray-500">Placed: {{ $order->created_at->format('M d, Y') }}</span>
                            <span class="font-semibold text-gray-900">{{ formatCurrency($order->grand_total) }}</span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item->product->images->first())
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 truncate">{{ $item->product->name }}</h4>
                                        @if($item->variation)
                                            <p class="text-sm text-gray-500">
                                                @foreach($item->variation->attributeValues as $value)
                                                    <span class="inline-block bg-gray-100 px-2 py-0.5 rounded text-xs mr-1">
                                                        {{ $value->attribute->name }}: {{ $value->value }}
                                                    </span>
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">{{ formatCurrency($item->unit_price) }}</p>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-wrap items-center justify-between gap-4">
                        <div class="text-sm text-gray-500">
                            <span class="font-medium">Shipping:</span> {{ $order->shipping_address->full_address ?? 'N/A' }}
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('track.order', $order->order_number) }}" 
                               class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                                Track Order
                            </a>
                            @if($order->status->slug === 'delivered')
                                <a href="{{ route('reviews.create', $order->id) }}" 
                                   class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                    Write Review
                                </a>
                            @endif
                            <a href="{{ route('order.detail', $order->order_number) }}" 
                               class="px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                    <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('shop') }}" class="inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Start Shopping
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layout-storefront>
