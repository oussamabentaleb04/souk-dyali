<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect()->route('buyer.dashboard');
    }

    public function buyer()
    {
        return view('dashboards.buyer', [
            'ordersCount' => Order::where('buyer_id', auth()->id())->count(),
        ]);
    }

    public function seller()
    {
        $profile = auth()->user()->sellerProfile;

        // Not applied yet, or still pending/rejected — send to the application page
        if (! $profile || ! $profile->isApproved()) {
            return redirect()->route('seller.apply');
        }

        return view('dashboards.seller', [
            'profile' => $profile,
            'productsCount' => Product::where('seller_profile_id', $profile->id)->count(),
            'ordersCount' => Order::where('seller_profile_id', $profile->id)->count(),
            'pendingOrders' => Order::where('seller_profile_id', $profile->id)->where('status', 'pending')->count(),
            'revenue' => (float) Order::where('seller_profile_id', $profile->id)->where('status', 'delivered')->sum('total'),
        ]);
    }

    public function admin()
    {
        return view('dashboards.admin', [
            'usersCount' => User::count(),
            'sellersCount' => SellerProfile::where('status', 'approved')->count(),
            'pendingApplications' => SellerProfile::where('status', 'pending')->count(),
            'productsCount' => Product::count(),
            'ordersCount' => Order::count(),
            'revenue' => (float) Order::where('status', 'delivered')->sum('total'),
        ]);
    }
}