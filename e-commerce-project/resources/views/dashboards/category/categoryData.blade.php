@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
        @can('create category')
            <a href="{{ route('category.create') }}" class="btn btn-primary mb-3" >Add Category</a>
        @endcan
        <div class="row">
            <h5 class="bg-primary text-white rounded p-2">Categories Data</h5>
           <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>User_Id</th>
                    <th class="text-center" colspan="4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                <tr>
                    <td>{{ $index+1 }}</td>
                        <td>{{ ucwords($category->title) }}</td>
                        <td>{{ $category->description }}</td>
                        <td>{{ $category->user_id }}</td>
                        @can('view subCategory')
                            <td class="text-center">
                                <a href="{{ route('category.subCategory.index',$category) }}" class="btn btn-primary">SubCategories</a>
                            </td>
                        @endcan
                        <td class="text-center"><a href="{{ route('category.show', $category->id) }}" class="btn btn-success"><i class="fa-solid fa-eye fa-sm"></i></a></td>
                        @can('update category')
                            <td class="text-center"><a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square fa-sm"></i></a></td>
                        @endcan
                        @can('delete category')
                            <td class="text-center">
                            <form action="{{ route('category.destroy', $category->id) }}" method="post">
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