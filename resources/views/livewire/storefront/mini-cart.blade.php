<div class="relative">
    <button 
        type="button"
        wire:click="openDrawer"
        class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        
        @if($itemCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                {{ $itemCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown preview (optional) -->
    <div 
        x-data="{ open: false }"
        @mouseenter="open = true"
        @mouseleave="open = false"
        x-show="open && {{ $itemCount }} > 0"
        x-transition
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-40"
    >
        <div class="p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Items</h3>
            
            @if(count($cartItems) > 0)
                <ul class="space-y-3 max-h-60 overflow-y-auto">
                    @foreach(array_slice($cartItems, 0, 3) as $item)
                        <li class="flex gap-3">
                            <img 
                                src="{{ $item['product_image'] ?? '/images/placeholder.jpg' }}" 
                                alt="{{ $item['product_name'] }}"
                                class="w-16 h-16 object-cover rounded"
                            >
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product_name'] }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                <p class="text-sm font-semibold text-blue-600">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-sm font-semibold text-gray-900 mb-3">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <a 
                        href="{{ route('cart') }}" 
                        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                    >
                        View Cart
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
