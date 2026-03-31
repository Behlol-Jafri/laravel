@extends('layouts.app')
@section('title', 'Products')
@section('page-title', 'Products')
@section('content')
<div class="page-header"><h1>Products from My Vendors</h1></div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Product</th><th>Vendor</th><th>Price</th><th>Stock</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($products as $p)
                <tr>
                    <td><div class="fw-600">{{ $p->name }}</div><small class="text-muted">{{ $p->category }}</small></td>
                    <td>{{ $p->vendor->name ?? '—' }}</td>
                    <td>PKR {{ number_format($p->price,0) }}</td>
                    <td>{{ $p->stock }}</td>
                    <td><span class="pill-badge {{ $p->is_active?'badge-completed':'badge-cancelled' }}">{{ $p->is_active?'Active':'Off' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-5">No products found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())<div class="px-4 py-3 border-top">{{ $products->links() }}</div>@endif
</div>
@endsection
