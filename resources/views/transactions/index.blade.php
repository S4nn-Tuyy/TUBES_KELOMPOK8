@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Transactions</h2>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#purchases">My Purchases</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#sales">My Sales</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="purchases">
            <div class="card">
                <div class="card-body">
                    @if($buyTransactions->isEmpty())
                        <div class="alert alert-info">
                            You haven't made any purchases yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Seller</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($buyTransactions as $transaction)
                                        <tr>
                                            <td>
                                                @if($transaction->product->exists)
                                                    <a href="{{ route('products.show', $transaction->product) }}">
                                                        {{ $transaction->product_name }}
                                                    </a>
                                                @else
                                                    {{ $transaction->product_name }}
                                                    <small class="text-muted">(removed)</small>
                                                @endif
                                            </td>
                                            <td>{{ $transaction->seller->name }}</td>
                                            <td>{{ $transaction->quantity }}</td>
                                            <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $transaction) }}" 
                                                    class="btn btn-sm btn-primary">
                                                    Details
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

        <div class="tab-pane fade" id="sales">
            <div class="card">
                <div class="card-body">
                    @if($sellTransactions->isEmpty())
                        <div class="alert alert-info">
                            You haven't made any sales yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Buyer</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sellTransactions as $transaction)
                                        <tr>
                                            <td>
                                                @if($transaction->product->exists)
                                                    <a href="{{ route('products.show', $transaction->product) }}">
                                                        {{ $transaction->product_name }}
                                                    </a>
                                                @else
                                                    {{ $transaction->product_name }}
                                                    <small class="text-muted">(removed)</small>
                                                @endif
                                            </td>
                                            <td>{{ $transaction->buyer->name }}</td>
                                            <td>{{ $transaction->quantity }}</td>
                                            <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $transaction) }}" 
                                                    class="btn btn-sm btn-primary">
                                                    Details
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
    </div>
</div>
@endsection 