<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to leave a review.');
        }

        // cek apakah dia pembeli barangnya
        if ($transaction->buyer_id !== Auth::id()) {
            return back()->with('error', 'You can only review products you have purchased.');
        }

        // Check if transaction is completed
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'You can only review completed transactions.');
        }

        // Check if review already exists
        if (Review::where('transaction_id', $transaction->id)->exists()) {
            return back()->with('error', 'You have already reviewed this transaction.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500'
        ]);

        // Debug information
        Log::info('Transaction data:', [
            'transaction_id' => $transaction->id,
            'product_id' => $transaction->product_id,
            'product_name' => $transaction->product_name
        ]);

        // Ensure we have a product_id
        if (!$transaction->product_id) {
            return back()->with('error', 'Cannot create review: Product reference is missing.');
        }

        try {
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $transaction->product_id,
                'transaction_id' => $transaction->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment']
            ]);

            return back()->with('success', 'Thank you for your review!');
        } catch (\Exception $e) {
            Log::error('Error creating review:', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'product_id' => $transaction->product_id
            ]);

            return back()->with('error', 'Sorry, there was a problem creating your review. Please try again.');
        }
    }

    public function destroy(Review $review)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to delete a review.');
        }

        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'You can only delete your own reviews.');
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
} 