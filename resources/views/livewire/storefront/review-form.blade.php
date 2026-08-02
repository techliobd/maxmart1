<div>
    @if(!Auth::check())
        <button 
            type="button"
            wire:click="toggleForm"
            class="text-blue-600 hover:text-blue-700 font-medium"
        >
            Write a review
        </button>
    @elseif(!$showForm)
        <button 
            type="button"
            wire:click="toggleForm"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
        >
            Write a Review
        </button>
    @else
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Write a Review</h3>
                <button 
                    type="button"
                    wire:click="toggleForm"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="submit" class="space-y-4">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                wire:click="setRating({{ $i }})"
                                class="focus:outline-none"
                            >
                                <svg 
                                    class="h-8 w-8 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor" 
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Review Title
                    </label>
                    <input 
                        type="text" 
                        id="title"
                        wire:model="title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Summarize your experience"
                    >
                    @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Comment -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Review
                    </label>
                    <textarea 
                        id="comment"
                        wire:model="comment"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Share details about your experience with this product"
                    ></textarea>
                    @error('comment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Verified Purchase Badge -->
                @if($isVerifiedPurchase)
                    <div class="flex items-center gap-2 text-green-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">Verified Purchase</span>
                    </div>
                @endif

                <!-- Image Upload (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Add Photos (Optional)</label>
                    <div class="flex items-center gap-4">
                        <input 
                            type="file" 
                            multiple 
                            accept="image/*"
                            wire:model="images"
                            class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        >
                    </div>
                    @error('images.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    @if(count($images) > 0)
                        <div class="flex gap-2 mt-2">
                            @foreach($images as $index => $image)
                                <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                    >
                        Submit Review
                    </button>
                    <button 
                        type="button"
                        wire:click="toggleForm"
                        class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
