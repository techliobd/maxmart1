<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::orderBy('name')->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(CategoryStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $data['slug'] = Str::slug($request->name);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        } else {
            unset($data['image']);
        }

        $data['slug'] = Str::slug($request->name);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete category with associated products.']);
        }

        if ($category->children()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete category with subcategories.']);
        }

        if ($category->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->categories as $item) {
                Category::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
