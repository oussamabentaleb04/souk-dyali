@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<a href="{{ route('orders.index') }}" class="text-secondary small text-decoration-none">&larr; My orders</a>

<div class="card mt-2" style="max-width: 640px;">
    <div class="card-body p-4">
        <h3>Order #{{ $order->id }}</h3>
        <p class="text-secondary">{{ $order->sellerProfile->shop_name }} &middot; Status: <strong>{{ ucfirst($order->status) }}</strong></p>

        <table class="table table-borderless">
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_title }} × {{ $item->quantity }}</td>
                    <td class="text-end">{{ number_format($item->subtotal, 2) }} DH</td>
                </tr>
            @endforeach
            <tr class="border-top">
                <th>Total</th>
                <th class="text-end">{{ number_format($order->total, 2) }} DH</th>
            </tr>
        </table>

        <p class="small text-secondary">Shipping to: {{ $order->shipping_address }} &middot; {{ $order->phone }}</p>
        @if($order->notes)<p class="small text-secondary">Notes: {{ $order->notes }}</p>@endif
    </div>
</div>
@endsection