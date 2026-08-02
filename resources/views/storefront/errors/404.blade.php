@extends('layouts.storefront')

@section('title', 'Page Not Found - 404')

@section('content')
    <!-- 404 Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Illustration -->
                <div class="relative mb-8">
                    <div class="inline-flex items-center justify-center w-48 h-48 bg-gray-100 rounded-full">
                        <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-0 left-1/4 w-8 h-8 bg-yellow-200 rounded-full opacity-60 animate-bounce"></div>
                    <div class="absolute bottom-0 right-1/4 w-6 h-6 bg-blue-200 rounded-full opacity-60 animate-bounce" style="animation-delay: 0.5s;"></div>
                </div>

                <!-- Heading -->
                <h1 class="text-8xl font-bold text-gray-200 mb-4">404</h1>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Page Not Found</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Oops! The page you're looking for doesn't exist or has been moved. 
                    Let's get you back on track.
                </p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Go Home
                    </a>
                    <a href="{{ route('shop') }}" class="inline-flex items-center justify-center border border-gray-300 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Browse Shop
                    </a>
                </div>

                <!-- Search -->
                <div class="mt-12 max-w-md mx-auto">
                    <p class="text-sm text-gray-600 mb-4">Or try searching for what you need:</p>
                    <form action="{{ route('search') }}" method="GET" class="flex gap-2">
                        <input type="text" 
                               name="q" 
                               placeholder="Search products..." 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Quick Links -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-4">Popular categories:</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        @php
                            $categories = \App\Models\Category::whereNull('parent_id')->take(5)->get();
                        @endphp
                        @foreach($categories as $category)
                            <a href="{{ route('shop', ['category' => $category->slug]) }}" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Contact Help -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600">
                        Need help? 
                        <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">Contact our support team</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
