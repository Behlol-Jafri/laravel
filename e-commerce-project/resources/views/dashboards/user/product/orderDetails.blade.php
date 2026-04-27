@extends('dashboards.user.userDashboard')

@section('content')

<div class="container mt-4">

    {{-- <div class="card mb-3 shadow-sm">
        <div class="card-body d-flex justify-content-between">
            <div>
                <h5 class="mb-1">Order #{{ $order->id }}</h5>
                <small class="text-muted">
                    Placed on {{ $order->created_at->format('d M Y') }}
                </small>
            </div>
            <div>
                <span class="badge bg-success px-3 py-2">
                    pending
                </span>
            </div>
        </div>
    </div> --}}

    <div class="row">

        <div class="col-md-8">

            <div class="card mb-3 shadow-sm">
                <div class="card-header fw-semibold">
                    Order Items
                </div>

                <div class="card-body" id="orderItemBody">
                
                </div>
            </div>

            {{-- <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    Order Status
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between text-center">

                        <div>
                            <div class="circle bg-success"></div>
                            <small>Placed</small>
                        </div>

                        <div>
                            <div class="circle bg-success"></div>
                            <small>Shipped</small>
                        </div>

                        <div>
                            <div class="circle bg-secondary"></div>
                            <small>Delivered</small>
                        </div>

                    </div>

                </div>
            </div> --}}

        </div>

        <div class="col-md-4">

            <div class="card mb-3 shadow-sm">
                <div class="card-header fw-semibold">
                    Shipping Address
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name: {{ Auth::user()->firstName }}</strong></p>
                    <p class="mb-1">Address: {{ Auth::user()->address }}</p> 
                    <p class="mb-0">Phone: {{ Auth::user()->phoneNumber }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    Price Details
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span id="subtotal"></span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Discounts</span>
                        <span>Rs: $50</span>
                    </div>

                    {{-- <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>Rs: $10</span>
                    </div> --}}

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong id="grandTotal"></strong>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>


<script>
function loadOrder() {
        fetch("/getOrderItem")
        .then(res => res.json())
        .then(data => {
            renderOrder(data.orderItems);
            updateTotal(data.total);
        })
}
function renderOrder(orderItems){

    let orderItemBody = document.getElementById('orderItemBody');
        

    orderItemBody.innerHTML = '';

    if (orderItems.length == 0) {
        orderItemBody.innerHTML = `<div class="text-center text-danger">Order Item is empty</div>`;
        return;
    }


    
    orderItems.forEach((item) => {

        orderItemBody.innerHTML += `
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div class="d-flex">
                                <img src="${item.image}"
                                 width="80"
                                 class="rounded me-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        ${item.title}
                                    </h6>
                                    <div class="p-0 m-0">
                                        <div class="border border-2 rounded-pill d-inline p-0 m-0">
                                            <button class="border-0 bg-transparent fs-5 fw-bold" style="cursor: ${item.quantity == 1 ? 'not-allowed' : 'pointer'};" ${item.quantity == 1 ? 'disabled' : ''} onclick="changeQty(${item.product_id}, -1)">-</button>
                                            <span class="fw-semibold border border-2 px-2">${item.quantity}</span>
                                            <button class="border-0 bg-transparent fs-5 fw-semibold" onclick="changeQty(${item.product_id}, 1)">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6>Price: $${item.price}</h6>
                                <h6>Total: $${item.price * item.quantity}</h6>
                            </div>
                            <div>
                                <small class="text-danger" style="cursor: pointer">
                                    <i onclick="removeItem(${item.product_id})" class="fas fa-trash"></i>
                                </small>
                            </div>

                        </div>
        `;
    });

}

loadOrder();

function updateTotal(total) {
    document.getElementById('subtotal').innerText = 'Rs: $' + total;
    document.getElementById('grandTotal').innerText = 'Rs: $' + (total - 50);
}

function changeQty(id, value) {
        fetch(`/updateOrderItem/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({'value': parseInt(value)})
        })
        .then(res => res.json())
        .then(data => {
            updateOrderCount();
            loadOrder();
        })

}

function removeItem(id) {
        fetch(`/deleteOrderItem/${id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
        })
        .then(res => res.json())
        .then(data => {
            updateOrderCount();
            loadOrder();
        })

}
</script>

@endsection