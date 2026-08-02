<x-layout-storefront>
    <x-slot name="title">Account Settings</x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Account Settings</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your password and notification preferences</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Change Password Section -->
        <div class="mb-8 pb-8 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h2>
            <form method="POST" action="{{ route('customer.settings.password.update') }}" class="max-w-xl space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           required
                           autocomplete="current-password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           required
                           minlength="8"
                           autocomplete="new-password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_password') border-red-500 @enderror">
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 characters. Use a mix of letters, numbers, and symbols.</p>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                    <input type="password" 
                           id="new_password_confirmation" 
                           name="new_password_confirmation" 
                           required
                           minlength="8"
                           autocomplete="new-password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Notification Preferences Section -->
        <div class="mb-8 pb-8 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Notification Preferences</h2>
            <form method="POST" action="{{ route('customer.settings.notifications.update') }}" class="max-w-xl space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Order Updates</h4>
                            <p class="text-sm text-gray-500 mt-1">Receive notifications about your order status changes</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="order_updates" value="1" {{ auth()->user()->meta?->notifications?.['order_updates'] ?? true ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Promotional Emails</h4>
                            <p class="text-sm text-gray-500 mt-1">Get notified about sales, discounts, and special offers</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="promotional_emails" value="1" {{ auth()->user()->meta?->notifications?.['promotional_emails'] ?? false ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Newsletter</h4>
                            <p class="text-sm text-gray-500 mt-1">Subscribe to our weekly newsletter with latest products and trends</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="newsletter" value="1" {{ auth()->user()->meta?->notifications?.['newsletter'] ?? false ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Price Drop Alerts</h4>
                            <p class="text-sm text-gray-500 mt-1">Get notified when prices drop on items in your wishlist</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="price_alerts" value="1" {{ auth()->user()->meta?->notifications?.['price_alerts'] ?? true ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Back in Stock Alerts</h4>
                            <p class="text-sm text-gray-500 mt-1">Receive alerts when out-of-stock items are available again</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="stock_alerts" value="1" {{ auth()->user()->meta?->notifications?.['stock_alerts'] ?? true ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Save Preferences
                    </button>
                </div>
            </form>
        </div>

        <!-- Privacy & Data Section -->
        <div class="mb-8 pb-8 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Privacy & Data</h2>
            <div class="max-w-xl space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900">Download My Data</h4>
                        <p class="text-sm text-gray-500 mt-1">Get a copy of all your personal data</p>
                    </div>
                    <a href="{{ route('customer.settings.export-data') }}" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                        Download
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900">Active Sessions</h4>
                        <p class="text-sm text-gray-500 mt-1">Manage devices where you're currently logged in</p>
                    </div>
                    <a href="{{ route('customer.settings.sessions') }}" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                        View Sessions
                    </a>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div>
            <h2 class="text-lg font-semibold text-red-600 mb-4">Danger Zone</h2>
            <div class="max-w-xl p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="font-medium text-gray-900 mb-2">Delete Account</h3>
                <p class="text-sm text-gray-600 mb-4">Once you delete your account, there is no going back. All your data including orders, addresses, and wishlist will be permanently removed.</p>
                <button type="button" 
                        onclick="confirmAccountDeletion()"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl">
                <div class="mb-4">
                    <div class="w-12 h-12 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete Account?</h3>
                    <p class="text-sm text-gray-600 text-center">This action cannot be undone. Please type <strong class="text-gray-900">DELETE</strong> to confirm.</p>
                </div>
                <form method="POST" action="{{ route('customer.settings.delete-account') }}" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <input type="text" 
                           id="confirmText" 
                           name="confirmation" 
                           placeholder="Type DELETE here"
                           class="w-full px-4 py-2 mb-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center uppercase"
                           required>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmAccountDeletion() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('confirmText').value = '';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Prevent form submission if confirmation text doesn't match
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            const confirmText = document.getElementById('confirmText').value.trim().toUpperCase();
            if (confirmText !== 'DELETE') {
                e.preventDefault();
                alert('Please type DELETE to confirm account deletion.');
            }
        });
    </script>
</x-layout-storefront>
