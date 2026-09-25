@extends('layouts.app')

@section('content')
<div class="text-center py-4">
    <h1 class="display-4 fw-bold">🛍️ Souk Dyali</h1>
    <p class="lead text-secondary">Authentic Moroccan crafts, straight from local artisans to your door.</p>
    <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Get started</a>
    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
</div>
@endsection