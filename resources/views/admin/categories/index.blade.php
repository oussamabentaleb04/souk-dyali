@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<h2 class="mb-3">Categories</h2>

<div class="table-responsive mb-4">
    <table class="table align-middle">
        <thead><tr><th>Icon</th><th>Name</th><th>Products</th><th></th></tr></thead>
        <tbody>
            @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->icon }}</td>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->products_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h5>Add a category</h5>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="row g-2 align-items-end">
        @csrf
        <div class="col-md-2">
            <label class="form-label small text-secondary">Icon (emoji)</label>
            <input type="text" name="icon" class="form-control" placeholder="🏺">
        </div>
        <div class="col-md-6">
            <label class="form-label small text-secondary">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary">Add</button>
        </div>
    </form>
</div></div>
@endsection