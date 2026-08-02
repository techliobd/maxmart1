<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name' => 'required|string|max:200',
            'site_tagline' => 'nullable|string|max:300',
            'site_description' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:512',
            'social_facebook' => 'nullable|url|max:500',
            'social_twitter' => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
            'social_linkedin' => 'nullable|url|max:500',
            'default_currency_id' => 'nullable|exists:currencies,id',
            'default_language_id' => 'nullable|exists:languages,id',
            'seo_google_analytics' => 'nullable|string|max:100',
            'seo_facebook_pixel' => 'nullable|string|max:100',
            'mail_from_name' => 'nullable|string|max:200',
            'mail_from_email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'site_name.required' => 'Site name is required.',
            'contact_email.email' => 'Please provide a valid contact email.',
            'social_facebook.url' => 'Please provide a valid Facebook URL.',
            'social_twitter.url' => 'Please provide a valid Twitter URL.',
            'social_instagram.url' => 'Please provide a valid Instagram URL.',
        ];
    }
}
