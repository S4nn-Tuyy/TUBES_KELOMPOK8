@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">My Messages</h5>
                </div>
                <div class="card-body">
                    @if($conversations->isEmpty())
                        <div class="alert alert-info">
                            You don't have any messages yet.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($conversations as $userId => $messages)
                                @php
                                    $otherUser = $messages->first()->sender_id === Auth::id() 
                                        ? $messages->first()->receiver 
                                        : $messages->first()->sender;
                                    $lastMessage = $messages->first();
                                    $unreadCount = $messages->where('receiver_id', Auth::id())
                                        ->whereNull('read_at')
                                        ->count();
                                @endphp
                                <a href="{{ route('messages.show', $otherUser) }}" 
                                    class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $otherUser->name }}</h6>
                                            <p class="mb-1 text-muted small">
                                                @if($lastMessage->product_id)
                                                    <span class="badge bg-info">Re: {{ $lastMessage->product->name }}</span>
                                                @endif
                                                {{ Str::limit($lastMessage->message, 50) }}
                                            </p>
                                            <small class="text-muted">
                                                {{ $lastMessage->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if($unreadCount > 0)
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 