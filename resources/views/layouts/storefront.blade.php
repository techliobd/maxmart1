<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic Meta Tags --}}
    <title>@yield('title', config('app.name', 'MaxMart')) - Premium E-Commerce Platform</title>
    <meta name="description" content="@yield('meta_description', 'Shop the latest products at MaxMart - Your premium online shopping destination.')">
    <meta name="keywords" content="@yield('meta_keywords', 'ecommerce, shopping, online store, maxmart')">
    <meta name="author" content="MaxMart">

    {{-- Open Graph / Social Media --}}
    @yield('og_tags')

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Additional Styles --}}
    @stack('styles')

    {{-- JSON-LD Structured Data --}}
    @yield('structured_data')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    {{-- Top Bar --}}
    <div class="bg-gray-900 text-white text-sm py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <span class="hidden sm:inline">Free Shipping on Orders Over $50!</span>
                </div>
                <div class="flex items-center space-x-4">
                    {{-- Currency Selector --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-1 hover:text-gray-300">
                            <span>{{ session('currency', 'USD') }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-50">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">USD</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">EUR</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">GBP</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">BDT</a>
                            </div>
                        </div>
                    </div>

                    {{-- Language Selector --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-1 hover:text-gray-300">
                            <span>{{ strtoupper(session('locale', 'en')) }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-50">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Español</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Français</a>
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="hover:text-gray-300">My Account</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hover:text-gray-300">Sign In</a>
                        <a href="{{ route('register') }}" class="hover:text-gray-300">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                {{-- Logo --}}
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <span class="text-3xl font-bold text-gray-900">Max<span class="text-blue-600">Mart</span></span>
                    </a>
                </div>

                {{-- Search Bar --}}
                <div class="hidden md:flex flex-1 max-w-xl mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" 
                                   placeholder="Search for products..." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Header Actions --}}
                <div class="flex items-center space-x-4">
                    {{-- Wishlist --}}
                    <a href="{{ route('wishlist.index') }}" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span id="wishlist-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>

                    {{-- Cart --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open; $dispatch('open-cart')" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ session('cart_count', 0) }}</span>
                        </button>
                    </div>

                    {{-- Mobile Menu Button --}}
                    <button @click="$dispatch('toggle-mobile-menu')" class="md:hidden p-2 text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="hidden md:block border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-center space-x-8">
                    <a href="{{ route('home') }}" class="py-4 px-2 text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-blue-600 transition-colors">Home</a>
                    <a href="{{ route('shop') }}" class="py-4 px-2 text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-blue-600 transition-colors">Shop</a>
                    <a href="{{ route('blog') }}" class="py-4 px-2 text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-blue-600 transition-colors">Blog</a>
                    <a href="{{ route('page', 'about') }}" class="py-4 px-2 text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-blue-600 transition-colors">About</a>
                    <a href="{{ route('page', 'contact') }}" class="py-4 px-2 text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-blue-600 transition-colors">Contact</a>
                </div>
            </div>
        </nav>
    </header>

    {{-- Mobile Menu --}}
    <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" 
         x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-b border-gray-200" style="display: none;">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block py-2 text-base font-medium text-gray-900 hover:text-blue-600">Home</a>
            <a href="{{ route('shop') }}" class="block py-2 text-base font-medium text-gray-900 hover:text-blue-600">Shop</a>
            <a href="{{ route('blog') }}" class="block py-2 text-base font-medium text-gray-900 hover:text-blue-600">Blog</a>
            <a href="{{ route('page', 'about') }}" class="block py-2 text-base font-medium text-gray-900 hover:text-blue-600">About</a>
            <a href="{{ route('page', 'contact') }}" class="block py-2 text-base font-medium text-gray-900 hover:text-blue-600">Contact</a>
        </div>
    </div>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- Company Info --}}
                <div>
                    <h3 class="text-2xl font-bold mb-4">Max<span class="text-blue-400">Mart</span></h3>
                    <p class="text-gray-400 text-sm">Your premium online shopping destination for quality products at great prices.</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('shop') }}" class="hover:text-white transition-colors">Shop</a></li>
                        <li><a href="{{ route('blog') }}" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="{{ route('page', 'about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('page', 'contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                {{-- Customer Service --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Customer Service</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('page', 'shipping') }}" class="hover:text-white transition-colors">Shipping Info</a></li>
                        <li><a href="{{ route('page', 'returns') }}" class="hover:text-white transition-colors">Returns & Exchanges</a></li>
                        <li><a href="{{ route('page', 'faq') }}" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="{{ route('page', 'privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('page', 'terms') }}" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4">Newsletter</h4>
                    <p class="text-gray-400 text-sm mb-4">Subscribe to get special offers and updates.</p>
                    <livewire:newsletter-form />
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} MaxMart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Cart Drawer --}}
    <livewire:cart-drawer />

    {{-- Flash Messages --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    {{-- Scripts --}}
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>
