@extends('layouts.app')

@section('title', 'My products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">My products</h2>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">+ Add product</a>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->path) }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">🛍️</div>
                        @endif
                    </td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ number_format($product->price, 0) }} DH</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-end">
                            <a class="btn btn-sm btn-outline-light" href="{{ route('seller.products.edit', $product) }}">Edit</a>
                            <form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-secondary">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection