@extends('layouts.app')

@section('title', 'Become a seller')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <h3 class="mb-3">Become a seller</h3>
                <p class="text-secondary">Tell us about your shop. An admin will review your application.</p>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('seller.apply.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Shop name</label>
                        <input type="text" name="shop_name" value="{{ old('shop_name') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Region</label>
                        <input type="text" name="region" value="{{ old('region') }}" class="form-control" placeholder="e.g. Fès" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tell us about your craft</label>
                        <textarea name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                    </div>
                    <button class="btn btn-primary w-100">Submit application</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection