<?php

namespace App\Http\Controllers;

class AdminProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', [
            'products' => \App\Models\Product::with(['sellerProfile', 'category'])->latest()->paginate(20),
        ]);
    }

    public function toggle(\App\Models\Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', $product->is_active ? 'Product shown publicly.' : 'Product hidden.');
    }
}