@extends('layouts.app')
@section('title', 'Vendor: '.$vendor->name)
@section('page-title', $vendor->name)

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $vendor->name }}</h1>
        <p>{{ $vendor->email }} {{ $vendor->phone ? '· '.$vendor->phone : '' }}</p>
    </div>
    <a href="{{ route('admin.vendors') }}" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="table-card">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>Products</div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Name</th><th>Price</th><th>Stock</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td><div class="fw-600">{{ $p->name }}</div><small class="text-muted">{{ $p->category }}</small></td>
                            <td>PKR {{ number_format($p->price,0) }}</td>
                            <td>{{ $p->stock }}</td>
                            <td><span class="pill-badge {{ $p->is_active?'badge-completed':'badge-cancelled' }}">{{ $p->is_active?'Active':'Off' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No products.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="table-card">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Recent Orders</div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Customer</th><th>Product</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($orders as $o)
                        <tr>
                            <td>{{ $o->user->name ?? '—' }}</td>
                            <td>{{ $o->product->name ?? '—' }}</td>
                            <td>PKR {{ number_format($o->total_price,0) }}</td>
                            <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No orders.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
