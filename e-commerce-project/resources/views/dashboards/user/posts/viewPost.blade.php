@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">View Post</h6>
                    </div>
                    <div class="card-body">
                        <h6>Title : {{ $post->title }}</h6>
                        <p>Description : {{ $post->description }}</p>
                        <p>User_Id : {{ $post->user_id }}</p>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
    </div>
@endsection