@extends('layouts.app')
@section('title', 'Customer: '.$user->name)
@section('page-title', 'Customer Profile')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->email }} {{ $user->phone ? '· '.$user->phone : '' }}</p>
    </div>
    <a href="{{ route('vendor.users') }}" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Back</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple"><i class="bi bi-receipt"></i></div>
            <div><div class="stat-value">{{ $orders->total() }}</div><div class="stat-label">Total Orders</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ $orders->where('status','completed')->count() }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="card-header"><i class="bi bi-receipt me-2"></i>Order History</div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            @forelse($orders as $o)
                <tr>
                    <td class="text-muted">#{{ $o->id }}</td>
                    <td>{{ $o->product->name ?? '—' }}</td>
                    <td>{{ $o->quantity }}</td>
                    <td>PKR {{ number_format($o->total_price, 0) }}</td>
                    <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                    <td class="text-muted small">{{ $o->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No orders from this customer.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="px-4 py-3 border-top">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
