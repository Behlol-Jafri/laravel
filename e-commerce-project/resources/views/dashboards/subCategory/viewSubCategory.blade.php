@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">View Sub Category</h6>
                    </div>
                    <div class="card-body">
                        <h6>Title : {{ ucwords($subCategory->title) }}</h6>
                        <p>Description : {{ $subCategory->description }}</p>
                        <p>Category_Id : {{ $subCategory->category_id }}</p>
                        <a href="{{ route('category.subCategory.index',$category) }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
    </div>
@endsection