<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class MediaManager extends Component
{
    use WithFileUploads;

    public array $files = [];
    public string $currentFolder = '';
    public array $folderHistory = [];
    public bool $showUploadModal = false;
    public ?array $selectedFiles = null;
    public string $searchQuery = '';

    protected $listeners = ['refreshComponent' => 'loadFiles'];

    public function mount(): void
    {
        $this->loadFiles();
    }

    public function loadFiles(): void
    {
        $path = $this->currentFolder ? 'public/' . $this->currentFolder : 'public';
        
        try {
            $allFiles = Storage::files($path);
            $allDirectories = Storage::directories($path);
            
            $this->files = [
                'directories' => collect($allDirectories)->map(function ($dir) {
                    return basename($dir);
                })->toArray(),
                'files' => collect($allFiles)->map(function ($file) {
                    return [
                        'name' => basename($file),
                        'path' => $file,
                        'url' => Storage::url($file),
                        'type' => $this->getFileType($file),
                        'size' => Storage::size($file),
                    ];
                })->filter(function ($file) {
                    if (empty($this->searchQuery)) {
                        return true;
                    }
                    return stripos($file['name'], $this->searchQuery) !== false;
                })->toArray(),
            ];
        } catch (\Exception $e) {
            $this->files = ['directories' => [], 'files' => []];
        }
    }

    protected function getFileType(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            return 'image';
        } elseif (in_array($extension, ['pdf'])) {
            return 'pdf';
        } elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
            return 'video';
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
            return 'audio';
        }
        
        return 'file';
    }

    public function navigateToFolder(string $folder): void
    {
        $this->folderHistory[] = $this->currentFolder;
        $this->currentFolder = $this->currentFolder 
            ? rtrim($this->currentFolder, '/') . '/' . $folder 
            : $folder;
        $this->loadFiles();
    }

    public function goBack(): void
    {
        if (!empty($this->folderHistory)) {
            $this->currentFolder = array_pop($this->folderHistory);
            $this->loadFiles();
        }
    }

    public function goToParent(): void
    {
        if ($this->currentFolder) {
            $parts = explode('/', trim($this->currentFolder, '/'));
            array_pop($parts);
            $this->currentFolder = implode('/', $parts);
            $this->folderHistory = [];
            $this->loadFiles();
        }
    }

    public function createFolder(string $folderName): void
    {
        $this->validate([
            'folderName' => 'required|string|max:100|regex:/^[a-zA-Z0-9_-]+$/',
        ]);

        $path = $this->currentFolder 
            ? 'public/' . rtrim($this->currentFolder, '/') . '/' . $folderName 
            : 'public/' . $folderName;

        Storage::makeDirectory($path);
        $this->loadFiles();
        $this->dispatch('showSuccess', message: 'Folder created successfully!');
    }

    public function deleteFile(string $filePath): void
    {
        try {
            Storage::delete($filePath);
            $this->loadFiles();
            $this->dispatch('showSuccess', message: 'File deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: 'Failed to delete file: ' . $e->getMessage());
        }
    }

    public function deleteFolder(string $folderName): void
    {
        try {
            $path = $this->currentFolder 
                ? 'public/' . rtrim($this->currentFolder, '/') . '/' . $folderName 
                : 'public/' . $folderName;
            
            Storage::deleteDirectory($path);
            $this->loadFiles();
            $this->dispatch('showSuccess', message: 'Folder deleted successfully!');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: 'Failed to delete folder: ' . $e->getMessage());
        }
    }

    public function selectFile(array $file): void
    {
        $this->selectedFiles = [$file];
        $this->dispatch('fileSelected', file: $file);
    }

    public function render()
    {
        return view('livewire.admin.media-manager');
    }
}
