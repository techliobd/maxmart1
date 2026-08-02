@extends('layouts.storefront')

@section('title', 'Home - MaxMart')
@section('meta_description', 'Welcome to MaxMart - Your premium online shopping destination for quality products at great prices.')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Discover Amazing Products
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 mb-8">
                        Shop the latest trends with up to 50% off on selected items. Free shipping on orders over $50!
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('shop') }}" 
                           class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center">
                            Shop Now
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('page', 'about') }}" 
                           class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition-colors">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('images/hero-placeholder.jpg') }}" 
                         alt="Hero Image" 
                         class="rounded-lg shadow-2xl"
                         onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Free Shipping</h3>
                    <p class="text-sm text-gray-600">On orders over $50</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Secure Payment</h3>
                    <p class="text-sm text-gray-600">100% secure transactions</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Easy Returns</h3>
                    <p class="text-sm text-gray-600">30-day return policy</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-sm text-gray-600">Dedicated customer support</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Categories --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Explore our wide range of categories and find exactly what you're looking for.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @forelse($categories ?? [] as $category)
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" 
                       class="group bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition-colors">
                            <svg class="w-8 h-8 text-gray-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 group-hover:text-blue-600">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $category->products_count ?? 0 }} Products</p>
                    </a>
                @empty
                    {{-- Placeholder categories --}}
                    @for($i = 1; $i <= 6; $i++)
                        <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-400">Category {{ $i }}</h3>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- Flash Sale --}}
    @if(isset($flashSale) && $flashSale)
        <section class="py-16 bg-gradient-to-r from-red-500 to-orange-500 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">🔥 Flash Sale</h2>
                        <p class="text-red-100">Limited time offer! Grab amazing deals before they're gone.</p>
                    </div>
                    <livewire:flash-sale-countdown :endTime="$flashSale->ends_at" />
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($flashSale->products ?? [] as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Products --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Featured Products</h2>
                    <p class="text-gray-600">Handpicked selection of our best products</p>
                </div>
                <a href="{{ route('shop') }}" class="text-blue-600 font-medium hover:text-blue-700 flex items-center">
                    View All
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($featuredProducts ?? [] as $product)
                    <x-product-card :product="$product" />
                @empty
                    @for($i = 1; $i <= 8; $i++)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="aspect-square bg-gray-100"></div>
                            <div class="p-4">
                                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- New Arrivals --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">New Arrivals</h2>
                    <p class="text-gray-600">Check out the latest additions to our store</p>
                </div>
                <a href="{{ route('shop', ['sort' => 'newest']) }}" class="text-blue-600 font-medium hover:text-blue-700 flex items-center">
                    View All
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($newArrivals ?? [] as $product)
                    <x-product-card :product="$product" />
                @empty
                    @for($i = 1; $i <= 8; $i++)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="aspect-square bg-gray-100"></div>
                            <div class="p-4">
                                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Don't just take our word for it - hear from our satisfied customers.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($testimonials ?? [] as $testimonial)
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-700 mb-4 italic">"{{ $testimonial->content }}"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                {{ substr($testimonial->customer_name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">{{ $testimonial->customer_name }}</p>
                                <p class="text-xs text-gray-500">{{ $testimonial->customer_title ?? 'Verified Buyer' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Placeholder testimonials --}}
                    @for($i = 1; $i <= 3; $i++)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                @for($j = 1; $j <= 5; $j++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-gray-700 mb-4 italic">"Great products and excellent customer service! Highly recommended."</p>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                    {{ chr(64 + $i) }}
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900">Customer {{ $i }}</p>
                                    <p class="text-xs text-gray-500">Verified Buyer</p>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- Newsletter --}}
    <section class="py-16 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
                <p class="text-gray-400 mb-8">Get the latest updates on new products and upcoming sales.</p>
                <livewire:newsletter-form />
            </div>
        </div>
    </section>
@endsection
