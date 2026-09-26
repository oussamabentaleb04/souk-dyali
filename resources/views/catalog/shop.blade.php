@extends('layouts.app')

@section('title', $shop->shop_name)

@section('content')
<h2>{{ $shop->shop_name }}</h2>
<p class="text-secondary">{{ $shop->region }}</p>
<p>{{ $shop->description }}</p>

<hr>

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
                    <p class="fs-5 text-info mb-2">{{ number_format($product->price, 0) }} DH</p>
                    <a href="{{ route('catalog.show', $product) }}" class="btn btn-sm btn-outline-primary">View</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-secondary">No products yet.</div></div>
    @endforelse
</div>

{{ $products->links('pagination::bootstrap-5') }}
@endsection