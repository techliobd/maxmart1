@extends('layouts.storefront')

@section('title', $post->meta_title ?? $post->title)
@section('description', $post->meta_description ?? Str::limit(strip_tags($post->content), 160))

@push('head')
    @if($post->featuredImage)
        <meta property="og:image" content="{{ $post->featuredImage->url }}">
        <meta name="twitter:image" content="{{ $post->featuredImage->url }}">
    @endif
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('blog') }}" class="hover:text-primary-600">Blog</a></li>
                @if($post->category)
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-primary-600">{{ $post->category->name }}</a></li>
                @endif
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">{{ Str::limit($post->title, 50) }}</li>
            </ol>
        </div>
    </nav>

    <!-- Article Section -->
    <article class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <header class="mb-8">
                    @if($post->featuredImage)
                        <img src="{{ $post->featuredImage->url ?? asset('images/placeholder.png') }}" 
                             alt="{{ $post->title }}" 
                             class="w-full h-96 object-cover rounded-xl mb-8">
                    @endif
                    
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                        @if($post->category)
                            <a href="{{ route('blog.category', $post->category->slug) }}" 
                               class="inline-flex items-center px-3 py-1 rounded-full bg-primary-100 text-primary-700 hover:bg-primary-200 transition-colors">
                                {{ $post->category->name }}
                            </a>
                        @endif
                        <span>{{ $post->publishedAt->format('F d, Y') }}</span>
                        <span>•</span>
                        <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>

                    @if($post->author)
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 font-bold text-lg">{{ substr($post->author->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $post->author->name }}</p>
                                @if($post->author->bio)
                                    <p class="text-sm text-gray-600">{{ Str::limit($post->author->bio, 50) }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </header>

                <!-- Content -->
                <div class="prose prose-lg max-w-none mb-12">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if($post->tags && count($post->tags) > 0)
                    <div class="flex flex-wrap gap-2 mb-8">
                        @foreach($post->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Share -->
                <div class="border-t border-b border-gray-200 py-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Share this article</h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.post', $post->slug)) }}" 
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.post', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.post', $post->slug)) }}&title={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </a>
                        <button onclick="copyLink()" 
                                class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Copy Link
                        </button>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts && $relatedPosts->count() > 0)
                    <section class="mt-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $relatedPost)
                                <article class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                                    @if($relatedPost->featuredImage)
                                        <a href="{{ route('blog.post', $relatedPost->slug) }}">
                                            <img src="{{ $relatedPost->featuredImage->url ?? asset('images/placeholder.png') }}" 
                                                 alt="{{ $relatedPost->title }}" 
                                                 class="w-full h-40 object-cover">
                                        </a>
                                    @endif
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-2">{{ $relatedPost->publishedAt->format('M d, Y') }}</p>
                                        <h3 class="font-semibold text-gray-900 mb-2 hover:text-primary-600">
                                            <a href="{{ route('blog.post', $relatedPost->slug) }}">{{ Str::limit($relatedPost->title, 50) }}</a>
                                        </h3>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </article>

    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    </script>
@endsection
