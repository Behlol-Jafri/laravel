@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
        @can('create product')
            <a href="{{ route('product.create') }}" class="btn btn-primary mb-3" >Add Product</a>
        @endcan
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="row">
            <div class="row mb-3">
                @if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'))
                    <div class="col">
                        <select name="vender_id" id="vender" class="form-select">
                            <option value="">All Vender</option>
                            @foreach ($users as $user)
                                @if ($user->hasRole('Vender'))
                                    <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->secondName }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col">
                    <select name="category_id" id="category" class="form-select">
                        <option value="">All Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <select name="subCategory_id" id="subCategory" class="form-select">
                        <option value="">All SubCategory</option>
                         @foreach ($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}">{{ $subCategory->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <h5 class="bg-primary text-white rounded p-2">Product Data
                {{-- <span id="productCount" class="float-end text-warning">Total Products : {{ $products->total() }}</span> --}}
            </h5>
           <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>User_Id</th>
                    <th>Category_Id</th>
                    <th>SubCategory_Id</th>
                    <th class="text-center" colspan="4">Actions</th>
                </tr>
            </thead>
            <tbody id="tblBody">
                @include('dashboards.product.productTable')
            </tbody>
           </table>
        </div>
    </div>
<div class="d-flex justify-content-between">
    <div id="productCount">
        Total Products : {{ $products->total() }}
    </div>
    <div id="paginationLinks">
        {{ $products->links() }}
    </div>
</div>




<script>
    const venderSelect = document.getElementById('vender');
    const categorySelect = document.getElementById('category');
    const subCategorySelect = document.getElementById('subCategory');

    function filterProducts(page = 1) {
        let vendorId = venderSelect?.value;
        let categoryId = categorySelect?.value;
        let subCategoryId = subCategorySelect?.value;

        let ids = {
            page: page
        };

        if(vendorId){
            ids.venderId = vendorId;
        }
        if(categoryId){
             ids.categoryId = categoryId;
        }
        if(subCategoryId){
             ids.subCategoryId = subCategoryId;
        }


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
            document.getElementById('tblBody').innerHTML = data.html;
            document.getElementById('paginationLinks').innerHTML = data.pagination;
            document.getElementById('productCount').innerText = 'Total Products : ' + data.count;
        })
    }

    document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        e.preventDefault();

        let url = e.target.closest('a').getAttribute('href');
        let page = new URL(url).searchParams.get('page');

        filterProducts(page);
    }
}); 


    if(venderSelect){
        venderSelect.addEventListener('change', filterProducts);
    }
    categorySelect.addEventListener('change', filterProducts);
    subCategorySelect.addEventListener('change', filterProducts);
</script>
   
@endsection