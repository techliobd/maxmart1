@extends('layouts.storefront')

@section('title', $product->name . ' - MaxMart')
@section('meta_description', $product->meta_description ?? Str::limit(strip_tags($product->description), 160))

@section('og_tags')
<meta property="og:title" content="{{ $product->name }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 160) }}">
<meta property="og:image" content="{{ $product->images->first()?->url ?? asset('images/placeholder.jpg') }}">
<meta property="og:type" content="product">
<meta property="og:price:amount" content="{{ $product->price }}">
<meta property="og:price:currency" content="{{ session('currency', 'USD') }}">
@endsection

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "image": "{{ $product->images->first()?->url ?? asset('images/placeholder.jpg') }}",
    "description": "{{ Str::limit(strip_tags($product->description), 160) }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ $product->brand?->name }}"
    },
    "offers": {
        "@type": "Offer",
        "price": "{{ $product->price }}",
        "priceCurrency": "{{ session('currency', 'USD') }}",
        "availability": "{{ $product->inStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    }
}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
        <span>/</span>
        <a href="{{ route('shop') }}" class="hover:text-blue-600">Shop</a>
        @if($product->category)
            <span>/</span>
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-blue-600">{{ $product->category->name }}</a>
        @endif
        <span>/</span>
        <span class="text-gray-900">{{ $product->name }}</span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-8">
        {{-- Product Images --}}
        <div>
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4">
                <img id="mainImage" 
                     src="{{ $product->images->first()?->url ?? asset('images/placeholder.jpg') }}" 
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </div>
            @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                        <button onclick="document.getElementById('mainImage').src = '{{ $image->url }}'" 
                                class="aspect-square bg-gray-100 rounded overflow-hidden hover:ring-2 hover:ring-blue-500 transition-shadow">
                            <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            {{-- Brand --}}
            @if($product->brand)
                <a href="{{ route('shop', ['brand' => $product->brand->slug]) }}" 
                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    {{ $product->brand->name }}
                </a>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl font-bold text-gray-900 mt-2 mb-4">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="flex items-center mb-4">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= round($product->averageRating()) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="ml-2 text-sm text-gray-600">
                    {{ number_format($product->averageRating(), 1) }} ({{ $product->reviews_count ?? 0 }} reviews)
                </span>
            </div>

            {{-- Price --}}
            <div class="mb-6">
                @if($product->isOnSale())
                    <div class="flex items-center space-x-3">
                        <span class="text-3xl font-bold text-red-600">{{ $product->sale_price }}</span>
                        <span class="text-xl text-gray-400 line-through">{{ $product->price }}</span>
                        <span class="bg-red-100 text-red-600 text-sm font-semibold px-2 py-1 rounded">
                            Save {{ $product->discountPercentage() }}%
                        </span>
                    </div>
                @else
                    <span class="text-3xl font-bold text-gray-900">{{ $product->price }}</span>
                @endif
            </div>

            {{-- Stock Status --}}
            <div class="mb-6">
                @if($product->inStock())
                    <span class="inline-flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        In Stock
                        @if($product->stock_quantity < 10)
                            <span class="ml-2 text-sm">(Only {{ $product->stock_quantity }} left)</span>
                        @endif
                    </span>
                @else
                    <span class="inline-flex items-center text-red-600">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 0L2 12.586l1.414 1.414L8.707 8.707l5.293 5.293a1 1 0 001.414-1.414L8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Out of Stock
                    </span>
                @endif
            </div>

            {{-- Description --}}
            <div class="prose prose-sm max-w-none mb-6">
                {!! $product->short_description ?? Str::limit($product->description, 300) !!}
            </div>

            {{-- Variations Selector --}}
            @if($product->hasVariations())
                <livewire:product-variation-selector :product="$product" />
            @endif

            {{-- Add to Cart --}}
            <div class="flex space-x-4 mb-6">
                <div class="flex items-center border border-gray-300 rounded-lg">
                    <button wire:click="decrementQuantity" class="px-4 py-3 text-gray-600 hover:bg-gray-100">-</button>
                    <span class="px-4 py-3 text-gray-900 font-medium w-16 text-center">{{ $quantity }}</span>
                    <button wire:click="incrementQuantity" class="px-4 py-3 text-gray-600 hover:bg-gray-100">+</button>
                </div>
                <button wire:click="addToCart" 
                        @disabled(!$product->inStock())
                        class="flex-1 bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Add to Cart
                </button>
                <button wire:click="addToWishlist" 
                        class="p-3 border border-gray-300 rounded-lg hover:border-red-500 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>

            {{-- Meta Info --}}
            <div class="border-t border-gray-200 pt-6 space-y-3 text-sm">
                @if($product->sku)
                    <div class="flex">
                        <span class="text-gray-500 w-24">SKU:</span>
                        <span class="text-gray-900">{{ $product->sku }}</span>
                    </div>
                @endif
                @if($product->category)
                    <div class="flex">
                        <span class="text-gray-500 w-24">Category:</span>
                        <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="text-blue-600 hover:text-blue-700">{{ $product->category->name }}</a>
                    </div>
                @endif
                @if($product->tags && $product->tags->count() > 0)
                    <div class="flex">
                        <span class="text-gray-500 w-24">Tags:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->tags as $tag)
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabs Section --}}
    <div class="mt-16">
        <div x-data="{ tab: 'description' }" class="border-b border-gray-200">
            <nav class="flex space-x-8">
                <button @click="tab = 'description'" 
                        :class="tab === 'description' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Description
                </button>
                <button @click="tab = 'specifications'" 
                        :class="tab === 'specifications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Specifications
                </button>
                <button @click="tab = 'reviews'" 
                        :class="tab === 'reviews' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Reviews ({{ $product->reviews_count ?? 0 }})
                </button>
            </nav>
        </div>

        <div class="py-8">
            {{-- Description Tab --}}
            <div x-show="tab === 'description'" class="prose max-w-none">
                {!! $product->description !!}
            </div>

            {{-- Specifications Tab --}}
            <div x-show="tab === 'specifications'" class="hidden">
                @if($product->attributes->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            @foreach($product->attributes as $attribute)
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-900 w-1/3">{{ $attribute->name }}</td>
                                    <td class="py-3 text-sm text-gray-600">{{ $attribute->pivot->value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No specifications available.</p>
                @endif
            </div>

            {{-- Reviews Tab --}}
            <div x-show="tab === 'reviews'" class="hidden">
                <livewire:review-form :product="$product" />
                <div class="mt-8 space-y-6">
                    @forelse($product->reviews ?? [] as $review)
                        <div class="border-b border-gray-200 pb-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr($review->customer?->name ?? 'C', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">{{ $review->customer?->name ?? 'Customer' }}</p>
                                        <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-700">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <x-product-card :product="$relatedProduct" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
