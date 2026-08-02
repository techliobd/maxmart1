@props(['post'])

<div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow overflow-hidden">
    {{-- Featured Image --}}
    <a href="{{ route('blog.show', $post->slug) }}">
        <div class="relative aspect-video overflow-hidden bg-gray-100">
            <img src="{{ $post->featured_image ?? asset('images/blog-placeholder.jpg') }}" 
                 alt="{{ $post->title }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
        </div>
    </a>

    {{-- Content --}}
    <div class="p-5">
        {{-- Category & Date --}}
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-2">
            @if($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}" 
                   class="text-blue-600 hover:text-blue-700 font-medium">
                    {{ $post->category->name }}
                </a>
                <span>&bull;</span>
            @endif
            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
        </div>

        {{-- Title --}}
        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 mb-2">
            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">
                {{ $post->title }}
            </a>
        </h3>

        {{-- Excerpt --}}
        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
            {{ Str::limit(strip_tags($post->content), 120) }}
        </p>

        {{-- Author & Read More --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 text-sm font-medium">
                    {{ substr($post->author?->name ?? 'A', 0, 1) }}
                </div>
                <span class="text-xs text-gray-500">{{ $post->author?->name ?? 'Admin' }}</span>
            </div>
            <a href="{{ route('blog.show', $post->slug) }}" 
               class="text-sm font-medium text-blue-600 hover:text-blue-700">
                Read More →
            </a>
        </div>
    </div>
</div>
