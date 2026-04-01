@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('category.subCategory.index',$category) }}" class="text-danger text-center p-3 d-block text-decoration-none fs-3 fw-semibold">{{ ucwords($subCategory->title) }}</a>
        @can('create product')
            <a href="{{ route('subCategory.product.create',$subCategory) }}" class="btn btn-primary mb-3" >Add Product</a>
        @endcan
        <div class="row">
            <h5 class="bg-primary text-white rounded p-2">Product Data</h5>
           <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>SubCategory_Id</th>
                    <th class="text-center" colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                        <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ ucwords($product->title) }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->subCategory_id }}</td>
                        <td class="text-center"><a href="{{ route('subCategory.product.show', [$subCategory,$product]) }}" class="btn btn-success"><i class="fa-solid fa-eye fa-sm"></i></a></td>
                        @can('update product')
                            <td class="text-center"><a href="{{ route('subCategory.product.edit', [$subCategory,$product]) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square fa-sm"></i></a></td>
                        @endcan
                        @can('delete product')
                            <td class="text-center">
                            <form action="{{ route('subCategory.product.destroy', [$subCategory,$product]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"><i class="fa-solid fa-trash fa-sm"></i></button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
           </table>
        </div>
    </div>
@endsection