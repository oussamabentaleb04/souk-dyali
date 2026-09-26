<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SellerOrderController extends Controller
{
    public function index()
    {
        $profile = auth()->user()->sellerProfile;
        abort_unless($profile && $profile->isApproved(), 403);

        return view('seller.orders.index', [
            'orders' => Order::with(['buyer', 'items'])
                ->where('seller_profile_id', $profile->id)
                ->latest()
                ->get(),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $profile = auth()->user()->sellerProfile;
        abort_unless($profile && (int) $order->seller_profile_id === (int) $profile->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'shipped', 'delivered', 'cancelled'])],
        ]);

        // Only allow sensible forward transitions, not jumping backward
        $allowed = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
        ];

        if (! in_array($data['status'], $allowed[$order->status] ?? [])) {
            return back()->with('error', "Cannot change status from {$order->status} to {$data['status']}.");
        }

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Order status updated.');
    }
}