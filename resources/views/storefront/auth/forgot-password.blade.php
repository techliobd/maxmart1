@extends('layouts.storefront')

@section('title', 'Forgot Password - MaxMart')

@section('content')
    <!-- Forgot Password Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password?</h1>
                    <p class="text-gray-600">No worries! Enter your email and we'll send you reset instructions.</p>
                </div>

                <!-- Reset Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    @if(session('status'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-green-800">{{ session('status') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="you@example.com"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   required
                                   autofocus>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                            Send Reset Link
                        </button>
                    </form>

                    <!-- Back to Login -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Login
                        </a>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-8 bg-blue-50 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Didn't receive the email?</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li>• Check your spam or junk folder</li>
                        <li>• Make sure you entered the correct email address</li>
                        <li>• Wait a few minutes and try again</li>
                        <li>• <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">Contact support</a> if you still need help</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
