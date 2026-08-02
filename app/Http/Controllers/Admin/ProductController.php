<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\ProductImage;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::with('values')->orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'brands', 'attributes'));
    }

    public function store(ProductStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            
            // Handle main image
            if ($request->hasFile('main_image')) {
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            $product = Product::create($data);

            // Handle gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $index,
                        'is_main' => $index === 0,
                    ]);
                }
            }

            // Handle attributes
            if ($request->filled('attributes')) {
                foreach ($request->attributes as $attributeId => $valueIds) {
                    $product->attributes()->attach($attributeId, ['attribute_values' => json_encode($valueIds)]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'attributes', 'images', 'variations.attributeValues']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::with('values')->orderBy('name')->get();

        $product->load(['attributes', 'images', 'variations.attributeValues']);

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Handle main image update
            if ($request->hasFile('main_image')) {
                // Delete old image
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $data['main_image'] = $request->file('main_image')->store('products', 'public');
            } else {
                unset($data['main_image']);
            }

            $product->update($data);

            // Handle gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $product->images()->count() + $index,
                        'is_main' => false,
                    ]);
                }
            }

            // Update attributes
            if ($request->filled('attributes')) {
                $product->attributes()->detach();
                foreach ($request->attributes as $attributeId => $valueIds) {
                    $product->attributes()->attach($attributeId, ['attribute_values' => json_encode($valueIds)]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete images
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        $productIds = $request->product_ids;

        switch ($request->action) {
            case 'activate':
                Product::whereIn('id', $productIds)->update(['status' => 'active']);
                break;
            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['status' => 'inactive']);
                break;
            case 'delete':
                Product::destroy($productIds);
                break;
        }

        return back()->with('success', 'Products updated successfully.');
    }

    public function duplicate(Product $product)
    {
        DB::beginTransaction();

        try {
            $newProduct = $product->replicate();
            $newProduct->name = $product->name . ' (Copy)';
            $newProduct->sku = $product->sku . '-copy';
            $newProduct->save();

            // Copy images
            foreach ($product->images as $image) {
                $newImage = $image->replicate();
                $newImage->product_id = $newProduct->id;
                $newImage->save();
            }

            DB::commit();

            return redirect()->route('admin.products.edit', $newProduct)->with('success', 'Product duplicated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to duplicate product: ' . $e->getMessage()]);
        }
    }
}
