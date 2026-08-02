<div class="bg-white border border-gray-200 rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Homepage Sections Order</h3>
    <p class="text-sm text-gray-600 mb-6">Arrange the order of sections on your homepage. Sections at the top will appear first.</p>

    @if(count($sections) > 0)
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach($sectionOrder as $index => $sectionId)
                    @php
                        $section = collect($sections)->firstWhere('id', $sectionId);
                    @endphp
                    @if($section)
                        <li class="p-4 hover:bg-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full text-sm font-medium text-gray-700">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $section['title'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $section['type'] }} section</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- Move Up -->
                                @if($index > 0)
                                    <button 
                                        type="button"
                                        wire:click="moveUp({{ $sectionId }})"
                                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        title="Move up"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="w-9 h-9"></span>
                                @endif

                                <!-- Move Down -->
                                @if($index < count($sectionOrder) - 1)
                                    <button 
                                        type="button"
                                        wire:click="moveDown({{ $sectionId }})"
                                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        title="Move down"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="w-9 h-9"></span>
                                @endif

                                <!-- Toggle Visibility -->
                                <button 
                                    type="button"
                                    wire:click="toggleVisibility({{ $sectionId }})"
                                    class="p-2 {{ $section['is_visible'] ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100' }} rounded-lg transition-colors"
                                    title="{{ $section['is_visible'] ? 'Visible' : 'Hidden' }}"
                                >
                                    @if($section['is_visible'])
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    @endif
                                </button>

                                <!-- Edit Link -->
                                <a 
                                    href="{{ route('admin.homepage-sections.edit', $sectionId) }}"
                                    class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit section"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No homepage sections</h3>
            <p class="mt-1 text-sm text-gray-500">Create sections in the Homepage Sections management page.</p>
        </div>
    @endif
</div>
