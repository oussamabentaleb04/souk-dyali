<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function cart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    public function index()
    {
        $cart = $this->cart()->load('items.product.sellerProfile', 'items.product.primaryImage');

        // Group items by seller, since checkout will create one order per seller
        $bySeller = $cart->items->groupBy(fn ($item) => $item->product->sellerProfile->shop_name);

        $total = $cart->items->sum(fn ($item) => $item->quantity * $item->product->price);

        return view('cart.index', compact('cart', 'bySeller', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        abort_unless(auth()->user()->role === 'buyer', 403, 'Only buyers can add products to a cart.');

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max(1, $product->stock)],
        ]);

        if ($product->stock < 1) {
            return back()->with('error', 'This product is out of stock.');
        }

        $cart = $this->cart();
        $item = $cart->items()->where('product_id', $product->id)->first();

        $newQuantity = ($item?->quantity ?? 0) + $data['quantity'];
        if ($newQuantity > $product->stock) {
            return back()->with('error', 'Only ' . $product->stock . ' left in stock.');
        }

        if ($item) {
            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create(['product_id' => $product->id, 'quantity' => $data['quantity']]);
        }

        return back()->with('success', 'Added to cart.');
    }

    public function update(Request $request, \App\Models\CartItem $cartItem)
    {
        abort_unless($cartItem->cart->user_id === auth()->id(), 403);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max(1, $cartItem->product->stock)],
        ]);

        $cartItem->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(\App\Models\CartItem $cartItem)
    {
        abort_unless($cartItem->cart->user_id === auth()->id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Removed from cart.');
    }
}