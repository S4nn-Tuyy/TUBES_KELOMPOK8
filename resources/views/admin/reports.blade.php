@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>System Reports</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="row">
        <!-- User Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">User Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Total Users
                            <span class="badge bg-primary rounded-pill">{{ $userStats['total'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Active Users
                            <span class="badge bg-success rounded-pill">{{ $userStats['active'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Banned Users
                            <span class="badge bg-danger rounded-pill">{{ $userStats['banned'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Total Products
                            <span class="badge bg-primary rounded-pill">{{ $productStats['total'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Visible Products
                            <span class="badge bg-success rounded-pill">{{ $productStats['visible'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Hidden Products
                            <span class="badge bg-danger rounded-pill">{{ $productStats['hidden'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Transaction Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Total Transactions
                            <span class="badge bg-primary rounded-pill">{{ $transactionStats->total }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Completed Transactions
                            <span class="badge bg-success rounded-pill">{{ $transactionStats->completed }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Processing Transactions
                            <span class="badge bg-warning rounded-pill">{{ $transactionStats->processing }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Cancelled Transactions
                            <span class="badge bg-danger rounded-pill">{{ $transactionStats->cancelled }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Review Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Total Reviews
                            <span class="badge bg-primary rounded-pill">{{ $reviewStats['total'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Average Rating
                            <span class="badge bg-info rounded-pill">
                                {{ number_format($reviewStats['average_rating'], 1) }} / 5.0
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 