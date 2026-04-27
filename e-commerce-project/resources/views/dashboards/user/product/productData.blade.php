@extends('dashboards.user.userDashboard')

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
                    <h5 class="">Product Data</h5>
                    <div class="row" id="cardBody">
                            @include('dashboards.user.product.productTable')
                    </div>
                    <div id="loading" style="display:none; text-align:center; padding:20px;">
                        Loading...
                    </div>
                </div>
            </div>  
        </div>

        
    </main>


    <script>

    let offset = 5;
    let isLoading = false;
    let hasMore = true;
    let activeFilters = { categoryIds: [], subCategoryIds: [], priceRanges: [] };
    let isFiltered = false;

    window.addEventListener('scroll', function () {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50) {
            loadMore();
        }
    });

    function loadMore() {
        if (!hasMore || isLoading) return;

        isLoading = true;
        document.getElementById('loading').style.display = 'block';

        let fetchPromise;

        if (isFiltered) {
            fetchPromise = fetch("{{ route('filter') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ...activeFilters, offset: offset })
            });
        } else {
            fetchPromise = fetch(`{{ url()->current() }}?offset=${offset}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
        }

        fetchPromise
            .then(res => res.json())
            .then(data => {
                document.getElementById('cardBody').insertAdjacentHTML('beforeend', data.html);
                offset += 5;
                hasMore = data.hasMore;
                isLoading = false;
                document.getElementById('loading').style.display = 'none';
            })
            .catch(() => {
                isLoading = false;
                document.getElementById('loading').style.display = 'none';
            });
    }

    function filterProducts() {
        let categoryIds = [...document.querySelectorAll('.category-checkbox:checked')].map(el => el.value);
        let subCategoryIds = [...document.querySelectorAll('.subcategory-checkbox:checked')].map(el => el.value);
        let priceRanges = [...document.querySelectorAll('.price-checkbox:checked')].map(el => el.value);

        activeFilters = { categoryIds, subCategoryIds, priceRanges };
        isFiltered = categoryIds.length > 0 || subCategoryIds.length > 0 || priceRanges.length > 0;

        offset = 5;
        hasMore = true;

        fetch("{{ route('filter') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ...activeFilters, offset: 0 })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('cardBody').innerHTML = data.html;
            hasMore = data.hasMore;
        });
    }

    document.addEventListener('change', function(e) {
        if (
            e.target.classList.contains('category-checkbox') ||
            e.target.classList.contains('subcategory-checkbox') ||
            e.target.classList.contains('price-checkbox')
        ) {
            filterProducts();
        }
    });

    function toggleDropdown(btnId, dropdownClass, angleClass) {
        let dropdown = document.querySelector(dropdownClass);
        let angle = document.querySelector(angleClass);
        let isOpen = dropdown.style.display === 'block';
        dropdown.style.display = isOpen ? 'none' : 'block';
        angle.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        angle.style.transition = '0.3s';
    }

    document.querySelector('#category').addEventListener('click', () => toggleDropdown('#category', '.category-dropdown', '.angle-category'));
    document.querySelector('#subCategory').addEventListener('click', () => toggleDropdown('#subCategory', '.subCategory-dropdown', '.angle-subCategory'));
    document.querySelector('#price').addEventListener('click', () => toggleDropdown('#price', '.price-dropdown', '.angle-price'));

</script>
   
@endsection