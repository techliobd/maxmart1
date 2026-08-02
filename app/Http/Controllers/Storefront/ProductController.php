<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use App\Models\ProductQuestion;
use App\Models\ProductAttribute;
use App\Services\ProductService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected SeoService $seoService
    ) {}

    public function show(Request $request, Category $category, Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        // Load related data
        $product->load([
            'images',
            'category',
            'brand',
            'attributes.attribute',
            'variations.attributeValues.attribute',
            'reviews.customer'
        ]);

        // Get reviews with stats
        $reviews = $product->reviews()
            ->with('customer')
            ->latest()
            ->paginate(10);

        $reviewStats = [
            'average' => $product->reviews()->avg('rating') ?? 0,
            'count' => $product->reviews()->count(),
            'distribution' => collect(range(5, 1))->mapWithKeys(fn($star) => [
                $star => $product->reviews()->where('rating', $star)->count()
            ])
        ];

        // Get questions and answers
        $questions = $product->questions()
            ->with('answers.admin')
            ->latest()
            ->get();

        // Related products
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->where('is_active', true)
            ->with('primaryImage')
            ->limit(4)
            ->get();

        // Generate SEO data
        $seoData = $this->seoService->generateProductSchema($product);

        return view('storefront.products.show', compact(
            'product',
            'category',
            'reviews',
            'reviewStats',
            'questions',
            'relatedProducts',
            'seoData'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('primaryImage')
            ->limit(10)
            ->get();

        return response()->json([
            'products' => $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->old_price && $p->old_price > $p->price ? $p->price : $p->price,
                'image' => $p->primaryImage?->url,
            ])
        ]);
    }

    public function quickView(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['images', 'variations.attributeValues.attribute']);

        return view('storefront.products.quick-view', compact('product'));
    }

    public function getVariationPrice(Request $request, Product $product)
    {
        $variationId = $request->input('variation_id');
        
        $variation = $product->variations()->find($variationId);
        
        if (!$variation) {
            return response()->json(['error' => 'Invalid variation'], 404);
        }

        return response()->json([
            'price' => $variation->old_price && $variation->old_price > $variation->price ? $variation->price : $variation->price,
            'compare_at_price' => $variation->old_price ?: $variation->price,
            'stock' => $variation->stock_quantity,
            'sku' => $variation->sku,
            'available' => $variation->stock_quantity > 0
        ]);
    }
}
