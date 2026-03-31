@extends('layouts.app')
@section('title', 'My Orders')
@section('page-title', 'Orders')

@section('content')
<div class="page-header"><div><h1>My Orders</h1><p>Orders placed for your products.</p></div></div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th><th>Date</th><th>Update</th></tr>
            </thead>
            <tbody>
            @forelse($orders as $o)
                <tr>
                    <td class="text-muted">#{{ $o->id }}</td>
                    <td>
                        <div class="fw-600">{{ $o->user->name ?? '—' }}</div>
                        <small class="text-muted">{{ $o->user->email ?? '' }}</small>
                    </td>
                    <td>{{ $o->product->name ?? '—' }}</td>
                    <td>{{ $o->quantity }}</td>
                    <td><strong>PKR {{ number_format($o->total_price, 0) }}</strong></td>
                    <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                    <td class="text-muted small">{{ $o->created_at->format('d M Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('vendor.orders.status', $o) }}" class="d-flex gap-1">
                            @csrf
                            <select name="status" class="form-select form-select-sm" style="width:130px">
                                @foreach(['pending','processing','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $o->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-check2"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-receipt display-6 d-block mb-2 opacity-25"></i>No orders yet.
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
