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
        // Orders per day, last 14 days
        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->toDateString());
        $perDay = Order::where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->get()
            ->groupBy(fn ($o) => $o->created_at->toDateString())
            ->map->count();

        // Revenue by category (delivered orders only)
        $revenueByCategory = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.status', 'delivered')
            ->selectRaw('categories.name, sum(order_items.subtotal) as total')
            ->groupBy('categories.name')
            ->pluck('total', 'name');

        return view('dashboards.admin', [
            'usersCount' => User::count(),
            'sellersCount' => SellerProfile::where('status', 'approved')->count(),
            'pendingApplications' => SellerProfile::where('status', 'pending')->count(),
            'productsCount' => Product::count(),
            'ordersCount' => Order::count(),
            'revenue' => (float) Order::where('status', 'delivered')->sum('total'),
            'chartLabelsDays' => $days->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'))->values(),
            'chartOrdersPerDay' => $days->map(fn ($d) => $perDay->get($d, 0))->values(),
            'chartCategoryLabels' => $revenueByCategory->keys(),
            'chartCategoryValues' => $revenueByCategory->values(),
        ]);
    }
}