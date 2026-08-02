<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Http\Requests\Admin\CmsPageStoreRequest;
use App\Http\Requests\Admin\CmsPageUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = CmsPage::orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(CmsPageStoreRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($request->title);
        $data['meta_title'] = $data['meta_title'] ?? $request->title;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        CmsPage::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function show(CmsPage $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(CmsPage $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(CmsPageUpdateRequest $request, CmsPage $page)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($request->title);
        $data['meta_title'] = $data['meta_title'] ?? $request->title;

        if ($request->hasFile('featured_image')) {
            if ($page->featured_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        } else {
            unset($data['featured_image']);
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(CmsPage $page)
    {
        if ($page->featured_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    public function toggleStatus(CmsPage $page)
    {
        $page->update(['is_active' => !$page->is_active]);

        return back()->with('success', 'Page status updated successfully.');
    }
}
