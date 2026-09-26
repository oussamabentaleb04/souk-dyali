@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row">
    <div class="col-md-7">
        <h3 class="mb-3">Shipping details</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Shipping address</label>
                <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
            </div>
            <div class="alert alert-info small">Payment: Cash on Delivery. Your order will be split per seller.</div>
            <button class="btn btn-primary btn-lg w-100">Place order</button>
        </form>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h5>Order summary</h5>
                @foreach($cart->items as $item)
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $item->product->title }} × {{ $item->quantity }}</span>
                        <span>{{ number_format($item->quantity * $item->product->price, 2) }} DH</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fs-5">
                    <span>Total</span>
                    <span>{{ number_format($total, 2) }} DH</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection