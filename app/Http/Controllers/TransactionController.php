<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Menampilkan daftar transaksi user
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view transactions.');
        }

        $buyTransactions = Transaction::with(['product', 'seller'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();

        $sellTransactions = Transaction::with(['product', 'buyer'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();

        return view('transactions.index', compact('buyTransactions', 'sellTransactions'));
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to make a purchase.');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check if buying own product
        if ($product->user_id === Auth::id()) {
            return back()->with('error', 'You cannot buy your own product.');
        }

        // Check stock availability
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Insufficient stock available.');
        }

        // Calculate total price
        $total_price = $product->price * $validated['quantity'];

        // Create transaction
        $transaction = Transaction::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $validated['quantity'],
            'total_price' => $total_price,
            'shipping_address' => $validated['shipping_address'],
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        // Reduce product stock
        $product->decrement('stock', $validated['quantity']);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Order placed successfully! Please complete the payment.');
    }

    // Menampilkan detail transaksi
    public function show(Transaction $transaction)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view transaction details.');
        }

        if ($transaction->buyer_id !== Auth::id() && $transaction->seller_id !== Auth::id()) {
            return redirect()->route('transactions.index')
                ->with('error', 'You are not authorized to view this transaction.');
        }

        return view('transactions.show', compact('transaction'));
    }

    // Update status transaksi
    public function update(Request $request, Transaction $transaction)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to update transaction status.');
        }

        if ($transaction->seller_id !== Auth::id()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Only the seller can update the transaction status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:processing,completed,cancelled',
            'payment_status' => 'required|in:paid,unpaid,refunded'
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction status updated successfully.');
    }

    // Konfirmasi penerimaan barang oleh pembeli
    public function confirm(Transaction $transaction)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to confirm receipt.');
        }

        if ($transaction->buyer_id !== Auth::id()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Only the buyer can confirm receipt.');
        }

        if ($transaction->status !== 'processing') {
            return back()->with('error', 'This transaction cannot be confirmed at this time.');
        }

        $transaction->update([
            'status' => 'completed',
            'payment_status' => 'paid'
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Order confirmed as received. Thank you for shopping!');
    }

    // Batalkan transaksi
    public function cancel(Transaction $transaction)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to cancel the transaction.');
        }

        if ($transaction->buyer_id !== Auth::id() && $transaction->seller_id !== Auth::id()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'You are not authorized to cancel this transaction.');
        }

        if (!in_array($transaction->status, ['pending', 'processing'])) {
            return back()->with('error', 'This transaction cannot be cancelled.');
        }

        // Return stock to product
        $transaction->product->increment('stock', $transaction->quantity);

        $transaction->update([
            'status' => 'cancelled',
            'payment_status' => 'refunded'
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction cancelled successfully.');
    }
} 