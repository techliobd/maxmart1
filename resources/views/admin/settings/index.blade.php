@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold text-gray-800">Settings</h1><p class="text-gray-500 mt-1">Configure your store settings</p></div>
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        
        <!-- General Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">General Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                    <input type="text" name="store_name" value="{{ old('store_name', setting('store_name', 'MaxMart')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Email</label>
                    <input type="email" name="store_email" value="{{ old('store_email', setting('store_email')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Phone</label>
                    <input type="text" name="store_phone" value="{{ old('store_phone', setting('store_phone')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="USD" {{ setting('currency') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ setting('currency') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="GBP" {{ setting('currency') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                        <option value="BDT" {{ setting('currency') === 'BDT' ? 'selected' : '' }}>BDT (৳)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">SEO Settings</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', setting('meta_title')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('meta_description', setting('meta_description')) }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">Save Settings</button>
    </form>
</div>
@endsection
