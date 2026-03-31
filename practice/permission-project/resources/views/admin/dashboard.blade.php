@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Welcome, {{ auth()->user()->name }}</h1>
        <p>You have access to {{ $stats['my_vendors'] }} vendor(s) granted by Super Admin.</p>
    </div>
    <a href="{{ route('admin.access') }}" class="btn btn-primary">
        <i class="bi bi-shield-lock me-2"></i>Manage Access
    </a>
</div>

@if($stats['my_vendors'] == 0)
<div class="alert alert-warning mb-4">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>No vendors assigned yet.</strong> Ask Super Admin to grant you access to vendors.
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-cyan"><i class="bi bi-shop"></i></div>
            <div><div class="stat-value">{{ $stats['my_vendors'] }}</div><div class="stat-label">My Vendors</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-person"></i></div>
            <div><div class="stat-value">{{ $stats['my_users'] }}</div><div class="stat-label">Total Users</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-box-seam"></i></div>
            <div><div class="stat-value">{{ $stats['products'] }}</div><div class="stat-label">Products</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple"><i class="bi bi-receipt"></i></div>
            <div><div class="stat-value">{{ $stats['orders'] }}</div><div class="stat-label">Orders</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-shop me-2 text-info"></i>My Vendors</span>
                <a href="{{ route('admin.vendors') }}" class="btn btn-sm btn-outline-info">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Vendor</th><th>Action</th></tr></thead>
                    <tbody>
                    @forelse($vendors as $v)
                        <tr>
                            <td>
                                <div class="fw-600">{{ $v->name }}</div>
                                <small class="text-muted">{{ $v->email }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.vendors.show', $v) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted py-4">No vendors granted yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-receipt me-2 text-success"></i>Recent Orders</span>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Customer</th><th>Product</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentOrders as $o)
                        <tr>
                            <td>{{ $o->user->name ?? '—' }}</td>
                            <td>{{ $o->product->name ?? '—' }}</td>
                            <td>PKR {{ number_format($o->total_price,0) }}</td>
                            <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
