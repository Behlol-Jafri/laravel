@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">View Product</h6>
                    </div>
                    <div class="card-body">
                        <h6>Title : {{ ucwords($product->title) }}</h6>
                        <p>Description : {{ $product->description }}</p>
                        <p>Price : {{ $product->price }}</p>
                        <p>Category_Id : {{ $product->subCategory_id }}</p>
                        <a href="{{ route('subCategory.product.index',$subCategory) }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
    </div>
@endsection