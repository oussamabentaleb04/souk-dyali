@extends('layouts.app')

@section('title', 'Application status')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4 text-center">
                <h3 class="mb-3">{{ $profile->shop_name }}</h3>
                @if($profile->status === 'pending')
                    <span class="badge bg-warning text-dark fs-6">Pending review</span>
                    <p class="text-secondary mt-3">Your application is being reviewed by our team.</p>
                @elseif($profile->status === 'approved')
                    <span class="badge bg-success fs-6">Approved</span>
                    <p class="text-secondary mt-3">Your shop is live. <a href="{{ route('dashboard') }}">Go to your dashboard</a>.</p>
                @else
                    <span class="badge bg-danger fs-6">Rejected</span>
                    <p class="text-secondary mt-3">Unfortunately your application was not approved.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection