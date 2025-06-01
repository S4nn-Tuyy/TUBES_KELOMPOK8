<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access the dashboard.');
        }

        $user = Auth::user();

        // Statistik Produk
        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)
            ->where('stock', '>', 0)
            ->count();

        // Statistik Penjualan
        $totalSales = Transaction::where('seller_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $pendingSales = Transaction::where('seller_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->count();
        $totalRevenue = Transaction::where('seller_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_price');

        // Statistik Pembelian
        $totalPurchases = Transaction::where('buyer_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $pendingPurchases = Transaction::where('buyer_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->count();
        $totalSpent = Transaction::where('buyer_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_price');

        // Transaksi Terbaru
        $recentTransactions = Transaction::with(['product', 'buyer', 'seller'])
            ->where(function($query) use ($user) {
                $query->where('buyer_id', $user->id)
                      ->orWhere('seller_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Produk Terbaru
        $recentProducts = Product::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalSales',
            'pendingSales',
            'totalRevenue',
            'totalPurchases',
            'pendingPurchases',
            'totalSpent',
            'recentTransactions',
            'recentProducts'
        ));
    }
} 