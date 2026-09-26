@extends('layouts.app')

@section('title', 'My orders')

@section('content')
<h2 class="mb-4">Orders to fulfill</h2>

@forelse($orders as $order)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">Order #{{ $order->id }} — {{ $order->buyer->name }}</h6>
                    <p class="text-secondary small mb-0">{{ $order->created_at->format('d M Y H:i') }} &middot; {{ $order->phone }}</p>
                    <p class="text-secondary small mb-0">{{ $order->shipping_address }}</p>
                </div>
                <div class="text-end">
                    @php
                        $badge = ['pending' => 'bg-warning text-dark', 'confirmed' => 'bg-info text-dark', 'shipped' => 'bg-primary', 'delivered' => 'bg-success', 'cancelled' => 'bg-danger'][$order->status] ?? 'bg-secondary';
                        $next = ['pending' => 'confirmed', 'confirmed' => 'shipped', 'shipped' => 'delivered'][$order->status] ?? null;
                    @endphp
                    <span class="badge {{ $badge }}">{{ ucfirst($order->status) }}</span>
                    <p class="mb-0 mt-1">{{ number_format($order->total, 2) }} DH</p>
                </div>
            </div>

            <ul class="list-unstyled small mt-2 mb-2">
                @foreach($order->items as $item)
                    <li>{{ $item->product_title }} × {{ $item->quantity }} — {{ number_format($item->subtotal, 2) }} DH</li>
                @endforeach
            </ul>

            <div class="d-flex gap-2">
                @if($next)
                    <form method="POST" action="{{ route('seller.orders.status', $order) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="{{ $next }}">
                        <button class="btn btn-sm btn-success">Mark as {{ ucfirst($next) }}</button>
                    </form>
                @endif
                @if(in_array($order->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('seller.orders.status', $order) }}" onsubmit="return confirm('Cancel this order?');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button class="btn btn-sm btn-outline-danger">Cancel</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-secondary">No orders yet.</div>
@endforelse
@endsection