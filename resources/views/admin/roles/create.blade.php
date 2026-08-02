<x-admin-layout>
    <x-slot name="title">Create Role</x-slot>
    <x-slot name="subtitle">Add New User Role</x-slot>

    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Role Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Role Name (slug) *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                placeholder="e.g., content_manager"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('name') border-red-500 @enderror"
                                pattern="[a-z_]+">
                            <p class="mt-1 text-xs text-gray-500">Lowercase letters and underscores only. No spaces.</p>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name *</label>
                            <input type="text" name="display_name" id="display_name" value="{{ old('display_name') }}" required
                                placeholder="e.g., Content Manager"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('display_name') border-red-500 @enderror">
                            @error('display_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Permissions</h3>
                    <div class="space-y-6">
                        @php
                            $permissionGroups = [
                                'Products' => ['products.view', 'products.create', 'products.edit', 'products.delete'],
                                'Categories' => ['categories.view', 'categories.create', 'categories.edit', 'categories.delete'],
                                'Brands' => ['brands.view', 'brands.create', 'brands.edit', 'brands.delete'],
                                'Orders' => ['orders.view', 'orders.create', 'orders.edit', 'orders.delete', 'orders.update_status'],
                                'Customers' => ['customers.view', 'customers.create', 'customers.edit', 'customers.delete'],
                                'Content' => ['blog.view', 'blog.create', 'blog.edit', 'blog.delete', 'pages.view', 'pages.create', 'pages.edit', 'pages.delete'],
                                'Settings' => ['settings.view', 'settings.edit'],
                                'Staff' => ['staff.view', 'staff.create', 'staff.edit', 'staff.delete'],
                                'Reports' => ['reports.view', 'reports.export'],
                            ];
                        @endphp

                        @foreach($permissionGroups as $group => $permissions)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $group }}</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                                {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-600">{{ str_replace('_', ' ', ucfirst(str_replace('.', ' - ', $permission))) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Role Settings</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="can_access_admin" value="1" {{ old('can_access_admin', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Can Access Admin Panel</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <button type="submit" class="w-full btn-primary justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="w-full btn-secondary justify-center mt-3">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
