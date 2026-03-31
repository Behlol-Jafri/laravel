@extends('layouts.app')
@section('title', 'Browse Products')
@section('page-title', 'Browse Products')

@section('content')
<div class="page-header">
    <div><h1>Browse Products</h1><p>Products available from your vendors.</p></div>
</div>

@if($products->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-bag-x display-4 d-block mb-3 opacity-25"></i>
            <h5>No products available</h5>
            <p class="mb-0">You don't have access to any vendor yet, or vendors have no active products.</p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($products as $p)
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-700 mb-1">{{ $p->name }}</h6>
                            <span class="text-muted small">{{ $p->vendor->name }}</span>
                        </div>
                        @if($p->category)
                            <span class="pill-badge badge-processing" style="font-size:10px">{{ $p->category }}</span>
                        @endif
                    </div>
                    @if($p->description)
                        <p class="text-muted small mb-3">{{ Str::limit($p->description, 80) }}</p>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div>
                            <div class="fw-700 text-primary fs-5">PKR {{ number_format($p->price, 0) }}</div>
                            <div class="text-muted" style="font-size:11px">
                                @if($p->stock > 0)
                                    <i class="bi bi-check-circle text-success me-1"></i>{{ $p->stock }} in stock
                                @else
                                    <i class="bi bi-x-circle text-danger me-1"></i>Out of stock
                                @endif
                            </div>
                        </div>
                        @if($p->stock > 0)
                        <button type="button" class="btn btn-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#orderModal"
                                data-product-id="{{ $p->id }}"
                                data-product-name="{{ $p->name }}"
                                data-product-price="{{ $p->price }}"
                                data-product-stock="{{ $p->stock }}">
                            <i class="bi bi-cart-plus me-1"></i>Order
                        </button>
                        @else
                        <button class="btn btn-secondary btn-sm" disabled>Out of Stock</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($products->hasPages())
        <div class="mt-4">{{ $products->links() }}</div>
    @endif
@endif

{{-- Order Modal --}}
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">Place Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('user.order') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="modal_product_id">
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="fw-600" id="modal_product_name"></div>
                        <div class="text-primary fw-700 mt-1">PKR <span id="modal_product_price"></span></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="1" value="1" id="modal_qty" required>
                        <div class="form-text">Max available: <span id="modal_product_stock"></span></div>
                    </div>
                    <div>
                        <label class="form-label fw-600">Notes <span class="text-muted fw-400">(optional)</span></label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-cart-check me-2"></i>Place Order
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
        document.getElementById('modal_product_id').value = this.dataset.productId;
        document.getElementById('modal_product_name').textContent = this.dataset.productName;
        document.getElementById('modal_product_price').textContent = parseFloat(this.dataset.productPrice).toLocaleString();
        document.getElementById('modal_product_stock').textContent = this.dataset.productStock;
        document.getElementById('modal_qty').max = this.dataset.productStock;
    });
});
</script>
@endpush
@endsection
