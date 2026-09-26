<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => Order::with(['sellerProfile', 'items'])
                ->where('buyer_id', auth()->id())
                ->latest()
                ->get(),
        ]);
    }

    public function show(Order $order)
    {
        abort_unless((int) $order->buyer_id === (int) auth()->id(), 403);

        return view('orders.show', [
            'order' => $order->load(['sellerProfile', 'items.product', 'items.review']),
        ]);
    }
}