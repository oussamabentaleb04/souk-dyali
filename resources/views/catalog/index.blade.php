@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<h2 class="mb-3">Browse Moroccan crafts</h2>

<form method="GET" action="{{ route('catalog.index') }}" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search products...">
    </div>
    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->icon }} {{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="region" class="form-select">
            <option value="">All regions</option>
            @foreach($regions as $r)
                <option value="{{ $r->id }}" @selected(request('region') == $r->id)>{{ $r->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-1 d-grid">
        <button class="btn btn-primary">Go</button>
    </div>
</form>

<p class="text-secondary">{{ $products->total() }} product(s) found</p>

<div class="row g-3">
    @forelse($products as $product)
        <div class="col-md-4 col-lg-3">
            <div class="card h-100">
                @if($product->primaryImage)
                    <img src="{{ asset('storage/' . $product->primaryImage->path) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">🛍️</div>
                @endif
                <div class="card-body">
                    <h6>{{ $product->title }}</h6>
                    <p class="text-secondary small mb-1">{{ $product->sellerProfile->shop_name }}</p>
                    <p class="fs-5 text-info mb-2">{{ number_format($product->price, 0) }} DH</p>
                    <a href="{{ route('catalog.show', $product) }}" class="btn btn-sm btn-outline-primary">View</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-secondary">No products match your search.</div></div>
    @endforelse
</div>

<div class="mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
@endsection