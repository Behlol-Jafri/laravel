@extends('layouts.app')
@section('title', 'All Products')
@section('page-title', 'All Products')

@section('content')
<div class="page-header">
    <h1>All Products</h1>
</div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Product</th><th>Vendor</th><th>Price</th><th>Stock</th><th>Category</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($products as $p)
                <tr>
                    <td class="text-muted">{{ $p->id }}</td>
                    <td><div class="fw-600">{{ $p->name }}</div><small class="text-muted">{{ Str::limit($p->description,40) }}</small></td>
                    <td>{{ $p->vendor->name ?? '—' }}</td>
                    <td><strong>PKR {{ number_format($p->price,0) }}</strong></td>
                    <td>{{ $p->stock }}</td>
                    <td>{{ $p->category ?? '—' }}</td>
                    <td>
                        @if($p->is_active)
                            <span class="pill-badge badge-completed">Active</span>
                        @else
                            <span class="pill-badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-5">No products found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())<div class="px-4 py-3 border-top">{{ $products->links() }}</div>@endif
</div>
@endsection
