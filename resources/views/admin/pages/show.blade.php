@extends('layouts.admin')

@section('title', $page->title)

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $page->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">Page details</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Edit Page
            </a>
            <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                ← Back to Pages
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Page Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Page Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Title</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1"><code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $page->slug }}</code></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $page->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Content</dt>
                        <dd class="mt-1 prose prose-sm max-w-none">
                            <div class="mt-2 p-4 bg-gray-50 rounded-lg">{{ Str::limit($page->content, 500) }}</div>
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Content Preview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Content Preview</h2>
                </div>
                <div class="p-6">
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($page->content)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Featured Image -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Featured Image</h2>
                </div>
                <div class="p-6">
                    @if($page->featured_image)
                        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto rounded-lg">
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No featured image</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SEO Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">SEO Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->meta_title ?: 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->meta_description ?: 'Not set' }}</dd>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Timestamps</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.pages.edit', $page) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700">
                        Edit This Page
                    </a>
                    <form action="{{ route('admin.pages.toggle-status', $page) }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Toggle Status ({{ $page->is_active ? 'Deactivate' : 'Activate' }})
                        </button>
                    </form>
                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full text-center px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700">
                            Delete Page
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
