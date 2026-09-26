<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()])
            ->load('items.product.sellerProfile');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cart->items->sum(fn ($item) => $item->quantity * $item->product->price);

        return view('checkout.show', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()])->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $orders = DB::transaction(function () use ($cart, $data) {
            $createdOrders = [];

            // Group cart items by seller, since each seller gets their own order
            $bySeller = $cart->items->groupBy(fn ($item) => $item->product->seller_profile_id);

            foreach ($bySeller as $sellerProfileId => $items) {
                $subtotal = 0;
                $orderItemsData = [];

                foreach ($items as $item) {
                    // Lock this product's row so two simultaneous checkouts
                    // can't both oversell the last units in stock.
                    $product = Product::whereKey($item->product_id)->lockForUpdate()->first();

                    if (! $product || $product->stock < $item->quantity) {
                        throw new \RuntimeException("Not enough stock for \"{$item->product->title}\". Only {$product?->stock} left.");
                    }

                    $product->decrement('stock', $item->quantity);

                    $lineTotal = $item->quantity * $product->price;
                    $subtotal += $lineTotal;

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'product_title' => $product->title, // snapshot
                        'unit_price' => $product->price,     // snapshot
                        'quantity' => $item->quantity,
                        'subtotal' => $lineTotal,
                    ];
                }

                $order = Order::create([
                    'buyer_id' => auth()->id(),
                    'seller_profile_id' => $sellerProfileId,
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'total' => $subtotal, // no shipping/discount logic yet
                    'shipping_address' => $data['shipping_address'],
                    'phone' => $data['phone'],
                    'notes' => $data['notes'] ?? null,
                ]);

                $order->items()->createMany($orderItemsData);
                $createdOrders[] = $order;
            }

            // Empty the cart now that orders are created
            $cart->items()->delete();

            return $createdOrders;
        });

        return redirect()->route('orders.index')->with('success', count($orders) . ' order(s) placed successfully!');
    }
}