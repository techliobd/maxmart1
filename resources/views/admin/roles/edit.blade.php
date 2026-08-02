<x-admin-layout>
    <x-slot name="title">Edit Role</x-slot>
    <x-slot name="subtitle">{{ $role->display_name ?? $role->name }}</x-slot>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Role Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Role Name (slug)</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                            <p class="mt-1 text-xs text-gray-500">Role name cannot be changed after creation.</p>
                        </div>
                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name *</label>
                            <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $role->display_name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('display_name') border-red-500 @enderror">
                            @error('display_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
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
                            $rolePermissions = $role->permissions ?? [];
                        @endphp

                        @foreach($permissionGroups as $group => $permissions)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $group }}</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                                {{ in_array($permission, $rolePermissions) ? 'checked' : '' }}
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
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="can_access_admin" value="1" {{ old('can_access_admin', $role->can_access_admin ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Can Access Admin Panel</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Users with this Role</h3>
                    <div class="space-y-2">
                        @forelse($role->users ?? [] as $user)
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-gray-700">{{ $user->name }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No users assigned to this role yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <button type="submit" class="w-full btn-primary justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="w-full btn-secondary justify-center mt-3">
                        Cancel
                    </a>
                    @if($role->name !== 'super_admin')
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="mt-3" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full btn-danger justify-center">
                                Delete Role
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
