@extends('home')

@section('data')
    <header >
        <nav class="navbar px-3 my-2">
            <div>
                <h4>SilkStitch</h4>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-3">
                <a href="/" class="nav-link">Home</a>
                {{-- <a href="" class="nav-link">Shop</a>
                <a href="" class="nav-link">About Us</a>
                <a href="" class="nav-link">Blog</a> --}}
                <a href="" class="nav-link">Contact Us</a>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-3">
                @if (Auth::check())
                    <a href="{{ route('orderDetails') }}" class="btn btn-warning rounded-pill position-relative">orders
                        <span id="order-count" 
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>
                @endif
                <i class="fa-solid fa-magnifying-glass" style="cursor: pointer"></i>
                <i class="fa-regular fa-heart" style="cursor: pointer"></i>
                <a href="{{ route('cartDetails') }}" class="position-relative">
                    <i class="fa-solid fa-cart-shopping text-black" style="cursor: pointer"></i>
                    <span id="cart-count" 
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </a>
                @if (!Auth::check())
                    <a href="{{ route('loginForm') }}" class="btn btn-primary rounded-pill">login</a>
                @else
                    <form id="logout" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger rounded-pill">Logout</button>
                    </form>
                @endif
            </div>
        </nav>
    </header>
    {{-- <main style="height: calc(100vh - 70px);">
        <div class=" overflow-y-auto h-100 custom-scroll"> --}}
            @yield('content')
        {{-- </div>
    </main>
    <footer class="bg-secondary p-1 rounded mt-3">
        <p>e-commercewebsite@copyright.2020</p>
    </footer> --}}

    <script>
        function updateCartCount() {
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];

                    let totalQty = 0;

                    cart.forEach(item => {
                        totalQty += parseInt(item.quantity);
                    });

                    updateCartUI(totalQty);
           
}

function updateOrderCount() {
                fetch("/getOrderItem")
                .then(res => res.json())
                .then(data => {
                    let orderItems = data.orderItems;

                    let totalQty = 0;

                    orderItems.forEach(item => {
                        totalQty += parseInt(item.quantity);
                    });

                    updateOrderUI(totalQty);
                })
           
}

function updateCartUI(totalQty) {
    let cartCount = document.getElementById('cart-count');

    if (!cartCount) return;

    if (totalQty === 0) {
        cartCount.style.display = 'none';
    } else {
        cartCount.style.display = 'block';
        cartCount.innerText = totalQty;
    }
}
function updateOrderUI(totalQty) {
    let orderCount = document.getElementById('order-count');

    if (!orderCount) return;

    if (totalQty === 0) {
        orderCount.style.display = 'none';
    } else {
        orderCount.style.display = 'block';
        orderCount.innerText = totalQty;
    }
}

updateCartCount();
updateOrderCount();

document.getElementById('logout').addEventListener('submit', function(e) {
    fetch("/logout", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(res => res.json())
    .then(data => {
        localStorage.removeItem('cart');

        window.location.href = "/";
    });
});

window.addEventListener('storage', function () {
    updateCartCount();
    updateOrderCount();
});
    </script>
@endsection