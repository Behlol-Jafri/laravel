@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">View Category</h6>
                    </div>
                    <div class="card-body">
                        <h6>Title : {{ $category->title }}</h6>
                        <p>Description : {{ $category->description }}</p>
                        <p>User_Id : {{ $category->user_id }}</p>
                        <a href="{{ route('category.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
    </div>
@endsection