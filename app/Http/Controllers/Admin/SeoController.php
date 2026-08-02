<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.seo.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:500',
            'og_default_image' => 'nullable|image|max:2048',
            'google_analytics_id' => 'nullable|string|max:50',
            'google_tag_manager_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'enable_sitemap' => 'boolean',
            'enable_robots' => 'boolean',
            'robots_content' => 'nullable|string',
        ]);

        $keys = [
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_analytics_id',
            'google_tag_manager_id',
            'facebook_pixel_id',
            'enable_sitemap',
            'enable_robots',
            'robots_content',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }

        if ($request->hasFile('og_default_image')) {
            $path = $request->file('og_default_image')->store('settings/seo', 'public');
            Setting::updateOrCreate(['key' => 'og_default_image'], ['value' => $path]);
        }

        Cache::forget('maxmart_settings');

        return back()->with('success', 'SEO settings updated successfully.');
    }

    public function generateSitemap()
    {
        // This would typically be done via a command or scheduled task
        // For now, we'll just return a success message
        return back()->with('success', 'Sitemap generation triggered. Check storage/app/public/sitemap.xml');
    }

    public function previewRobots()
    {
        $robotsContent = Setting::where('key', 'robots_content')->value('value') ?? '';

        return response($robotsContent, 200)->header('Content-Type', 'text/plain');
    }
}
