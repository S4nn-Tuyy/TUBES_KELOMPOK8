@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Start Conversation</h5>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary btn-sm">
                        Back to Product
                    </a>
                </div>
                <div class="card-body">
                    <div class="product-info mb-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <p class="text-muted mb-1">Seller: {{ $product->user->name }}</p>
                                <p class="text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('messages.store', $product->user) }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea name="message" id="message" rows="4" 
                                class="form-control @error('message') is-invalid @enderror" 
                                placeholder="Type your message here..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 