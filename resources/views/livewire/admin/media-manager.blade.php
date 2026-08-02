<div class="bg-white border border-gray-200 rounded-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Media Manager</h3>
        <button 
            type="button"
            wire:click="$set('showUploadModal', true)"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
        >
            Upload Files
        </button>
    </div>

    <!-- Breadcrumb & Navigation -->
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
        <div class="flex items-center gap-2">
            @if($currentFolder || count($folderHistory) > 0)
                <button 
                    type="button"
                    wire:click="goBack"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg"
                    title="Go back"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </button>
            @endif
            
            @if($currentFolder)
                <button 
                    type="button"
                    wire:click="goToParent"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg"
                    title="Parent folder"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </button>
            @endif

            <nav class="flex items-center gap-1 text-sm">
                <button 
                    type="button"
                    wire:click="$set('currentFolder', ''); $set('folderHistory', [])"
                    class="text-blue-600 hover:text-blue-800 font-medium"
                >
                    Media Library
                </button>
                @if($currentFolder)
                    <span class="text-gray-400">/</span>
                    @foreach(explode('/', $currentFolder) as $part)
                        <span class="text-gray-600">{{ $part }}</span>
                        @if(!$loop->last)
                            <span class="text-gray-400">/</span>
                        @endif
                    @endforeach
                @endif
            </nav>
        </div>

        <!-- Search -->
        <div class="relative">
            <input 
                type="text"
                wire:model.live.debounce.300ms="searchQuery"
                placeholder="Search files..."
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            >
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Folders -->
    @if(count($files['directories']) > 0)
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Folders</h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($files['directories'] as $folder)
                    <div 
                        wire:click="navigateToFolder('{{ $folder }}')"
                        class="group cursor-pointer p-4 border border-gray-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-colors text-center"
                    >
                        <svg class="mx-auto h-12 w-12 text-yellow-500 group-hover:text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900 truncate">{{ $folder }}</p>
                        <button 
                            type="button"
                            wire:click.stop="deleteFolder('{{ $folder }}')"
                            class="mt-2 text-xs text-red-600 hover:text-red-800 opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            Delete folder
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Files -->
    @if(count($files['files']) > 0)
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-3">Files ({{ count($files['files']) }})</h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($files['files'] as $file)
                    <div class="group relative border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <!-- File Preview -->
                        <div 
                            wire:click="selectFile(@js($file))"
                            class="aspect-square bg-gray-100 cursor-pointer"
                        >
                            @if($file['type'] === 'image')
                                <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    @if($file['type'] === 'pdf')
                                        <svg class="h-12 w-12 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                    @elseif($file['type'] === 'video')
                                        <svg class="h-12 w-12 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                        </svg>
                                    @else
                                        <svg class="h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- File Info -->
                        <div class="p-3">
                            <p class="text-xs font-medium text-gray-900 truncate" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($file['size'] / 1024, 1) }} KB</p>
                        </div>

                        <!-- Actions -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                            <a 
                                href="{{ $file['url'] }}" 
                                target="_blank"
                                class="p-1.5 bg-white rounded shadow hover:bg-gray-50"
                                title="View"
                            >
                                <svg class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <button 
                                type="button"
                                wire:click="deleteFile('{{ $file['path'] }}')"
                                class="p-1.5 bg-white rounded shadow hover:bg-red-50"
                                title="Delete"
                            >
                                <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No files</h3>
            <p class="mt-1 text-sm text-gray-500">Upload files to get started.</p>
        </div>
    @endif
</div>
