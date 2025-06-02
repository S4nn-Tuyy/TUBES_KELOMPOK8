@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Dashboard</h2>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Product Stats -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Produk</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <h6 class="text-muted">Total Produk</h6>
                                <h2>{{ $totalProducts }}</h2>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6 class="text-muted">Produk Aktif</h6>
                                <h2>{{ $activeProducts }}</h2>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- Transaction Stats -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <h6 class="text-muted">Penjualan Selesai</h6>
                                <h2>{{ $totalSales }}</h2>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted">Total Pendapatan</h6>
                                <h4 class="text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h6 class="text-muted">Pembelian Selesai</h6>
                                <h2>{{ $totalPurchases }}</h2>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted">Total Pengeluaran</h6>
                                <h4 class="text-danger">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                        Lihat Semua Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Transactions Alert -->
    @if($pendingPurchases > 0 || $pendingSales > 0)
        <div class="alert alert-warning mb-4">
            <h5 class="alert-heading">Transaksi yang Perlu Ditindaklanjuti</h5>
            @if($pendingPurchases > 0)
                <p class="mb-1">
                    Anda memiliki {{ $pendingPurchases }} pembelian yang menunggu konfirmasi
                </p>
            @endif
            @if($pendingSales > 0)
                <p class="mb-0">
                    Anda memiliki {{ $pendingSales }} penjualan yang perlu diproses
                </p>
            @endif
        </div>
    @endif

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Transaksi Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentTransactions->isEmpty())
                        <p class="text-muted">Belum ada transaksi.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Tipe</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->product->name }}</td>
                                            <td>
                                                @if($transaction->buyer_id === Auth::id())
                                                    <span class="badge bg-primary">Pembelian</span>
                                                @else
                                                    <span class="badge bg-success">Penjualan</span>
                                                @endif
                                            </td>
                                            <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : 
                                                    ($transaction->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $transaction) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Produk Terbaru Saya</h5>
                </div>
                <div class="card-body">
                    @if($recentProducts->isEmpty())
                        <p class="text-muted">Belum ada produk.</p>
                    @else
                        @foreach($recentProducts as $product)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ Storage::url($product->image) }}" 
                                     class="rounded" alt="{{ $product->name }}"
                                     style="width: 48px; height: 48px; object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                    <small class="text-muted">
                                        Stok: {{ $product->stock }} | 
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 