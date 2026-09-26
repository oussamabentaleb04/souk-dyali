@extends('layouts.app')

@section('title', 'My cart')

@section('content')
<h2 class="mb-4">My cart</h2>

@if($cart->items->isEmpty())
    <div class="alert alert-secondary">Your cart is empty. <a href="{{ route('catalog.index') }}">Browse products</a>.</div>
@else
    @foreach($bySeller as $shopName => $items)
        <h6 class="text-secondary mt-4">Sold by {{ $shopName }}</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->product->title }}</td>
                            <td>{{ number_format($item->product->price, 2) }} DH</td>
                            <td>
                                <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm" style="width: 70px;">
                                    <button class="btn btn-sm btn-outline-light">Update</button>
                                </form>
                            </td>
                            <td>{{ number_format($item->quantity * $item->product->price, 2) }} DH</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="d-flex justify-content-between align-items-center mt-4">
        <h4>Total: {{ number_format($total, 2) }} DH</h4>
        <a href="{{ route('checkout.show') }}" class="btn btn-primary btn-lg">Checkout</a>
    </div>
@endif
@endsection