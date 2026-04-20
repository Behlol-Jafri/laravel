@extends('dashboards.dashboardLayout')

@section('content')

    <main >
        <div class="row ms-0">
            <div class="col-2 border-end border-2 ms-3 p-2" style="height: 100%; overflow-y: auto; position: sticky; top: 0;">
                    <div class="d-flex justify-content-between align-items-center" style="cursor: pointer" id="category">
                        <label class="form-label fw-semibold" style="cursor: pointer">Categories</label>
                        <i class="fa-solid fa-angle-down angle-category"></i>
                    </div>
                    <div class="category-dropdown">
                        @foreach ($categories as $category)
                            <label class="d-block ms-3" style="cursor: pointer">
                                <input type="checkbox" class="category-checkbox checkbox" value="{{ $category->id }}">
                                <span class="text">{{ $category->title }}</span>
                            </label>
                        @endforeach
                    </div>  
                    
                    <div class="d-flex justify-content-between align-items-center" style="cursor: pointer" id="subCategory">
                        <label class="form-label fw-semibold" style="cursor: pointer">Sub Categories</label>
                        <i class="fa-solid fa-angle-down angle-subCategory"></i>
                    </div>
                <div class="subCategory-dropdown">
                    @foreach ($subCategories as $subCategory)
                        <label class="d-block ms-3" style="cursor: pointer">
                            <input type="checkbox" class="subcategory-checkbox checkbox"  value="{{ $subCategory->id }}">
                            <span class="text">{{ $subCategory->title }}</span>
                        </label>
                    @endforeach
                </div>  
                    <div class="d-flex justify-content-between align-items-center" style="cursor: pointer" id="price">
                        <label class="form-label fw-semibold" style="cursor: pointer">Price</label>
                        <i class="fa-solid fa-angle-down angle-price"></i>
                    </div>

                <div class="price-dropdown">
                    <label class="d-block ms-3" style="cursor:pointer">
                        <input type="checkbox" class="price-checkbox checkbox" value="0-50">
                        <span class="text">0 - 50</span>
                    </label>

                    <label class="d-block ms-3" style="cursor:pointer">
                        <input type="checkbox" class="price-checkbox checkbox" value="50-100">
                        <span class="text">50 - 100</span>
                    </label>

                    <label class="d-block ms-3" style="cursor:pointer">
                        <input type="checkbox" class="price-checkbox checkbox" value="100-200">
                        <span class="text">100 - 200</span>
                    </label>

                    <label class="d-block ms-3" style="cursor:pointer">
                        <input type="checkbox" class="price-checkbox checkbox" value="200-500">
                        <span class="text">200 - 500</span>
                    </label>

                    <label class="d-block ms-3" style="cursor:pointer">
                        <input type="checkbox" class="price-checkbox checkbox" value="500+">
                        <span class="text">More then 500</span>
                    </label>
                </div>
            </div>
            <div class="col ">
                <div class="container-fluid">
                    @can('create product')
                        <a href="{{ route('product.create') }}" class="btn btn-primary mb-3" >Add Product</a>
                    @endcan
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <h5 class="bg-primary text-white rounded p-2">Product Data</h5>
                    <div class="row" id="cardBody">
                            @include('dashboards.user.product.productTable')
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div id="productCount">
                        Showing {{ $products->count() }} out of {{ $products->total() }} products
                    </div>
                    <div id="paginationLinks">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>  
        </div>

        
    </main>


    


<script>


    function filterProducts(page = 1) {
        let categoryIds = [];
        let subCategoryIds = [];
        let priceRanges = [];

        document.querySelectorAll('.category-checkbox:checked').forEach(el => {
            categoryIds.push(el.value);
        });

        document.querySelectorAll('.subcategory-checkbox:checked').forEach(el => {
            subCategoryIds.push(el.value);
        });

        document.querySelectorAll('.price-checkbox:checked').forEach(el => {
            priceRanges.push(el.value);
        });

        let ids = {
            page: page,
            categoryIds: categoryIds,
            subCategoryIds: subCategoryIds,
            priceRanges: priceRanges
        };


        fetch("{{ route('products.filter') }}",{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(ids)
        })
        .then(response => response.json())
        .then(data =>{
            document.getElementById('cardBody').innerHTML = data.html;
            document.getElementById('paginationLinks').innerHTML = data.pagination;
            document.getElementById('productCount').innerText = `Showing ${data.currentCount} out of ${data.total} products`;
        })
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('category-checkbox') || 
            e.target.classList.contains('subcategory-checkbox') ||
            e.target.classList.contains('price-checkbox')
        ) {
            filterProducts();
        }
    });

    let category = document.querySelector('#category');
    category.addEventListener('click', function(){
        let categoryDropdown = document.querySelector('.category-dropdown');
        let angleCategory = document.querySelector('.angle-category');
        if(categoryDropdown.style.display === "block"){
            categoryDropdown.style.display = "none"
            angleCategory.style.transform = 'rotate(0deg)'
            angleCategory.style.transition = '0.3s'
        }else{
            categoryDropdown.style.display = "block"
            angleCategory.style.transform = 'rotate(180deg)'
            angleCategory.style.transition = '0.3s'
        }
    });

    let subCategory = document.querySelector('#subCategory');
    subCategory.addEventListener('click', function(){
        let subCategoryDropdown = document.querySelector('.subCategory-dropdown');
        let angleSubCategory = document.querySelector('.angle-subCategory');
        if(subCategoryDropdown.style.display === "block"){
            subCategoryDropdown.style.display = "none"
            angleSubCategory.style.transform = 'rotate(0deg)'
            angleSubCategory.style.transition = '0.3s'
        }else{
            subCategoryDropdown.style.display = "block"
            angleSubCategory.style.transform = 'rotate(180deg)'
            angleSubCategory.style.transition = '0.3s'
        }
    });

    let price = document.querySelector('#price');
    price.addEventListener('click', function(){
        let priceDropdown = document.querySelector('.price-dropdown');
        let anglePrice = document.querySelector('.angle-price');
        if(priceDropdown.style.display === "block"){
            priceDropdown.style.display = "none"
            anglePrice.style.transform = 'rotate(0deg)'
            anglePrice.style.transition = '0.3s'
        }else{
            priceDropdown.style.display = "block"
            anglePrice.style.transform = 'rotate(180deg)'
            anglePrice.style.transition = '0.3s'
        }
    });


</script>
   
@endsection