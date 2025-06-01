<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view messages.');
        }

        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id === Auth::id() 
                    ? $message->receiver_id 
                    : $message->sender_id;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view messages.');
        }

        $messages = Message::where(function ($query) use ($user) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($messages as $message) {
            if ($message->receiver_id === Auth::id() && !$message->read_at) {
                $message->markAsRead();
            }
        }

        return view('messages.show', compact('messages', 'user'));
    }

    public function store(Request $request, User $user)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to send messages.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'product_id' => 'nullable|exists:products,id'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $validated['message'],
            'product_id' => $validated['product_id'] ?? null
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    public function startConversation(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to start a conversation.');
        }

        if ($product->user_id === Auth::id()) {
            return back()->with('error', 'You cannot message yourself.');
        }

        return view('messages.create', compact('product'));
    }

    public function getUnreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
} 