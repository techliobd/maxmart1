<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:500',
            'slug' => 'required|string|max:500|unique:blog_posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'author_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Blog post title is required.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already in use.',
            'content.required' => 'Content is required.',
            'blog_category_id.required' => 'Category is required.',
            'author_id.required' => 'Author is required.',
        ];
    }
}
