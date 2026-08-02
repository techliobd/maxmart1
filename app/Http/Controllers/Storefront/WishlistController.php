<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to view your wishlist.');
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with(['product.primaryImage', 'product.category'])
            ->latest()
            ->paginate(24);

        return view('storefront.wishlist.index', compact('wishlist'));
    }

    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from wishlist'
            ]);
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to wishlist'
            ]);
        }
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            return back()->with('success', 'Added to wishlist');
        }

        return back()->with('info', 'Product already in wishlist');
    }

    public function remove(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return back()->with('success', 'Removed from wishlist');
    }

    public function count(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Wishlist::where('user_id', Auth::id())->count();
        
        return response()->json(['count' => $count]);
    }
}
