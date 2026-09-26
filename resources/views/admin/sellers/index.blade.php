@extends('layouts.app')

@section('title', 'Seller applications')

@section('content')
<h2 class="mb-3">Seller applications</h2>

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm {{ $currentStatus === '' ? 'btn-secondary' : 'btn-outline-secondary' }}">All</a>
    <a href="{{ route('admin.sellers.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $currentStatus === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
    <a href="{{ route('admin.sellers.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $currentStatus === 'approved' ? 'btn-success' : 'btn-outline-success' }}">Approved</a>
    <a href="{{ route('admin.sellers.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $currentStatus === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Rejected</a>
</div>

@forelse($sellers as $seller)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-1">{{ $seller->shop_name }}</h5>
                    <p class="text-secondary small mb-1">{{ $seller->user->name }} &middot; {{ $seller->user->email }} &middot; {{ $seller->region }}</p>
                    <p class="mb-2">{{ $seller->description }}</p>
                </div>
                @php
                    $badge = ['pending' => 'bg-warning text-dark', 'approved' => 'bg-success', 'rejected' => 'bg-danger'][$seller->status];
                @endphp
                <span class="badge {{ $badge }}">{{ ucfirst($seller->status) }}</span>
            </div>
            <div class="d-flex gap-2">
                @if($seller->status !== 'approved')
                    <form method="POST" action="{{ route('admin.sellers.status', $seller) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                @endif
                @if($seller->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.sellers.status', $seller) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button class="btn btn-sm btn-outline-danger">Reject</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-secondary">No applications found.</div>
@endforelse
@endsection