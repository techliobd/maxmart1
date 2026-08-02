@extends('layouts.admin')

@section('title', 'Create Page')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Page</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new static page or CMS content</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            ← Back to Pages
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('title') border-red-500 @enderror">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Slug -->
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="auto-generated-from-title"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50">
            <p class="mt-1 text-xs text-gray-500">Auto-generated from title if left empty</p>
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
            <textarea name="content" id="content" rows="15" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Featured Image -->
        <div>
            <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('featured_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Meta Title -->
        <div>
            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title (SEO)</label>
            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" placeholder="Defaults to page title"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('meta_title') border-red-500 @enderror">
            @error('meta_title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Meta Description -->
        <div>
            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description (SEO)</label>
            <textarea name="meta_description" id="meta_description" rows="2"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
            @error('meta_description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="is_active" class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700">Page is active (visible on storefront)</span>
            </label>
            @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Create Page
            </button>
        </div>
    </form>
</div>
@endsection
