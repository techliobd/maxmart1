<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('shop.index');
        }

        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['primaryImage', 'category'])
            ->paginate(24);

        // Get related categories
        $categories = Category::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        // Get related brands
        $brands = Brand::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        return view('storefront.search.results', compact('products', 'query', 'categories', 'brands'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->with('primaryImage')
            ->limit(8)
            ->get()
            ->map(fn($p) => [
                'type' => 'product',
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->old_price && $p->old_price > $p->price ? $p->price : $p->price,
                'image' => $p->primaryImage?->url,
                'url' => route('products.show', ['category' => $p->category->slug ?? 'products', 'product' => $p])
            ]);

        $categories = Category::where('name', 'like', "%{$query}%")
            ->limit(4)
            ->get()
            ->map(fn($c) => [
                'type' => 'category',
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'url' => route('shop.category', $c)
            ]);

        $suggestions = $products->concat($categories)->take(10);

        return response()->json([
            'suggestions' => $suggestions,
            'query' => $query
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);

        $products = Product::where('is_active', true)
            ->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('sku', 'like', "%{$request->q}%");
            })
            ->with('primaryImage')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->old_price && $p->old_price > $p->price ? $p->price : $p->price,
                'image' => $p->primaryImage?->url,
                'url' => route('products.show', ['category' => $p->category->slug ?? 'products', 'product' => $p])
            ])
        ]);
    }
}
