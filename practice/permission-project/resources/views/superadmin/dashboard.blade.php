@extends('layouts.app')
@section('title', 'Super Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Welcome back, {{ auth()->user()->name }} 👋</h1>
        <p>Here's what's happening across the entire platform.</p>
    </div>
    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>Add User
    </a>
</div>

{{-- Stats Grid --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="stat-value">{{ $stats['admins'] }}</div>
                <div class="stat-label">Admins</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-cyan"><i class="bi bi-shop"></i></div>
            <div>
                <div class="stat-value">{{ $stats['vendors'] }}</div>
                <div class="stat-label">Vendors</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-person"></i></div>
            <div>
                <div class="stat-value">{{ $stats['users'] }}</div>
                <div class="stat-label">Regular Users</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_products'] }}</div>
                <div class="stat-label">Products</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
                <div class="stat-label">Orders</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_grants'] }}</div>
                <div class="stat-label">Active Grants</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['active_users'] }}</div>
                <div class="stat-label">Active Accounts</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Users --}}
    <div class="col-lg-6">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2 text-primary"></i>Recent Users</span>
                <a href="{{ route('superadmin.users') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Name</th><th>Role</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentUsers as $u)
                        <tr>
                            <td>
                                <div class="fw-600">{{ $u->name }}</div>
                                <small class="text-muted">{{ $u->email }}</small>
                            </td>
                            <td>
                                <span class="pill-badge badge-{{ $u->role }}">{{ $u->getRoleLabel() }}</span>
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="pill-badge badge-completed">Active</span>
                                @else
                                    <span class="pill-badge badge-cancelled">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No users yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Access Grants --}}
    <div class="col-lg-6">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-shield-lock me-2 text-warning"></i>Recent Access Grants</span>
                <a href="{{ route('superadmin.access') }}" class="btn btn-sm btn-outline-warning">Manage</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Granter</th><th>Grantee</th><th>Level</th></tr></thead>
                    <tbody>
                    @forelse($recentGrants as $g)
                        <tr>
                            <td>
                                <div class="fw-600 small">{{ $g->granter->name }}</div>
                                <span class="pill-badge badge-{{ $g->granter->role }} small" style="font-size:10px">{{ $g->granter->getRoleLabel() }}</span>
                            </td>
                            <td>
                                <div class="fw-600 small">{{ $g->grantee->name }}</div>
                                <span class="pill-badge badge-{{ $g->grantee->role }} small" style="font-size:10px">{{ $g->grantee->getRoleLabel() }}</span>
                            </td>
                            <td>
                                <span class="pill-badge badge-processing">{{ ucfirst($g->access_level) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No grants yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="col-12">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2 text-success"></i>Recent Orders</span>
                <a href="{{ route('superadmin.orders') }}" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Order ID</th><th>Customer</th><th>Product</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentOrders as $o)
                        <tr>
                            <td><span class="text-muted">#{{ $o->id }}</span></td>
                            <td>{{ $o->user->name ?? '-' }}</td>
                            <td>{{ $o->product->name ?? '-' }}</td>
                            <td><strong>PKR {{ number_format($o->total_price, 0) }}</strong></td>
                            <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
