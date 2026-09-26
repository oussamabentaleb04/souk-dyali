@extends('layouts.app')

@section('title', 'My orders')

@section('content')
<h2 class="mb-4">My orders</h2>

@forelse($orders as $order)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">Order #{{ $order->id }} — {{ $order->sellerProfile->shop_name }}</h6>
                    <p class="text-secondary small mb-0">{{ $order->created_at->format('d M Y H:i') }} &middot; {{ $order->items->count() }} item(s)</p>
                </div>
                <div class="text-end">
                    @php
                        $badge = ['pending' => 'bg-warning text-dark', 'confirmed' => 'bg-info text-dark', 'shipped' => 'bg-primary', 'delivered' => 'bg-success', 'cancelled' => 'bg-danger'][$order->status] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $badge }}">{{ ucfirst($order->status) }}</span>
                    <p class="mb-0 mt-1">{{ number_format($order->total, 2) }} DH</p>
                </div>
            </div>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-light mt-2">View details</a>
        </div>
    </div>
@empty
    <div class="alert alert-secondary">No orders yet. <a href="{{ route('catalog.index') }}">Start shopping</a>.</div>
@endforelse
@endsection