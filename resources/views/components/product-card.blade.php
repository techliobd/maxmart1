@props(['product'])

<div class="group bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow overflow-hidden">
    {{-- Product Image --}}
    <div class="relative aspect-square overflow-hidden bg-gray-100">
        <a href="{{ route('product.show', $product->slug) }}">
            <img src="{{ $product->images->first()?->url ?? asset('images/placeholder.jpg') }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </a>
        
        {{-- Badges --}}
        @if($product->isOnSale())
            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">
                Sale
            </span>
        @endif
        @if($product->isNew())
            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                New
            </span>
        @endif
        
        {{-- Quick Actions --}}
        <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            <div class="flex justify-center space-x-2">
                <button wire:click="$dispatch('addToCart', { productId: {{ $product->id }} })" 
                        class="bg-white text-gray-900 px-4 py-2 rounded-full text-sm font-medium hover:bg-blue-600 hover:text-white transition-colors">
                    Add to Cart
                </button>
                <button wire:click="$dispatch('addToWishlist', { productId: {{ $product->id }} })" 
                        class="bg-white text-gray-900 p-2 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Product Info --}}
    <div class="p-4">
        {{-- Category --}}
        @if($product->category)
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}" 
               class="text-xs text-gray-500 hover:text-blue-600">
                {{ $product->category->name }}
            </a>
        @endif

        {{-- Name --}}
        <h3 class="mt-1 text-base font-medium text-gray-900 line-clamp-2">
            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-blue-600">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Rating --}}
        @if($product->averageRating() > 0)
            <div class="mt-1 flex items-center">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($product->averageRating()) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="ml-1 text-xs text-gray-500">({{ $product->reviews_count ?? 0 }})</span>
            </div>
        @endif

        {{-- Price --}}
        <div class="mt-2 flex items-center space-x-2">
            @if($product->isOnSale())
                <span class="text-lg font-bold text-red-600">{{ $product->old_price ?? $product->price }}</span>
                <span class="text-sm text-gray-400 line-through">{{ $product->price }}</span>
            @else
                <span class="text-lg font-bold text-gray-900">{{ $product->price }}</span>
            @endif
        </div>

        {{-- Stock Status --}}
        @if(!$product->inStock())
            <span class="mt-1 text-xs text-red-500 font-medium">Out of Stock</span>
        @elseif($product->stock_quantity < 10)
            <span class="mt-1 text-xs text-orange-500 font-medium">Only {{ $product->stock_quantity }} left</span>
        @endif
    </div>
</div>
