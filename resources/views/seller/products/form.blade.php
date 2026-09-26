@extends('layouts.app')

@section('title', $product->exists ? 'Edit product' : 'Add product')

@section('content')
<a href="{{ route('seller.products.index') }}" class="text-secondary small text-decoration-none">&larr; My products</a>

<div class="card mt-2" style="max-width: 720px;">
    <div class="card-body p-4">
        <h3 class="mb-3">{{ $product->exists ? 'Edit ' . $product->title : 'Add a product' }}</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $product->exists ? route('seller.products.update', $product) : route('seller.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if($product->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" value="{{ old('title', $product->title) }}" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Choose...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Region</label>
                    <select name="region_id" class="form-select">
                        <option value="">None</option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}" @selected(old('region_id', $product->region_id) == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control" required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (DH)</label>
                    <input type="number" step="0.01" min="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Photos (jpg, png or webp, max 2MB each)</label>
                <input type="file" name="images[]" class="form-control" accept="image/png,image/jpeg,image/webp" multiple>
                @if($product->exists && $product->images->isNotEmpty())
                    <div class="d-flex gap-2 mt-2 flex-wrap">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->path) }}" style="width: 70px; height: 70px; object-fit: cover;" class="rounded">
                        @endforeach
                    </div>
                    <div class="text-secondary small mt-1">Current photos — new uploads will be added alongside them.</div>
                @endif
            </div>

            <div class="form-check mb-4">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active"
                    @checked(old('is_active', $product->is_active ? '1' : '0') == '1')>
                <label class="form-check-label" for="active">Visible in the shop</label>
            </div>

            <button class="btn btn-primary">{{ $product->exists ? 'Save changes' : 'Add product' }}</button>
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection