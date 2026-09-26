<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Souk Dyali')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-black border-bottom border-secondary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">🛍️ Souk Dyali</a>
                <div class="d-flex gap-3 me-auto ms-4">
            <a class="nav-link text-light" href="{{ route('catalog.index') }}">Shop</a>
            @auth
                                @if(auth()->user()->role === 'seller')
                    <a class="nav-link text-light" href="{{ route('seller.products.index') }}">My products</a>
                    <a class="nav-link text-light" href="{{ route('seller.orders.index') }}">My orders</a>
                @endif
                @if(auth()->user()->role === 'buyer')
                    <a class="nav-link text-light" href="{{ route('cart.index') }}">Cart</a>
                    <a class="nav-link text-light" href="{{ route('orders.index') }}">My orders</a>
                @endif
            @endauth
        </div>
        <div class="d-flex gap-2 align-items-center">
            @auth
                <span class="text-secondary small">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <a class="btn btn-sm btn-outline-light" href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-danger">Logout</button>
                </form>
            @else
                <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Login</a>
                <a class="btn btn-sm btn-primary" href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>
</nav>

@auth
    @if(auth()->user()->role === 'admin')
        <div class="border-bottom border-secondary bg-body-tertiary">
            <div class="container d-flex gap-3 flex-wrap py-2 small">
                <span class="text-secondary">Admin</span>
                <a class="text-warning text-decoration-none" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="text-warning text-decoration-none" href="{{ route('admin.sellers.index') }}">Sellers</a>
                <a class="text-warning text-decoration-none" href="{{ route('admin.categories.index') }}">Categories</a>
                <a class="text-warning text-decoration-none" href="{{ route('admin.products.index') }}">Products</a>
                <a class="text-warning text-decoration-none" href="{{ route('admin.reviews.index') }}">Reviews</a>
            </div>
        </div>
    @endif
@endauth

<main class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
       @yield('content')
</main>
@stack('scripts')
</body>
</html>