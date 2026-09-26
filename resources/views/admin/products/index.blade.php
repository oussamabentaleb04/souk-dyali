@extends('layouts.app')

@section('title', 'Moderate products')

@section('content')
<h2 class="mb-3">All products</h2>

<div class="table-responsive">
    <table class="table align-middle">
        <thead><tr><th>Title</th><th>Seller</th><th>Category</th><th>Price</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->sellerProfile->shop_name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ number_format($product->price, 0) }} DH</td>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">Visible</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-light">{{ $product->is_active ? 'Hide' : 'Show' }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $products->links('pagination::bootstrap-5') }}
@endsection