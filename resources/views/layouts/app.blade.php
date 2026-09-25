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
        <div class="d-flex gap-2 align-items-center ms-auto">
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
<main class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>
</body>
</html>