@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')
<h2>Admin dashboard 👑</h2>

<div class="row g-3">
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Users</div><div class="fs-4">{{ $usersCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Approved sellers</div><div class="fs-4">{{ $sellersCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Pending applications</div><div class="fs-4">{{ $pendingApplications }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Products</div><div class="fs-4">{{ $productsCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Orders</div><div class="fs-4">{{ $ordersCount }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-secondary small">Revenue</div><div class="fs-4">{{ number_format($revenue, 0) }} DH</div>
    </div></div></div>
</div>
@endsection