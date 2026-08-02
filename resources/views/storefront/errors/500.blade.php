@extends('layouts.storefront')

@section('title', 'Server Error - 500')

@section('content')
    <!-- 500 Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Illustration -->
                <div class="relative mb-8">
                    <div class="inline-flex items-center justify-center w-48 h-48 bg-gray-100 rounded-full">
                        <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-0 left-1/4 w-8 h-8 bg-red-200 rounded-full opacity-60 animate-pulse"></div>
                    <div class="absolute bottom-0 right-1/4 w-6 h-6 bg-orange-200 rounded-full opacity-60 animate-pulse" style="animation-delay: 0.5s;"></div>
                </div>

                <!-- Heading -->
                <h1 class="text-8xl font-bold text-gray-200 mb-4">500</h1>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Server Error</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Oops! Something went wrong on our end. We're working to fix it and should be back up shortly.
                </p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Go Home
                    </a>
                    <button onclick="window.location.reload()" class="inline-flex items-center justify-center border border-gray-300 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Try Again
                    </button>
                </div>

                <!-- Status Info -->
                <div class="mt-12 bg-blue-50 rounded-xl p-6 text-left">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        What happened?
                    </h3>
                    <p class="text-gray-700 mb-4">
                        Our server encountered an unexpected error while processing your request. 
                        This is usually temporary and gets resolved quickly.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-200 text-blue-700 rounded-full text-xs font-semibold mr-2 flex-shrink-0">✓</span>
                            <span>Our team has been automatically notified</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-200 text-blue-700 rounded-full text-xs font-semibold mr-2 flex-shrink-0">✓</span>
                            <span>We're investigating the issue</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-200 text-blue-700 rounded-full text-xs font-semibold mr-2 flex-shrink-0">✓</span>
                            <span>Service should be restored shortly</span>
                        </li>
                    </ul>
                </div>

                <!-- Error ID (for debugging) -->
                @if(app()->environment('production'))
                    <div class="mt-6 text-sm text-gray-500">
                        <p>Error ID: <code class="bg-gray-100 px-2 py-1 rounded">{{ uniqid('ERR_') }}</code></p>
                        <p class="mt-2">Please reference this ID when contacting support.</p>
                    </div>
                @endif

                <!-- Contact Help -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <p class="text-gray-600 mb-4">Need immediate assistance?</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('contact') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact Support
                        </a>
                        <a href="{{ route('blog') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            Read Our Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
