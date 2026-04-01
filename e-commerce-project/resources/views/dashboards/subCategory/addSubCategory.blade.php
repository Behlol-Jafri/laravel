@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
        <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">Add Sub Category</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.subCategory.store',$category) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Title">
                                 @error('title')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Description">
                                 @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="">
                                 <a href="{{ route('category.subCategory.index',$category) }}" class="btn btn-secondary me-3">Cancle</a>
                                <button type="submit" class="btn btn-primary">Add Sub Category</button>
                            </div>
                        </form>
                    </div>
        </div>
    </div>
@endsection