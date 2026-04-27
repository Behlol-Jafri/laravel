@if($products->count() > 0)   
                @foreach ($products as $index => $product)
                    <div class="col-4 my-2">
                        <div class="card product-card w-100">
                            <div class="card-header" style="height: 250px">
                                @if($product->images->count() > 0)
                                    <div class="position-relative h-100">
                                        <img src="{{ asset($product->images->first()->image) }}" class="rounded w-100 object-fit-cover h-100">
                                        <div class="position-absolute top-0 end-0 btn btn-danger">New</div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5>{{ $product->title }}</h5>
                                <p>{{ $product->price }}</p>
                                <button 
                                    class="btn-hover add-to-cart-btn"
                                    data-id="{{ $product->id }}"
                                    data-title="{{ $product->title }}"
                                    data-price="{{ $product->price }}"
                                    data-image="{{ optional($product->images->first())->image }}"
                                >
                                    <i class="fas fa-bag-shopping"></i> Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else 
                <div class="text-center text-danger">
                    Products Not Found
                </div>
            @endif



            <script>
document.addEventListener('click', function(e) {

    if (e.target.closest('.add-to-cart-btn')) {

        let btn = e.target.closest('.add-to-cart-btn');

       let product = {
            product_id: btn.dataset.id,
            title: btn.dataset.title,
            price: parseFloat(btn.dataset.price.replace('$', '')),
            image: btn.dataset.image,
            quantity: 1
        };

                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                let existing = cart.find(item => item.product_id == product.product_id);

                if (existing) {
                    existing.quantity += 1;
                } else {
                    cart.push(product);
                }

                localStorage.setItem('cart', JSON.stringify(cart));

                updateCartCount();

        
    }

});
</script>