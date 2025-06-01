<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        if (!Auth::user()->is_admin) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        return null;
    }

    public function dashboard()
    {
        if ($check = $this->checkAdmin()) return $check;

        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_transactions' => Transaction::count(),
            'total_reviews' => Review::count(),
        ];

        $recentProducts = Product::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'recentUsers'));
    }

    public function products()
    {
        if ($check = $this->checkAdmin()) return $check;

        $products = Product::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.products', compact('products'));
    }

    public function toggleProductVisibility(Product $product)
    {
        if ($check = $this->checkAdmin()) return $check;

        $product->is_hidden = !$product->is_hidden;
        $product->save();

        return back()->with('success', 
            $product->is_hidden ? 'Produk telah disembunyikan.' : 'Produk sekarang terlihat.');
    }

    public function users()
    {
        if ($check = $this->checkAdmin()) return $check;

        $users = User::latest()
            ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        if ($check = $this->checkAdmin()) return $check;

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        return back()->with('success', 
            $user->is_banned ? 'Pengguna telah dibanned.' : 'Pengguna telah di-unbanned.');
    }

    public function reports()
    {
        if ($check = $this->checkAdmin()) return $check;

        $userStats = [
            'total' => User::count(),
            'active' => User::where('is_banned', false)->count(),
            'banned' => User::where('is_banned', true)->count(),
        ];

        $productStats = [
            'total' => Product::count(),
            'visible' => Product::where('is_hidden', false)->count(),
            'hidden' => Product::where('is_hidden', true)->count(),
        ];

        $transactionStats = Transaction::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled
        ')->first();

        $reviewStats = [
            'total' => Review::count(),
            'average_rating' => Review::avg('rating'),
        ];

        return view('admin.reports', compact(
            'userStats', 
            'productStats', 
            'transactionStats', 
            'reviewStats'
        ));
    }
} 