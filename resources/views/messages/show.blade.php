@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chat with {{ $user->name }}</h5>
                    <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm">
                        Back to Messages
                    </a>
                </div>
                <div class="card-body">
                    <div class="chat-messages mb-4" style="max-height: 400px; overflow-y: auto;">
                        @foreach($messages as $message)
                            <div class="message mb-3 {{ $message->sender_id === Auth::id() ? 'text-end' : '' }}">
                                <div class="message-content d-inline-block {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }} rounded p-2" 
                                    style="max-width: 70%;">
                                    @if($message->product_id)
                                        <div class="product-reference mb-2">
                                            <a href="{{ route('products.show', $message->product_id) }}" 
                                                class="text-decoration-none {{ $message->sender_id === Auth::id() ? 'text-white' : 'text-primary' }}">
                                                <small>Re: {{ $message->product->name }}</small>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="message-text">
                                        {{ $message->message }}
                                    </div>
                                    <div class="message-meta">
                                        <small class="{{ $message->sender_id === Auth::id() ? 'text-white-50' : 'text-muted' }}">
                                            {{ $message->created_at->format('M d, H:i') }}
                                            @if($message->read_at && $message->sender_id === Auth::id())
                                                · Read
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('messages.store', $user) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                placeholder="Type your message..." rows="2" required></textarea>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                        @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll to bottom of chat
    const chatMessages = document.querySelector('.chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Auto-resize textarea
    const textarea = document.querySelector('textarea[name="message"]');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
@endpush
@endsection 