@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transaction Details</h5>
                    <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'cancelled' ? 'danger' : 'warning') }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($transaction->product->image)
                                <img src="{{ Storage::url($transaction->product->image) }}" 
                                    class="img-fluid rounded" alt="{{ $transaction->product->name }}">
                            @else
                                <div class="text-center p-4 bg-light rounded">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                    <p class="mt-2 text-muted">Product image not available</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $transaction->product_name }}</h4>
                            @if($transaction->product->exists)
                                <p class="text-muted">{{ $transaction->product->description }}</p>
                            @else
                                <p class="text-muted">(Product has been removed)</p>
                            @endif
                            
                            <div class="row mt-3">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Price per Item:</strong></p>
                                    <p>Rp {{ number_format($transaction->total_price / $transaction->quantity, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Quantity:</strong></p>
                                    <p>{{ $transaction->quantity }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Total Price:</strong></p>
                                    <p class="text-primary h5">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Payment Status:</strong></p>
                                    <span class="badge bg-{{ $transaction->payment_status == 'paid' ? 'success' : ($transaction->payment_status == 'refunded' ? 'info' : 'warning') }}">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Seller Information</h6>
                            <p class="mb-1">{{ $transaction->seller->name }}</p>
                            <p class="text-muted">{{ $transaction->seller->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Buyer Information</h6>
                            <p class="mb-1">{{ $transaction->buyer->name }}</p>
                            <p class="text-muted">{{ $transaction->buyer->email }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Shipping Address</h6>
                        <p>{{ $transaction->shipping_address }}</p>
                    </div>

                    <div class="d-flex gap-2">
                        @if($transaction->buyer_id === Auth::id())
                            @if($transaction->status === 'processing')
                                <form action="{{ route('transactions.confirm', $transaction) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        Confirm Receipt
                                    </button>
                                </form>
                            @endif

                            @if($transaction->status === 'completed' && !$transaction->review)
                                <div class="card mt-4 w-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">Write a Review</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('reviews.store', $transaction) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Rating</label>
                                                <div class="star-rating">
                                                    <div class="star-input">
                                                        <input type="radio" name="rating" value="5" id="rating-5">
                                                        <label for="rating-5" class="fas fa-star"></label>
                                                        <input type="radio" name="rating" value="4" id="rating-4">
                                                        <label for="rating-4" class="fas fa-star"></label>
                                                        <input type="radio" name="rating" value="3" id="rating-3">
                                                        <label for="rating-3" class="fas fa-star"></label>
                                                        <input type="radio" name="rating" value="2" id="rating-2">
                                                        <label for="rating-2" class="fas fa-star"></label>
                                                        <input type="radio" name="rating" value="1" id="rating-1">
                                                        <label for="rating-1" class="fas fa-star"></label>
                                                    </div>
                                                </div>
                                                @error('rating')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">Your Review</label>
                                                <textarea class="form-control @error('comment') is-invalid @enderror" 
                                                    id="comment" name="comment" rows="3" required 
                                                    minlength="10" maxlength="500"></textarea>
                                                @error('comment')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit Review</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if($transaction->seller_id === Auth::id() && $transaction->status === 'pending')
                            <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="processing">
                                <input type="hidden" name="payment_status" value="paid">
                                <button type="submit" class="btn btn-primary">
                                    Process Order
                                </button>
                            </form>
                        @endif

                        @if(in_array($transaction->status, ['pending', 'processing']))
                            <form action="{{ route('transactions.cancel', $transaction) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to cancel this transaction?')">
                                    Cancel Transaction
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            Back to Transactions
                        </a>
                    </div>

                    @if($transaction->review)
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Your Review</h5>
                                @if($transaction->review->user_id === Auth::id())
                                    <form action="{{ route('reviews.destroy', $transaction->review) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this review?')">
                                            Delete Review
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="review-stars mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $transaction->review->rating ? 'checked' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="card-text">{{ $transaction->review->comment }}</p>
                                <small class="text-muted">Posted {{ $transaction->review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 