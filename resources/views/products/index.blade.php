@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Products</h2>
        </div>
        <div class="col-md-4 text-end">
            @auth
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Add New Product
                </a>
            @endauth
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" 
                                placeholder="Search products..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                <option value="Books" {{ request('category') == 'Books' ? 'selected' : '' }}>Books</option>
                                <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="Fashion" {{ request('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                <option value="Food" {{ request('category') == 'Food' ? 'selected' : '' }}>Food</option>
                                <option value="Others" {{ request('category') == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="condition">
                                <option value="">All Conditions</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Used</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ Storage::url($product->image) }}" class="card-img-top" 
                        alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge bg-{{ $product->condition == 'new' ? 'success' : 'warning' }}">
                                {{ ucfirst($product->condition) }}
                            </span>
                            <span class="badge bg-secondary">{{ ucfirst($product->category) }}</span>
                            @if($product->is_hidden)
                                <span class="badge bg-danger">Disembunyikan</span>
                            @endif
                        </div>
                        <p class="card-text"><small class="text-muted">Oleh {{ $product->user->name }}</small></p>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No products found.
                </div>
            </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any JavaScript for enhanced filtering here
</script>
@endpush 