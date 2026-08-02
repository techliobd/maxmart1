<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CmsPageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id ?? $this->input('id');

        return [
            'title' => 'required|string|max:300',
            'slug' => "required|string|max:300|unique:cms_pages,slug,{$pageId}",
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'template' => 'nullable|in:default,full-width,with-sidebar',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Page title is required.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already in use.',
            'content.required' => 'Content is required.',
        ];
    }
}
