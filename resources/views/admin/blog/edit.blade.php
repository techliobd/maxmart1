@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Blog Post</h1>
            <p class="mt-1 text-sm text-gray-500">Update blog post information</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            ← Back to Posts
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

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('title') border-red-500 @enderror">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Slug -->
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50">
            <p class="mt-1 text-xs text-gray-500">Auto-generated from title if left empty</p>
        </div>

        <!-- Category -->
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
            <select name="category_id" id="category_id" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category_id') border-red-500 @enderror">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tags -->
        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <input type="text" name="tags" id="tags" value="{{ old('tags', $post->tags->pluck('name')->join(', ')) }}" placeholder="Comma separated tags"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <p class="mt-1 text-xs text-gray-500">Separate tags with commas</p>
        </div>

        <!-- Featured Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Featured Image</label>
            @if($post->featured_image)
                <div class="mt-2 mb-3">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="Current featured image" class="h-32 w-auto rounded-lg object-cover">
                </div>
            @endif
            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image</p>
            @error('featured_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Excerpt -->
        <div>
            <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="3"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $post->excerpt) }}</textarea>
            @error('excerpt')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
            <textarea name="content" id="content" rows="15" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('content') border-red-500 @enderror">{{ old('content', $post->content) }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Meta Description -->
        <div>
            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description (SEO)</label>
            <textarea name="meta_description" id="meta_description" rows="2"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $post->meta_description) }}</textarea>
            @error('meta_description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status & Published At -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="scheduled" {{ old('status', $post->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="published_at" class="block text-sm font-medium text-gray-700">Published At</label>
                <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.blog.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Post
            </button>
        </div>
    </form>
</div>
@endsection
