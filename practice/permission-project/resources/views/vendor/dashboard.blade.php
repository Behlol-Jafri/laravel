@extends('layouts.app')
@section('title', 'Vendor Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Welcome, {{ auth()->user()->name }}</h1>
        <p>Manage your store, products, and customer access.</p>
    </div>
    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Product
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-box-seam-fill"></i></div>
            <div><div class="stat-value">{{ $stats['my_products'] }}</div><div class="stat-label">My Products</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple"><i class="bi bi-receipt-cutoff"></i></div>
            <div><div class="stat-value">{{ $stats['my_orders'] }}</div><div class="stat-label">Total Orders</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-cyan"><i class="bi bi-people"></i></div>
            <div><div class="stat-value">{{ $stats['my_users'] }}</div><div class="stat-label">My Customers</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-currency-dollar"></i></div>
            <div><div class="stat-value">{{ number_format($stats['revenue'], 0) }}</div><div class="stat-label">Revenue (PKR)</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2 text-success"></i>Recent Orders</span>
                <a href="{{ route('vendor.orders') }}" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>#</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                    @forelse($recentOrders as $o)
                        <tr>
                            <td class="text-muted">#{{ $o->id }}</td>
                            <td>{{ $o->user->name ?? '—' }}</td>
                            <td>{{ $o->product->name ?? '—' }}</td>
                            <td>{{ $o->quantity }}</td>
                            <td><strong>PKR {{ number_format($o->total_price, 0) }}</strong></td>
                            <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                            <td class="text-muted small">{{ $o->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
