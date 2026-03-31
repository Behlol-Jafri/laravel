@extends('layouts.app')
@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="page-header"><div><h1>My Orders</h1><p>Track all your order history.</p></div></div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Product</th><th>Vendor</th><th>Qty</th><th>Total</th><th>Status</th><th>Notes</th><th>Date</th></tr>
            </thead>
            <tbody>
            @forelse($orders as $o)
                <tr>
                    <td class="text-muted">#{{ $o->id }}</td>
                    <td>
                        <div class="fw-600">{{ $o->product->name ?? '—' }}</div>
                    </td>
                    <td class="text-muted small">{{ $o->product->vendor->name ?? '—' }}</td>
                    <td>{{ $o->quantity }}</td>
                    <td><strong>PKR {{ number_format($o->total_price, 0) }}</strong></td>
                    <td>
                        <span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span>
                    </td>
                    <td class="text-muted small">{{ $o->notes ? Str::limit($o->notes, 30) : '—' }}</td>
                    <td class="text-muted small">{{ $o->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-receipt display-6 d-block mb-2 opacity-25"></i>
                        No orders yet. <a href="{{ route('user.products') }}">Browse products</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="px-4 py-3 border-top">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
