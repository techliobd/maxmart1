<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use App\Http\Requests\Admin\FlashSaleStoreRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('products')
            ->latest()
            ->paginate(20);

        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->orderBy('name')->get();

        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(FlashSaleStoreRequest $request)
    {
        $data = $request->validated();

        // Convert dates to proper format
        $data['starts_at'] = Carbon::parse($data['starts_at']);
        $data['ends_at'] = Carbon::parse($data['ends_at']);

        $flashSale = FlashSale::create($data);

        // Attach products
        if ($request->filled('product_ids')) {
            $flashSale->products()->attach($request->product_ids);
        }

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale created successfully.');
    }

    public function show(FlashSale $flashSale)
    {
        $flashSale->load('products');

        $stats = [
            'total_products' => $flashSale->products()->count(),
            'is_active' => $flashSale->isActive(),
            'time_remaining' => $flashSale->ends_at?->diffForHumans(),
        ];

        return view('admin.flash-sales.show', compact('flashSale', 'stats'));
    }

    public function edit(FlashSale $flashSale)
    {
        $products = Product::where('status', 'active')->orderBy('name')->get();
        $flashSale->load('products');

        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $data = $request->all();

        // Convert dates to proper format
        $data['starts_at'] = Carbon::parse($data['starts_at']);
        $data['ends_at'] = Carbon::parse($data['ends_at']);

        $flashSale->update($data);

        // Sync products
        if ($request->filled('product_ids')) {
            $flashSale->products()->sync($request->product_ids);
        } else {
            $flashSale->products()->detach();
        }

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale updated successfully.');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->products()->detach();
        $flashSale->delete();

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale deleted successfully.');
    }

    public function toggleStatus(FlashSale $flashSale)
    {
        $flashSale->update(['is_active' => !$flashSale->is_active]);

        return back()->with('success', 'Flash sale status updated successfully.');
    }

    public function addProduct(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (!$flashSale->products()->where('product_id', $request->product_id)->exists()) {
            $flashSale->products()->attach($request->product_id);
        }

        return back()->with('success', 'Product added to flash sale successfully.');
    }

    public function removeProduct(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $flashSale->products()->detach($request->product_id);

        return back()->with('success', 'Product removed from flash sale successfully.');
    }
}
