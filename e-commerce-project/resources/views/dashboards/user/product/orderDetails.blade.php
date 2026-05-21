@extends('dashboards.user.userDashboard')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold" style="font-size: 1.17rem;">
                        <i class="fas fa-cart-arrow-down text-orange me-2"></i> Items in Your Order
                    </div>
                    <div class="card-body p-0" id="orderItemsList">
                        <div class="d-flex align-items-center justify-content-center py-5">
                            <div class="spinner-border text-warning"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="fas fa-map-marker-alt text-orange me-2 "></i> Shipping Address
                    </div>
                    <div class="card-body">
                        <div><strong>{{ Auth::user()->firstName ?? 'N/A' }}</strong></div>
                        <div>{{ Auth::user()->address ?? 'No address provided' }}</div>
                        <div class="mt-1">Phone: {{ Auth::user()->phoneNumber ?? '-' }}</div>
                        <div class="mt-2 text-secondary" style="font-size: 0.9rem;">
                            <i class="fas fa-truck-moving me-1"></i>
                            Delivered by: <span id="deliveryEstimate">3-7 business days</span>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="fas fa-receipt text-orange me-2"></i> Order Summary
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="orderSubtotal">Rs --</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span id="orderShipping">Rs --</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span id="orderDiscount">--</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span id="orderTax">Rs --</span>
                        </div>
                        <hr class="my-2"/>
                        <div class="d-flex justify-content-between">
                            <b>Total</b>
                            <b id="orderGrandTotal">Rs --</b>
                        </div>
                        <a href="{{ route('payment') }}" class="btn btn-success btn-block w-100 mt-3 fw-bold py-2">
                            Pay Now
                        </a>
                   
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadOrderDetails);

        async function loadOrderDetails() {
            try {
                const response = await fetch("/getOrderItem");
                const data = await response.json();
                renderOrderItems(data.orderItems ?? []);
                renderOrderSummary(data);
            } catch (e) {
                document.getElementById('orderItemsList').innerHTML = `
                    <div class="no-items">
                        <i class="fas fa-exclamation-circle fa-3x mb-3 d-block"></i>
                        <div>Unable to load your order. <a href="#" onclick="location.reload()">Retry</a></div>
                    </div>
                `;
            }
        }

        function renderOrderItems(items) {
            const container = document.getElementById('orderItemsList');
            if (!items || items.length === 0) {
                container.innerHTML = `
                    <div class="no-items">
                        <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                        <div>No products in your order.</div>
                    </div>
                `;
                return;
            }

            let html = `<table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Item Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
            `;
            items.forEach(item => {
                html += `
                    <tr class="order-list-row">
                        <td style="width:100px;">
                            <img src="${item.image}" alt="${item.title}" class="w-100 h-100 rounded object-fit-cover">
                        </td>
                        <td>
                            <div class="order-item-title">${item.title}</div>
                        </td>
                        <td>${item.quantity}</td>
                        <td class="order-item-price">Rs. ${Number(item.price).toFixed(2)}</td>
                        <td class="order-item-price">Rs. ${(item.price * item.quantity).toFixed(2)}</td>
                        <td>
                            <span class="badge bg-${item.status_class ?? 'success'}">${item.status ?? 'pending'}</span>
                        </td>
                    </tr>
                `;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        function renderOrderSummary(data) {
            const subtotal = Number(data.total ?? 0);
            const shipping = Number(data.shipping ?? 100);
            const discount = Number(data.discount ?? 0);
            const tax = Number(data.tax ?? (subtotal * 0.10)).toFixed(2); 
            const grandTotal = (subtotal + shipping - discount + Number(tax)).toFixed(2);

            document.getElementById('orderSubtotal').innerText = `Rs ${subtotal.toFixed(2)}`;
            document.getElementById('orderShipping').innerText = `Rs ${shipping.toFixed(2)}`;
            document.getElementById('orderDiscount').innerText = discount > 0 ? `Rs ${discount.toFixed(2)}` : '--';
            document.getElementById('orderTax').innerText = `Rs ${tax}`;
            document.getElementById('orderGrandTotal').innerText = `Rs ${grandTotal}`;
        }
    </script>
@endsection