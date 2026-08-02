<div>
    @if($subscribed)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-green-800 font-medium">{{ $message ?? 'Thank you for subscribing!' }}</p>
            </div>
        </div>
    @else
        <form wire:submit="subscribe" class="w-full">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label for="newsletter-email" class="sr-only">Email address</label>
                    <input 
                        type="email" 
                        id="newsletter-email"
                        wire:model="email"
                        placeholder="Enter your email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    >
                    @error('email') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
                <button 
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors whitespace-nowrap"
                >
                    Subscribe
                </button>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                Get the latest updates on new products and upcoming sales. Unsubscribe anytime.
            </p>
        </form>
    @endif
</div>
