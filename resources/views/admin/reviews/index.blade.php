@extends('layouts.app')

@section('title', 'Moderate reviews')

@section('content')
<h2 class="mb-3">Reviews</h2>

<div class="table-responsive">
    <table class="table align-middle">
        <thead><tr><th>User</th><th>Product</th><th>Rating</th><th>Comment</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->user->name }}</td>
                    <td>{{ $review->product->title }}</td>
                    <td>{{ str_repeat('⭐', $review->rating) }}</td>
                    <td class="small">{{ $review->comment ?: '-' }}</td>
                    <td>
                        @if($review->is_visible)
                            <span class="badge bg-success">Visible</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-light">{{ $review->is_visible ? 'Hide' : 'Show' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-secondary">No reviews yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $reviews->links('pagination::bootstrap-5') }}
@endsection