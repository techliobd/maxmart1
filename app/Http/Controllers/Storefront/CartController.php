<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\CartAddRequest;
use App\Http\Requests\Storefront\CartUpdateRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(Request $request)
    {
        $cart = $this->cartService->getCurrentCart();
        
        if (!$cart) {
            return view('storefront.cart.empty');
        }

        $cartItems = $cart->items()->with(['product.primaryImage', 'variation.attributeValues'])->get();
        
        $subtotal = $cartItems->sum(fn($item) => $item->total);
        $shipping = 0; // Will be calculated at checkout
        $tax = 0; // Will be calculated at checkout
        $total = $subtotal + $shipping + $tax;

        return view('storefront.cart.index', compact('cart', 'cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function add(CartAddRequest $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        if (!$product->is_visible) {
            return back()->with('error', 'This product is not available.');
        }

        $variation = null;
        if ($request->filled('variation_id')) {
            $variation = ProductVariation::findOrFail($request->variation_id);
            
            if (!$this->cartService->validateVariationForProduct($variation, $product)) {
                return back()->with('error', 'Invalid product variation selected.');
            }
        }

        $cartItem = $this->cartService->addToCart(
            $product,
            $request->quantity,
            $variation
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully.',
                'cart_item' => [
                    'id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->total
                ],
                'cart_count' => $this->cartService->getCartItemCount()
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.');
    }

    public function update(CartUpdateRequest $request)
    {
        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        $updated = $this->cartService->updateCartItem($cartItem, $request->quantity);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully.',
                'cart_item' => [
                    'id' => $updated->id,
                    'quantity' => $updated->quantity,
                    'total' => $updated->total
                ],
                'cart_count' => $this->cartService->getCartItemCount(),
                'cart_total' => $this->cartService->getCartTotal()
            ]);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:carts,id'
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);
        $cartItem->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'cart_count' => $this->cartService->getCartItemCount(),
                'cart_total' => $this->cartService->getCartTotal()
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear(Request $request)
    {
        $cart = $this->cartService->getCurrentCart();
        
        if ($cart) {
            $cart->items()->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared.',
                'cart_count' => 0,
                'cart_total' => 0
            ]);
        }

        return back()->with('success', 'Cart cleared.');
    }

    public function count(Request $request)
    {
        return response()->json([
            'count' => $this->cartService->getCartItemCount()
        ]);
    }
}
