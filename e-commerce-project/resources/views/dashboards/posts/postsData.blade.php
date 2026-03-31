@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
        @can('create post')
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3" >Add Post</a>
        @endcan
        <div class="row">
            <h5 class="bg-primary text-white rounded p-2">Posts Data</h5>
           <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>User_Id</th>
                    <th class="text-center" colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $index => $post)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->description }}</td>
                        <td>{{ $post->user_id }}</td>
                        <td class="text-center"><a href="{{ route('posts.show', $post->id) }}" class="btn btn-success"><i class="fa-solid fa-eye fa-sm"></i></a></td>
                        @can('update post')
                        <td class="text-center"><a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square fa-sm"></i></a></td>
                        @endcan
                        @can('delete post')
                        <td class="text-center">
                            <form action="{{ route('posts.destroy', $post->id) }}" method="post">
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