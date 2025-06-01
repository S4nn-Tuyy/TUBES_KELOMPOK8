@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ Storage::url($product->image) }}" class="img-fluid rounded" 
                alt="{{ $product->name }}" style="max-height: 400px; width: 100%; object-fit: cover;">
        </div>
        <div class="col-md-6">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <div class="d-flex gap-2">
                        @if(Auth::check() && Auth::id() !== $product->user_id)
                            <a href="{{ route('messages.create', $product) }}" class="btn btn-outline-primary">
                                <i class="fas fa-comments"></i> Chat with Seller
                            </a>
                        @endif
                        @if(Auth::check() && Auth::id() === $product->user_id)
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <p class="text-muted mb-4">Posted by {{ $product->user->name }}</p>

                <div class="mb-4">
                    <h4 class="text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                    <div class="d-flex gap-2 mb-3">
                        <span class="badge bg-{{ $product->condition == 'new' ? 'success' : 'warning' }}">
                            {{ ucfirst($product->condition) }}
                        </span>
                        <span class="badge bg-info">Stock: {{ $product->stock }}</span>
                        <span class="badge bg-secondary">{{ ucfirst($product->category) }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Description</h5>
                    <p>{{ $product->description }}</p>
                </div>

                @if($product->user_id !== Auth::id())
                    @if($product->stock > 0)
                        <form action="{{ route('transactions.store') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                    id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                    id="shipping_address" name="shipping_address" rows="3" required></textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Buy Now</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            This product is currently out of stock.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if($product->reviews->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3>Reviews</h3>
                @foreach($product->reviews as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-subtitle mb-2 text-muted">{{ $review->user->name }}</h6>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="card-text">{{ $review->comment }}</p>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection 