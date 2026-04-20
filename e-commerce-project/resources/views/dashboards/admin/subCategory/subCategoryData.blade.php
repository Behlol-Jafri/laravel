@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
        <div class="text-center">
            <a href="{{ route('category.index') }}" class="text-danger p-1 fs-3 fw-semibold">{{ ucwords($category->title) }}</a>
        </div>
        @can('create subCategory')
            <a href="{{ route('category.subCategory.create',$category) }}" class="btn btn-primary mb-3" >Add Sub Category</a>
        @endcan
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="row">
            <h5 class="bg-primary text-white rounded p-2">Sub Categories Data</h5>
           <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category_Id</th>
                    <th class="text-center" colspan="4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subCategories as $index => $subCategory)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $subCategory->title }}</td>
                        <td>{{ $subCategory->description }}</td>
                        <td>{{ $subCategory->category_id }}</td>
                        <td class="text-center"><a href="{{ route('category.subCategory.show', [$category, $subCategory]) }}" class="btn btn-success"><i class="fa-solid fa-eye fa-sm"></i></a></td>
                        @can('update subCategory')
                            <td class="text-center"><a href="{{ route('category.subCategory.edit', [$category, $subCategory]) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square fa-sm"></i></a></td>
                        @endcan
                        @can('delete subCategory')
                            <td class="text-center">
                            <form action="{{ route('category.subCategory.destroy', [$category, $subCategory]) }}" method="post">
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