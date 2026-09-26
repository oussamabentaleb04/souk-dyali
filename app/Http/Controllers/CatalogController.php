<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'region', 'sellerProfile', 'primaryImage'])
            ->where('is_active', true)
            ->where('stock', '>', 0);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', (int) $request->input('category'));
        }
        if ($request->filled('region')) {
            $query->where('region_id', (int) $request->input('region'));
        }

        return view('catalog.index', [
            'products' => $query->latest()->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return view('catalog.show', [
            'product' => $product->load(['category', 'region', 'sellerProfile', 'images', 'reviews.user']),
        ]);
    }

    public function shop(\App\Models\SellerProfile $sellerProfile)
    {
        abort_unless($sellerProfile->isApproved(), 404);

        return view('catalog.shop', [
            'shop' => $sellerProfile,
            'products' => Product::where('seller_profile_id', $sellerProfile->id)
                ->where('is_active', true)
                ->with(['primaryImage', 'category'])
                ->paginate(12),
        ]);
    }
}