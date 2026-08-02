@extends('layouts.storefront')

@section('title', isset($category) ? $category->name . ' - Blog' : 'Blog - MaxMart')

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                @if(isset($category))
                    <li><a href="{{ route('blog') }}" class="hover:text-primary-600">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 font-medium">{{ $category->name }}</li>
                @else
                    <li class="text-gray-900 font-medium">Blog</li>
                @endif
            </ol>
        </div>
    </nav>

    <!-- Blog Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Main Content -->
                <div class="flex-1">
                    @if(isset($category))
                        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ $category->name }}</h1>
                    @else
                        <h1 class="text-3xl font-bold text-gray-900 mb-8">Our Blog</h1>
                    @endif

                    @if($posts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($posts as $post)
                                <article class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                                    @if($post->featuredImage)
                                        <a href="{{ route('blog.post', $post->slug) }}">
                                            <img src="{{ $post->featuredImage->url ?? asset('images/placeholder.png') }}" 
                                                 alt="{{ $post->title }}" 
                                                 class="w-full h-48 object-cover">
                                        </a>
                                    @endif
                                    <div class="p-6">
                                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                            <span>{{ $post->publishedAt->format('M d, Y') }}</span>
                                            @if($post->category)
                                                <span>•</span>
                                                <a href="{{ route('blog.category', $post->category->slug) }}" class="text-primary-600 hover:text-primary-700">
                                                    {{ $post->category->name }}
                                                </a>
                                            @endif
                                        </div>
                                        <h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-primary-600">
                                            <a href="{{ route('blog.post', $post->slug) }}">{{ $post->title }}</a>
                                        </h2>
                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                @if($post->author)
                                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                        <span class="text-primary-600 font-semibold text-sm">{{ substr($post->author->name, 0, 1) }}</span>
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $post->author->name }}</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('blog.post', $post->slug) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                                Read More →
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <h3 class="mt-4 text-xl font-medium text-gray-900">No posts found</h3>
                            <p class="mt-2 text-gray-600">Check back later for new articles.</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <aside class="w-full md:w-80">
                    <!-- Search -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Search</h3>
                        <form action="{{ route('blog') }}" method="GET" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search articles..." 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                        <ul class="space-y-2">
                            @php
                                $categories = \App\Models\BlogCategory::withCount('posts')->get();
                            @endphp
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog.category', $category->slug) }}" 
                                       class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors {{ isset($category) && $category->id == $category->id ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                                        <span>{{ $category->name }}</span>
                                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $category->posts_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Recent Posts -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Posts</h3>
                        <ul class="space-y-4">
                            @php
                                $recentPosts = \App\Models\BlogPost::published()->latest('published_at')->take(5)->get();
                            @endphp
                            @foreach($recentPosts as $recentPost)
                                <li class="flex gap-3">
                                    @if($recentPost->featuredImage)
                                        <img src="{{ $recentPost->featuredImage->url ?? asset('images/placeholder.png') }}" 
                                             alt="{{ $recentPost->title }}" 
                                             class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                    @endif
                                    <div>
                                        <a href="{{ route('blog.post', $recentPost->slug) }}" 
                                           class="text-sm font-medium text-gray-900 hover:text-primary-600 line-clamp-2">
                                            {{ $recentPost->title }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1">{{ $recentPost->publishedAt->format('M d, Y') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
