<div 
    x-data="{ 
        open: @entangle('isOpen'),
        cartItems: @entangle('cartItems'),
        subtotal: @entangle('subtotal'),
        tax: @entangle('tax'),
        total: @entangle('total')
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-hidden"
    aria-labelledby="slide-over-title" 
    role="dialog" 
    aria-modal="true"
>
    <div class="absolute inset-0 overflow-hidden">
        <!-- Background overlay -->
        <div 
            x-show="open"
            x-transition:enter="ease-in-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="close()"
        ></div>

        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
            <!-- Slide-over panel -->
            <div 
                x-show="open"
                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="pointer-events-auto w-screen max-w-md"
            >
                <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-4 py-6 sm:px-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900" id="slide-over-title">Shopping Cart</h2>
                        <button 
                            type="button" 
                            @click="close()"
                            class="text-gray-400 hover:text-gray-500"
                        >
                            <span class="sr-only">Close panel</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                        @if(count($cartItems) > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($cartItems as $item)
                                    <li class="flex py-6">
                                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            <img 
                                                src="{{ $item['product_image'] ?? '/images/placeholder.jpg' }}" 
                                                alt="{{ $item['product_name'] }}"
                                                class="h-full w-full object-cover object-center"
                                            >
                                        </div>

                                        <div class="ml-4 flex flex-1 flex-col">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900">
                                                    <h3>{{ $item['product_name'] }}</h3>
                                                    <p class="ml-4">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                                </div>
                                                @if(isset($item['variation_name']) && $item['variation_name'])
                                                    <p class="mt-1 text-sm text-gray-500">{{ $item['variation_name'] }}</p>
                                                @endif
                                            </div>

                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                <div class="flex items-center border border-gray-300 rounded">
                                                    <button 
                                                        type="button"
                                                        wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                                        class="px-2 py-1 hover:bg-gray-100"
                                                    >
                                                        -
                                                    </button>
                                                    <span class="px-3 py-1">{{ $item['quantity'] }}</span>
                                                    <button 
                                                        type="button"
                                                        wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                                        class="px-2 py-1 hover:bg-gray-100"
                                                    >
                                                        +
                                                    </button>
                                                </div>

                                                <button 
                                                    type="button"
                                                    wire:click="removeItem({{ $item['id'] }})"
                                                    class="font-medium text-red-600 hover:text-red-500"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                                <p class="mt-1 text-sm text-gray-500">Start shopping to add items to your cart.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    @if(count($cartItems) > 0)
                        <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                            <div class="flex justify-between text-base font-medium text-gray-900">
                                <p>Subtotal</p>
                                <p>${{ number_format($subtotal, 2) }}</p>
                            </div>
                            @if($tax > 0)
                                <div class="flex justify-between text-sm text-gray-600 mt-2">
                                    <p>Tax</p>
                                    <p>${{ number_format($tax, 2) }}</p>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg font-bold text-gray-900 mt-4">
                                <p>Total</p>
                                <p>${{ number_format($total, 2) }}</p>
                            </div>

                            <div class="mt-6">
                                <a 
                                    href="{{ route('checkout') }}" 
                                    class="flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700"
                                >
                                    Checkout
                                </a>
                            </div>

                            <div class="mt-4 flex justify-center">
                                <button 
                                    type="button"
                                    wire:click="clearCart"
                                    class="text-sm text-red-600 hover:text-red-500"
                                >
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
