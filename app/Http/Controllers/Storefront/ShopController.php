<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $query = Product::where('is_visible', true)
            ->with(['primaryImage', 'category', 'brand']);

        // Category filter
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->descendants()->pluck('id');
                $categoryIds[] = $category->id;
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand_id', function($q) use ($request) {
                $q->select('id')->from('brands')->where('slug', $request->brand);
            });
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Attribute filters
        if ($request->filled('attributes')) {
            foreach ($request->attributes as $attributeId => $values) {
                $query->whereHas('attributes', function($q) use ($attributeId, $values) {
                    $q->where('attribute_id', $attributeId)
                      ->whereIn('value', $values);
                });
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortField = match($request->sort) {
            'price_low' => ['price', 'asc'],
            'price_high' => ['price', 'desc'],
            'newest' => ['created_at', 'desc'],
            'popular' => ['sales_count', 'desc'],
            default => ['created_at', 'desc'],
        };
        $query->orderBy(...$sortField);

        $products = $query->paginate(24)->withQueryString();

        $categories = Category::whereNull('parent_id')->get();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return view('storefront.shop.index', compact(
            'products',
            'categories',
            'brands',
            'attributes'
        ));
    }

    public function category(Category $category)
    {
        $products = $category->products()
            ->where('is_visible', true)
            ->with(['primaryImage', 'brand'])
            ->paginate(24);

        return view('storefront.shop.category', compact('category', 'products'));
    }

    public function brand(Brand $brand)
    {
        $products = $brand->products()
            ->where('is_visible', true)
            ->with(['primaryImage', 'category'])
            ->paginate(24);

        return view('storefront.shop.brand', compact('brand', 'products'));
    }
}
