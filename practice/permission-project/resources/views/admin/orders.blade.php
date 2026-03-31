@extends('layouts.app')
@section('title', 'Orders')
@section('page-title', 'Orders')
@section('content')
<div class="page-header"><h1>Orders from My Vendors</h1></div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Customer</th><th>Product</th><th>Vendor</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            @forelse($orders as $o)
                <tr>
                    <td class="text-muted">#{{ $o->id }}</td>
                    <td>{{ $o->user->name ?? '—' }}</td>
                    <td>{{ $o->product->name ?? '—' }}</td>
                    <td>{{ $o->product->vendor->name ?? '—' }}</td>
                    <td>PKR {{ number_format($o->total_price,0) }}</td>
                    <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                    <td class="text-muted small">{{ $o->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-5">No orders found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())<div class="px-4 py-3 border-top">{{ $orders->links() }}</div>@endif
</div>
@endsection
