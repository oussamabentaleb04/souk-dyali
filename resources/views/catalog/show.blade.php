@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($product->images->isNotEmpty())
            <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="img-fluid rounded mb-2" style="max-height: 400px; width: 100%; object-fit: cover;">
            <div class="d-flex gap-2 flex-wrap">
                @foreach($product->images->skip(1) as $img)
                    <img src="{{ asset('storage/' . $img->path) }}" style="width: 80px; height: 80px; object-fit: cover;" class="rounded">
                @endforeach
            </div>
        @else
            <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="height: 400px;">🛍️</div>
        @endif
    </div>
    <div class="col-md-6">
        <h2>{{ $product->title }}</h2>
        <p class="text-secondary">
            Sold by <a href="{{ route('catalog.shop', $product->sellerProfile) }}">{{ $product->sellerProfile->shop_name }}</a>
            @if($product->region) &middot; {{ $product->region->name }} @endif
        </p>
        <p class="fs-3 text-info">{{ number_format($product->price, 2) }} DH</p>
        <p>{{ $product->description }}</p>
        <p class="small text-secondary">{{ $product->stock }} in stock</p>

        @auth
            @if(auth()->user()->role === 'buyer' && $product->stock > 0)
                <form method="POST" action="#" class="d-flex gap-2">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width: 100px;">
                    <button class="btn btn-primary" disabled title="Cart coming in the next phase">Add to cart</button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-light">Login to buy</a>
        @endauth
    </div>
</div>

<hr class="my-5">

<h4>Reviews</h4>
@forelse($product->reviews->where('is_visible', true) as $review)
    <div class="card mb-2"><div class="card-body py-2">
        <div>{{ str_repeat('⭐', $review->rating) }}</div>
        @if($review->comment)<p class="mb-1">{{ $review->comment }}</p>@endif
        <div class="text-secondary small">{{ $review->user->name }}</div>
        @if($review->seller_reply)
            <div class="mt-2 ps-3 border-start border-secondary small text-secondary">Seller: {{ $review->seller_reply }}</div>
        @endif
    </div></div>
@empty
    <p class="text-secondary">No reviews yet.</p>
@endforelse
@endsection