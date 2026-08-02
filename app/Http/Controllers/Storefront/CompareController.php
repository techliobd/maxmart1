<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $compareIds = session('compare_products', []);
        
        if (empty($compareIds)) {
            return view('storefront.compare.empty');
        }

        $products = Product::whereIn('id', $compareIds)
            ->where('is_visible', true)
            ->with(['primaryImage', 'category', 'brand', 'attributes.attribute'])
            ->get();

        // Get all attributes for comparison table
        $allAttributes = $products->flatMap(fn($p) => 
            $p->attributes->map(fn($pa) => $pa->attribute->name)
        )->unique()->values();

        return view('storefront.compare.index', compact('products', 'allAttributes'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $compareIds = session('compare_products', []);
        
        if (!in_array($request->product_id, $compareIds)) {
            if (count($compareIds) >= 4) {
                return back()->with('error', 'You can compare maximum 4 products at a time.');
            }
            
            $compareIds[] = $request->product_id;
            session(['compare_products' => $compareIds]);
            
            return back()->with('success', 'Product added to comparison.');
        }

        return back()->with('info', 'Product already in comparison list.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $compareIds = session('compare_products', []);
        $compareIds = array_diff($compareIds, [$request->product_id]);
        session(['compare_products' => array_values($compareIds)]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => count($compareIds)
            ]);
        }

        return back()->with('success', 'Product removed from comparison.');
    }

    public function clear(Request $request)
    {
        session()->forget('compare_products');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => 0
            ]);
        }

        return back()->with('success', 'Comparison list cleared.');
    }

    public function count(Request $request)
    {
        $count = count(session('compare_products', []));
        
        return response()->json(['count' => $count]);
    }
}
