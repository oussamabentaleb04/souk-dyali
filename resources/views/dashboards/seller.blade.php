@extends('layouts.app')

@section('title', 'Seller dashboard')

@section('content')
<h2>{{ $profile->shop_name }} 🎨</h2>
<p class="text-secondary">{{ $profile->region }}</p>

<div class="row g-3">
    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-secondary small">Products</div><div class="fs-4">{{ $productsCount }}</div>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-secondary small">Orders</div><div class="fs-4">{{ $ordersCount }}</div>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-secondary small">Pending orders</div><div class="fs-4">{{ $pendingOrders }}</div>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-secondary small">Revenue</div><div class="fs-4">{{ number_format($revenue, 0) }} DH</div>
    </div></div></div>
</div>
@endsection