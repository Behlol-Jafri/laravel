@extends('layouts.app')
@section('title', 'My Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Welcome, {{ auth()->user()->name }}</h1>
        <p>Browse products from vendors you have access to.</p>
    </div>
    <a href="{{ route('user.products') }}" class="btn btn-primary">
        <i class="bi bi-bag me-2"></i>Browse Products
    </a>
</div>

@if($stats['accessible_vendors'] == 0)
<div class="alert alert-warning mb-4">
    <i class="bi bi-lock me-2"></i>
    <strong>No vendor access yet.</strong> A vendor needs to grant you access before you can browse and order products.
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-cyan"><i class="bi bi-shop"></i></div>
            <div><div class="stat-value">{{ $stats['accessible_vendors'] }}</div><div class="stat-label">Accessible Vendors</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-box-seam"></i></div>
            <div><div class="stat-value">{{ $stats['available_products'] }}</div><div class="stat-label">Available Products</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple"><i class="bi bi-receipt"></i></div>
            <div><div class="stat-value">{{ $stats['my_orders'] }}</div><div class="stat-label">My Orders</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange"><i class="bi bi-clock"></i></div>
            <div><div class="stat-value">{{ $stats['pending_orders'] }}</div><div class="stat-label">Pending Orders</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Featured Products --}}
    @if($featuredProducts->isNotEmpty())
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-bag me-2 text-primary"></i>Featured Products</span>
                <a href="{{ route('user.products') }}" class="btn btn-sm btn-outline-primary">All Products</a>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    @foreach($featuredProducts as $p)
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100" style="border-color:#e2e8f0!important">
                            <div class="fw-600 mb-1">{{ $p->name }}</div>
                            <div class="text-muted small mb-2">{{ $p->vendor->name }}</div>
                            <div class="d-flex align-items-center justify-content-between">
                                <strong class="text-primary">PKR {{ number_format($p->price, 0) }}</strong>
                                <span class="text-muted small">Stock: {{ $p->stock }}</span>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-primary w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderModal"
                                        data-product-id="{{ $p->id }}"
                                        data-product-name="{{ $p->name }}"
                                        data-product-price="{{ $p->price }}"
                                        data-product-stock="{{ $p->stock }}">
                                    <i class="bi bi-cart-plus me-1"></i>Order
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Orders --}}
    <div class="{{ $featuredProducts->isNotEmpty() ? 'col-lg-5' : 'col-12' }}">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-receipt me-2 text-success"></i>Recent Orders</span>
                <a href="{{ route('user.orders') }}" class="btn btn-sm btn-outline-success">All Orders</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Product</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentOrders as $o)
                        <tr>
                            <td>
                                <div class="fw-600 small">{{ $o->product->name ?? '—' }}</div>
                                <small class="text-muted">{{ $o->created_at->format('d M Y') }}</small>
                            </td>
                            <td>PKR {{ number_format($o->total_price, 0) }}</td>
                            <td><span class="pill-badge badge-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Order Modal --}}
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Place Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('user.order') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="modal_product_id">
                    <p class="mb-3">
                        <strong id="modal_product_name"></strong><br>
                        <span class="text-muted small">Price: PKR <span id="modal_product_price"></span></span>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-600">Quantity *</label>
                        <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                        <small class="text-muted">Available stock: <span id="modal_product_stock"></span></small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-600">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cart-check me-2"></i>Confirm Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('[data-bs-target="#orderModal"]').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modal_product_id').value    = this.dataset.productId;
        document.getElementById('modal_product_name').textContent  = this.dataset.productName;
        document.getElementById('modal_product_price').textContent = parseFloat(this.dataset.productPrice).toLocaleString();
        document.getElementById('modal_product_stock').textContent = this.dataset.productStock;
        document.querySelector('#orderModal input[name=quantity]').max = this.dataset.productStock;
    });
});
</script>
@endpush
@endsection
