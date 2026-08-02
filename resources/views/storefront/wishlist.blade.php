@extends('layouts.storefront')

@section('title', 'Wishlist - MaxMart')

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">Wishlist</li>
            </ol>
        </div>
    </nav>

    <!-- Wishlist Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">My Wishlist</h1>

            @if(auth()->check() && auth()->user()->wishlist->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach(auth()->user()->wishlist as $wishlistItem)
                        @php
                            $product = $wishlistItem->product;
                        @endphp
                        <livewire:product-card :product="$product" :key="'wishlist-' . $product->id" />
                    @endforeach
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <p class="text-gray-600">{{ auth()->user()->wishlist->count() }} item(s) in your wishlist</p>
                    <a href="{{ route('shop') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        Continue Shopping →
                    </a>
                </div>
            @elseif(session('wishlist_items'))
                <!-- Guest wishlist from session -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach(session('wishlist_items', []) as $productId)
                        @php
                            $product = \App\Models\Product::find($productId);
                        @endphp
                        @if($product)
                            <livewire:product-card :product="$product" :key="'session-wishlist-' . $product->id" />
                        @endif
                    @endforeach
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <p class="text-gray-600">{{ count(session('wishlist_items', [])) }} item(s) in your wishlist</p>
                    <a href="{{ route('shop') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        Continue Shopping →
                    </a>
                </div>

                @guest
                    <div class="mt-6 p-4 bg-primary-50 rounded-lg">
                        <p class="text-primary-800">
                            <a href="{{ route('login') }}" class="font-medium underline">Login</a> or 
                            <a href="{{ route('register') }}" class="font-medium underline">Register</a> to save your wishlist permanently.
                        </p>
                    </div>
                @endguest
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">Your wishlist is empty</h3>
                    <p class="mt-2 text-gray-600">Save your favorite products here for later.</p>
                    <a href="{{ route('shop') }}" class="mt-6 inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                        Browse Products
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
