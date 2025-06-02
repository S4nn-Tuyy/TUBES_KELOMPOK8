@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Products</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" target="_blank">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>{{ $product->user->name }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $product->is_hidden ? 'bg-danger' : 'bg-success' }}">
                                        {{ $product->is_hidden ? 'Hidden' : 'Visible' }}
                                    </span>
                                </td>
                                <td>{{ $product->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $product->is_hidden ? 'btn-success' : 'btn-danger' }}">
                                            {{ $product->is_hidden ? 'Show' : 'Hide' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 