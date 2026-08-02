<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Http\Requests\Admin\SettingUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.settings.general');
    }

    public function general()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(SettingUpdateRequest $request)
    {
        $this->updateSettings($request, [
            'site_name',
            'site_tagline',
            'site_description',
            'contact_email',
            'contact_phone',
            'contact_address',
            'currency_id',
            'timezone',
            'date_format',
            'time_format',
        ]);

        Cache::forget('maxmart_settings');

        return back()->with('success', 'General settings updated successfully.');
    }

    public function seo()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.seo', compact('settings'));
    }

    public function updateSeo(Request $request)
    {
        $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:2048',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
        ]);

        $this->updateSettings($request, [
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_analytics_id',
            'facebook_pixel_id',
        ]);

        if ($request->hasFile('og_image')) {
            $path = $request->file('og_image')->store('settings/seo', 'public');
            $this->saveSetting('og_image', $path);
        }

        Cache::forget('maxmart_settings');

        return back()->with('success', 'SEO settings updated successfully.');
    }

    public function email()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|in:smtp,mail,sendmail,log,array',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,none',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        $this->updateSettings($request, [
            'mail_driver',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
        ]);

        Cache::forget('maxmart_settings');

        return back()->with('success', 'Email settings updated successfully.');
    }

    public function shipping()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.shipping', compact('settings'));
    }

    public function updateShipping(Request $request)
    {
        $request->validate([
            'shipping_enabled' => 'boolean',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'flat_rate_shipping' => 'nullable|numeric|min:0',
            'shipping_weight_unit' => 'nullable|in:kg,lbs,g,oz',
            'shipping_dimension_unit' => 'nullable|in:cm,inches,m,ft',
        ]);

        $this->updateSettings($request, [
            'shipping_enabled',
            'free_shipping_threshold',
            'flat_rate_shipping',
            'shipping_weight_unit',
            'shipping_dimension_unit',
        ]);

        Cache::forget('maxmart_settings');

        return back()->with('success', 'Shipping settings updated successfully.');
    }

    public function tax()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.tax', compact('settings'));
    }

    public function updateTax(Request $request)
    {
        $request->validate([
            'tax_enabled' => 'boolean',
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_calculation_method' => 'nullable|in:inclusive,exclusive',
            'tax_display_method' => 'nullable|in:including_tax,excluding_tax',
        ]);

        $this->updateSettings($request, [
            'tax_enabled',
            'default_tax_rate',
            'tax_calculation_method',
            'tax_display_method',
        ]);

        Cache::forget('maxmart_settings');

        return back()->with('success', 'Tax settings updated successfully.');
    }

    public function payment()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'cod_enabled' => 'boolean',
            'cod_fee' => 'nullable|numeric|min:0',
        ]);

        $this->updateSettings($request, [
            'cod_enabled',
            'cod_fee',
        ]);

        Cache::forget('maxmart_settings');

        return back()->with('success', 'Payment settings updated successfully.');
    }

    public function social()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.social', compact('settings'));
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            'facebook_url' => 'nullable|url|max:500',
            'twitter_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'linkedin_url' => 'nullable|url|max:500',
            'pinterest_url' => 'nullable|url|max:500',
        ]);

        $this->updateSettings($request, [
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url',
            'linkedin_url',
            'pinterest_url',
        ]);

        Cache::forget('maxmart_settings');

        return back()->with('success', 'Social media settings updated successfully.');
    }

    private function updateSettings(Request $request, array $keys)
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $this->saveSetting($key, $request->input($key));
            }
        }
    }

    private function saveSetting($key, $value)
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
