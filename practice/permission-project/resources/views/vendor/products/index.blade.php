@extends('layouts.app')
@section('title', 'My Products')
@section('page-title', 'My Products')

@section('content')
<div class="page-header">
    <div><h1>My Products</h1><p>Manage your product listings.</p></div>
    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Product
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($products as $p)
                <tr>
                    <td class="text-muted">{{ $p->id }}</td>
                    <td>
                        <div class="fw-600">{{ $p->name }}</div>
                        <small class="text-muted">{{ Str::limit($p->description, 45) }}</small>
                    </td>
                    <td>{{ $p->category ?? '—' }}</td>
                    <td><strong>PKR {{ number_format($p->price, 0) }}</strong></td>
                    <td>
                        @if($p->stock <= 5)
                            <span class="text-danger fw-600">{{ $p->stock }}</span>
                        @else
                            {{ $p->stock }}
                        @endif
                    </td>
                    <td>
                        <span class="pill-badge {{ $p->is_active ? 'badge-completed' : 'badge-cancelled' }}">
                            {{ $p->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('vendor.products.edit', $p) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('vendor.products.destroy', $p) }}"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-box-seam display-6 d-block mb-2 opacity-25"></i>
                        No products yet. <a href="{{ route('vendor.products.create') }}">Add your first product</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="px-4 py-3 border-top">{{ $products->links() }}</div>
    @endif
</div>
@endsection
