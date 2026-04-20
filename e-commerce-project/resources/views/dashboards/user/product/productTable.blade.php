@if($products->total() > 0)   
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
                                <button class="w-100 border rounded text-center p-2 btn-hover"><i class="fas fa-bag-shopping"></i> Add to card</button>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else 
                <div class="text-center text-danger">
                    Products Not Found
                </div>
            @endif