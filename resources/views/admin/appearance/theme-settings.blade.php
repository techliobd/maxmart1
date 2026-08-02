<x-admin-layout>
    <x-slot name="title">Theme Settings</x-slot>
    <x-slot name="subtitle">Customize Storefront Appearance</x-slot>

    <form action="{{ route('admin.appearance.theme-settings') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Logo & Favicon -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Logo & Favicon</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Storefront Logo</label>
                            @if(setting('storefront_logo'))
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . setting('storefront_logo')) }}" alt="Current Logo" class="h-12 w-auto">
                                </div>
                            @endif
                            <input type="file" name="storefront_logo" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                            <p class="mt-1 text-xs text-gray-500">Recommended: PNG with transparent background, max 200x60px</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Logo</label>
                            @if(setting('admin_logo'))
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . setting('admin_logo')) }}" alt="Current Admin Logo" class="h-10 w-auto">
                                </div>
                            @endif
                            <input type="file" name="admin_logo" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            @if(setting('favicon'))
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . setting('favicon')) }}" alt="Current Favicon" class="h-8 w-8">
                                </div>
                            @endif
                            <input type="file" name="favicon" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                            <p class="mt-1 text-xs text-gray-500">Recommended: ICO or PNG, 32x32px or 64x64px</p>
                        </div>
                    </div>
                </div>

                <!-- Colors -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Color Scheme</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', setting('primary_color', '#3B82F6')) }}"
                                    class="h-10 w-14 rounded border-gray-300">
                                <span class="text-sm text-gray-600" x-text="document.getElementById('primary_color').value">#3B82F6</span>
                            </div>
                        </div>
                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700">Secondary Color</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="color" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', setting('secondary_color', '#10B981')) }}"
                                    class="h-10 w-14 rounded border-gray-300">
                                <span class="text-sm text-gray-600" x-text="document.getElementById('secondary_color').value">#10B981</span>
                            </div>
                        </div>
                        <div>
                            <label for="accent_color" class="block text-sm font-medium text-gray-700">Accent Color</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="color" name="accent_color" id="accent_color" value="{{ old('accent_color', setting('accent_color', '#F59E0B')) }}"
                                    class="h-10 w-14 rounded border-gray-300">
                                <span class="text-sm text-gray-600" x-text="document.getElementById('accent_color').value">#F59E0B</span>
                            </div>
                        </div>
                        <div>
                            <label for="text_color" class="block text-sm font-medium text-gray-700">Text Color</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="color" name="text_color" id="text_color" value="{{ old('text_color', setting('text_color', '#1F2937')) }}"
                                    class="h-10 w-14 rounded border-gray-300">
                                <span class="text-sm text-gray-600" x-text="document.getElementById('text_color').value">#1F2937</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layout -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Layout Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="layout_width" class="block text-sm font-medium text-gray-700">Site Width</label>
                            <select name="layout_width" id="layout_width" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="full" {{ old('layout_width', setting('layout_width', 'full')) === 'full' ? 'selected' : '' }}>Full Width</option>
                                <option value="boxed" {{ old('layout_width', setting('layout_width', 'boxed')) === 'boxed' ? 'selected' : '' }}>Boxed (1200px)</option>
                                <option value="narrow" {{ old('layout_width', setting('layout_width', 'narrow')) === 'narrow' ? 'selected' : '' }}>Narrow (960px)</option>
                            </select>
                        </div>
                        <div>
                            <label for="header_style" class="block text-sm font-medium text-gray-700">Header Style</label>
                            <select name="header_style" id="header_style" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="default" {{ old('header_style', setting('header_style', 'default')) === 'default' ? 'selected' : '' }}>Default</option>
                                <option value="centered" {{ old('header_style', setting('header_style', 'centered')) === 'centered' ? 'selected' : '' }}>Centered Logo</option>
                                <option value="minimal" {{ old('header_style', setting('header_style', 'minimal')) === 'minimal' ? 'selected' : '' }}>Minimal</option>
                            </select>
                        </div>
                        <div>
                            <label for="footer_style" class="block text-sm font-medium text-gray-700">Footer Style</label>
                            <select name="footer_style" id="footer_style" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="default" {{ old('footer_style', setting('footer_style', 'default')) === 'default' ? 'selected' : '' }}>Default</option>
                                <option value="minimal" {{ old('footer_style', setting('footer_style', 'minimal')) === 'minimal' ? 'selected' : '' }}>Minimal</option>
                                <option value="extended" {{ old('footer_style', setting('footer_style', 'extended')) === 'extended' ? 'selected' : '' }}>Extended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Social Media Links</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="social_facebook" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                            <input type="url" name="social_facebook" id="social_facebook" value="{{ old('social_facebook', setting('social_facebook')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label for="social_twitter" class="block text-sm font-medium text-gray-700">Twitter/X URL</label>
                            <input type="url" name="social_twitter" id="social_twitter" value="{{ old('social_twitter', setting('social_twitter')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label for="social_instagram" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                            <input type="url" name="social_instagram" id="social_instagram" value="{{ old('social_instagram', setting('social_instagram')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label for="social_youtube" class="block text-sm font-medium text-gray-700">YouTube URL</label>
                            <input type="url" name="social_youtube" id="social_youtube" value="{{ old('social_youtube', setting('social_youtube')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label for="social_linkedin" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                            <input type="url" name="social_linkedin" id="social_linkedin" value="{{ old('social_linkedin', setting('social_linkedin')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label for="social_pinterest" class="block text-sm font-medium text-gray-700">Pinterest URL</label>
                            <input type="url" name="social_pinterest" id="social_pinterest" value="{{ old('social_pinterest', setting('social_pinterest')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Settings</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="show_breadcrumb" value="1" {{ old('show_breadcrumb', setting('show_breadcrumb', true)) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Show Breadcrumb Navigation</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="show_back_to_top" value="1" {{ old('show_back_to_top', setting('show_back_to_top', true)) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Show Back to Top Button</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="enable_dark_mode" value="1" {{ old('enable_dark_mode', setting('enable_dark_mode', false)) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Enable Dark Mode Toggle</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="sticky_header" value="1" {{ old('sticky_header', setting('sticky_header', true)) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Sticky Header</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Custom CSS/JS</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="custom_css" class="block text-sm font-medium text-gray-700">Custom CSS</label>
                            <textarea name="custom_css" id="custom_css" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary font-mono text-sm">{{ old('custom_css', setting('custom_css')) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Add custom CSS without modifying theme files</p>
                        </div>
                        <div>
                            <label for="custom_js" class="block text-sm font-medium text-gray-700">Custom JavaScript</label>
                            <textarea name="custom_js" id="custom_js" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary font-mono text-sm">{{ old('custom_js', setting('custom_js')) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Add custom JS before closing body tag</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <button type="submit" class="w-full btn-primary justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Theme Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
