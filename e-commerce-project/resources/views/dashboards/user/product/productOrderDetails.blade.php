@extends('dashboards.user.userDashboard')

@section('content')
    <div class="container-fluid mt-4">
    <h4 class="text-center">Your Cart</h4>
    <div class="border rounded p-3">
        <div id="cartBody">
        </div>

        <h5 id="grandTotal" class="text-end"></h5>
    </div>
    <div class="text-center mt-3">
        <a href="/checkout" class="btn btn-primary">Checkout</a>
        <a href="/" class="btn btn-primary">Continue Shopping</a>
    </div>
</div>


<script>
function loadCart() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        renderCart(cart);
    
}

function renderCart(cart){
    let cartBody = document.getElementById('cartBody');
        
    let grandTotal = 0;

    cartBody.innerHTML = '';

    if (cart.length == 0) {
        cartBody.innerHTML = `<div class="text-center text-danger">Cart is empty</div>`;
        document.getElementById('grandTotal').innerText = '';
        return;
    }


    
    cart.forEach((item, index) => {
        let total = item.price * item.quantity;
        
        grandTotal += total;

        cartBody.innerHTML += `
            <div class="d-flex justify-content-between border rounded p-3 gap-2 my-3">
                <div style="width:20%;">
                    <img src="${item.image}" class="w-100 h-100 rounded cart-img">
                </div>
                <div style="width:20%;" class="p-1">${item.title}</div>
                <div style="width:20%;" class="p-0 m-0">
                    <div class="border border-2 rounded-pill d-inline p-1 m-0">
                        <button class="border-0 bg-transparent fs-4 fw-bold" style="cursor: ${item.quantity == 1 ? 'not-allowed' : 'pointer'};" ${item.quantity == 1 ? 'disabled' : ''} onclick="changeQty(${index}, -1)">-</button>
                        <span class="fw-semibold border border-2 py-1 px-3">${item.quantity}</span>
                        <button class="border-0 bg-transparent fs-4 fw-semibold" onclick="changeQty(${index}, 1)">+</button>
                    </div>
                </div>
                <div style="width:20%;" class="p-1">
                    <span class="fw-bold">Price : </span>
                    $${item.price}
                </div>
                <div style="width:20%;" class="p-1">
                    <span class="fw-bold">Total : </span>
                    $${total}
                </div>
                <div class="p-1">
                    <button onclick="removeItem(${index})" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        `;
    });

    document.getElementById('grandTotal').innerText = 'Total Price : $' + grandTotal;
}

loadCart();


function changeQty(index, change) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart[index].quantity += change;
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        loadCart();

}

function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        loadCart();

}
</script>
@endsection