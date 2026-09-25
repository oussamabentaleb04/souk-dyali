@extends('layouts.app')

@section('title', 'My dashboard')

@section('content')
<h2>Welcome, {{ auth()->user()->name }} 👋</h2>
<p class="text-secondary">Product browsing and cart come in the next phase.</p>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card"><div class="card-body">
            <div class="text-secondary small">My orders</div>
            <div class="fs-2">{{ $ordersCount }}</div>
        </div></div>
    </div>
</div>
@if(! auth()->user()->sellerProfile)
    <div class="mt-4">
        <a href="{{ route('seller.apply') }}" class="btn btn-outline-warning">Become a seller</a>
    </div>
@endif
@endsection