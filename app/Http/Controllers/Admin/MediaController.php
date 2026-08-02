<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $folder = $request->input('folder', '');
        $files = [];
        $folders = [];

        $path = 'media/' . ($folder ? $folder . '/' : '');

        if (Storage::disk('public')->exists($path)) {
            $items = Storage::disk('public')->directories($path);
            
            foreach ($items as $item) {
                $folders[] = [
                    'name' => basename($item),
                    'path' => $item,
                ];
            }

            $fileItems = Storage::disk('public')->files($path);
            
            foreach ($fileItems as $file) {
                $files[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'url' => Storage::disk('public')->url($file),
                    'size' => Storage::disk('public')->size($file),
                    'type' => mime_content_type(Storage::disk('public')->path($file)),
                    'created_at' => Storage::disk('public')->lastModified($file),
                ];
            }
        }

        return view('admin.media.index', compact('files', 'folders', 'folder'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10240',
            'folder' => 'nullable|string',
        ]);

        $uploadedFiles = [];
        $folder = $request->input('folder', '');
        $basePath = 'media/' . ($folder ? $folder . '/' : '');

        foreach ($request->file('files') as $file) {
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->extension();
            $path = $file->storeAs($basePath, $filename, 'public');
            
            $uploadedFiles[] = [
                'name' => $filename,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
            ];
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
        ]);
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent' => 'nullable|string',
        ]);

        $parentPath = $request->input('parent', '');
        $folderPath = 'media/' . ($parentPath ? $parentPath . '/' : '') . $request->name;

        Storage::disk('public')->makeDirectory($folderPath);

        return back()->with('success', 'Folder created successfully.');
    }

    public function show($path)
    {
        $fullPath = urldecode($path);
        
        if (!Storage::disk('public')->exists($fullPath)) {
            abort(404);
        }

        $file = [
            'name' => basename($fullPath),
            'path' => $fullPath,
            'url' => Storage::disk('public')->url($fullPath),
            'size' => Storage::disk('public')->size($fullPath),
            'type' => mime_content_type(Storage::disk('public')->path($fullPath)),
            'created_at' => Storage::disk('public')->lastModified($fullPath),
        ];

        return view('admin.media.show', compact('file'));
    }

    public function destroy($path)
    {
        $fullPath = urldecode($path);
        
        if (!Storage::disk('public')->exists($fullPath)) {
            return back()->withErrors(['error' => 'File not found.']);
        }

        Storage::disk('public')->delete($fullPath);

        return back()->with('success', 'File deleted successfully.');
    }

    public function rename(Request $request, $path)
    {
        $request->validate([
            'new_name' => 'required|string|max:255',
        ]);

        $fullPath = urldecode($path);
        $directory = dirname($fullPath);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $newPath = $directory . '/' . $request->new_name . ($extension ? '.' . $extension : '');

        if (Storage::disk('public')->exists($newPath)) {
            return back()->withErrors(['error' => 'A file with this name already exists.']);
        }

        Storage::disk('public')->move($fullPath, $newPath);

        return back()->with('success', 'File renamed successfully.');
    }

    public function move(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|string',
            'destination' => 'required|string',
        ]);

        foreach ($request->files as $file) {
            $filename = basename($file);
            $newPath = 'media/' . ($request->destination ? $request->destination . '/' : '') . $filename;
            
            Storage::disk('public')->move($file, $newPath);
        }

        return back()->with('success', 'Files moved successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $results = [];

        if (empty($query)) {
            return response()->json(['files' => []]);
        }

        $allFiles = Storage::disk('public')->allFiles('media');
        
        foreach ($allFiles as $file) {
            if (stripos(basename($file), $query) !== false) {
                $results[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'url' => Storage::disk('public')->url($file),
                ];
            }
        }

        return response()->json(['files' => array_slice($results, 0, 50)]);
    }
}
