<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerProductController extends Controller
{
    private function sellerProfile()
    {
        $profile = auth()->user()->sellerProfile;
        abort_unless($profile && $profile->isApproved(), 403, 'Your seller account is not approved yet.');
        return $profile;
    }

    public function index()
    {
        $profile = $this->sellerProfile();

        return view('seller.products.index', [
            'products' => Product::where('seller_profile_id', $profile->id)
                ->with(['category', 'primaryImage'])
                ->latest()
                ->get(),
        ]);
    }

    public function create()
    {
        $this->sellerProfile();

        return view('seller.products.form', [
            'product' => new Product(['is_active' => true, 'stock' => 1]),
            'categories' => Category::orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $profile = $this->sellerProfile();
        $data = $this->validated($request);

        $product = Product::create($data + [
            'seller_profile_id' => $profile->id,
            'slug' => $this->uniqueSlug($data['title']),
        ]);

        $this->storeImages($request, $product);

        return redirect()->route('seller.products.index')->with('success', 'Product added.');
    }

    public function edit(Product $product)
    {
        $profile = $this->sellerProfile();
        abort_unless((int) $product->seller_profile_id === (int) $profile->id, 403);

        return view('seller.products.form', [
            'product' => $product->load('images'),
            'categories' => Category::orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $profile = $this->sellerProfile();
        abort_unless((int) $product->seller_profile_id === (int) $profile->id, 403);

        $data = $this->validated($request);
        $product->update($data);
        $this->storeImages($request, $product);

        return redirect()->route('seller.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $profile = $this->sellerProfile();
        abort_unless((int) $product->seller_profile_id === (int) $profile->id, 403);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'region_id' => ['nullable', 'exists:regions,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        unset($data['images']);

        return $data;
    }

    private function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => ! $hasPrimary,
            ]);
            $hasPrimary = true;
        }
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'product';
        $slug = $base;
        $i = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}