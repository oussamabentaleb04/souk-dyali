@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<a href="{{ route('orders.index') }}" class="text-secondary small text-decoration-none">&larr; My orders</a>

<div class="card mt-2" style="max-width: 640px;">
    <div class="card-body p-4">
        <h3>Order #{{ $order->id }}</h3>
        <p class="text-secondary">{{ $order->sellerProfile->shop_name }} &middot; Status: <strong>{{ ucfirst($order->status) }}</strong></p>

        @foreach($order->items as $item)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between">
                    <span>{{ $item->product_title }} × {{ $item->quantity }}</span>
                    <span>{{ number_format($item->subtotal, 2) }} DH</span>
                </div>

                @if($order->status === 'delivered')
                    @if($item->review)
                        <div class="mt-2 small">
                            <div>{{ str_repeat('⭐', $item->review->rating) }}</div>
                            @if($item->review->comment)<p class="text-secondary mb-0">{{ $item->review->comment }}</p>@endif
                        </div>
                    @else
                        <form method="POST" action="{{ route('reviews.store', $item) }}" class="mt-2">
                            @csrf
                            <select name="rating" class="form-select form-select-sm mb-2" style="max-width: 150px;" required>
                                <option value="">Rate it...</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ str_repeat('⭐', $i) }}</option>
                                @endfor
                            </select>
                            <textarea name="comment" rows="2" class="form-control form-control-sm mb-2" placeholder="Optional comment"></textarea>
                            <button class="btn btn-sm btn-primary">Submit review</button>
                        </form>
                    @endif
                @endif
            </div>
        @endforeach

        <div class="d-flex justify-content-between fs-5">
            <span>Total</span>
            <span>{{ number_format($order->total, 2) }} DH</span>
        </div>

        <p class="small text-secondary mt-3">Shipping to: {{ $order->shipping_address }} &middot; {{ $order->phone }}</p>
        @if($order->notes)<p class="small text-secondary">Notes: {{ $order->notes }}</p>@endif
    </div>
</div>
@endsection