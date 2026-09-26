<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, OrderItem $orderItem)
    {
        abort_unless((int) $orderItem->order->buyer_id === (int) auth()->id(), 403);

        if ($orderItem->order->status !== 'delivered') {
            return back()->with('error', 'You can only review items from delivered orders.');
        }
        if ($orderItem->review()->exists()) {
            return back()->with('error', 'You already reviewed this item.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create($data + [
            'product_id' => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'user_id' => auth()->id(),
            'is_visible' => true,
        ]);

        return back()->with('success', 'Thanks for your review!');
    }
}