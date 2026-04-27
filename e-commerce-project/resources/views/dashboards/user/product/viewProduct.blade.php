@extends('dashboards.user.userDashboard')

@section('content')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">View Product</h6>
                    </div>
                    <div class="card-body">
                        <h6>Title : {{ $product->title }}</h6>
                        <p>Description : {{ $product->description }}</p>
                        <p>Price : {{ $product->price }}</p>
                        <p>Quantity : {{ $product->quantity }}</p>
                        <p>User_Id : {{ $product->user_id }}</p>
                        <p>Category_Id : {{ $product->category_id }}</p>
                        <p>SubCategory_Id : {{ $product->subCategory_id }}</p>
                        <p>Images :</p>
                        <div class="row mb-3">
                            @foreach ($product->images as $image)
                                <div class="col-3">
                                    <img src="{{ asset($image->image) }}" class="rounded w-50">
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('product.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
    </div>
@endsection