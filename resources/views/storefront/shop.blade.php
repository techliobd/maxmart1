@extends('layouts.storefront')

@section('title', 'Shop - MaxMart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Filters Sidebar --}}
        <aside class="lg:w-64 flex-shrink-0">
            <livewire:product-filter />
        </aside>

        {{-- Products Grid --}}
        <div class="flex-1">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Shop</h1>
                    <p class="text-gray-600 text-sm mt-1">
                        {{ $products->total() }} products found
                        @if(request('category'))
                            in <span class="font-medium">{{ $currentCategory?->name }}</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <select wire:model.live="sort" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="featured">Featured</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="newest">Newest First</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
            </div>

            {{-- Products --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-600">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('shop') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-700 font-medium">Clear all filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
