@extends('layouts.storefront')

@section('title', $page->meta_title ?? $page->title)
@section('description', $page->meta_description ?? Str::limit(strip_tags($page->content), 160))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">{{ $page->title }}</li>
            </ol>
        </div>
    </nav>

    <!-- Page Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <article class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($page->featuredImage)
                        <img src="{{ $page->featuredImage?->url ?? asset('images/placeholder.png') }}" 
                             alt="{{ $page->title }}" 
                             class="w-full h-64 object-cover">
                    @endif
                    
                    <div class="p-8 md:p-12">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
                        
                        <div class="prose prose-lg max-w-none text-gray-700">
                            {!! $page->content !!}
                        </div>

                        @if($page->updated_at)
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <p class="text-sm text-gray-500">
                                    Last updated: {{ $page->updated_at->format('F d, Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </article>

                <!-- Contact CTA for specific pages -->
                @if(in_array(strtolower($page->slug), ['about-us', 'contact', 'help', 'faq']))
                    <div class="mt-12 bg-primary-50 rounded-xl p-8 text-center">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Have Questions?</h2>
                        <p class="text-gray-600 mb-6">Our team is here to help. Reach out to us anytime.</p>
                        <a href="{{ route('contact') }}" class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                            Contact Us
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
