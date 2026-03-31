@extends('home')

@section('data')
    <header class="bg-primary rounded d-flex justify-content-between text-white p-2 m-2">
        <h5>{{ Auth::user()->getRoleNames()->first() }}, Dashboard</h5>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button class="btn btn-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
        </form>
    </header>
    <main style="height: calc(100vh - 50px);">
        
        <div class="row h-100 ms-0">
            <div class="col-2 bg-warning ms-3 rounded p-0" style="height: 100%; overflow-y: auto; position: sticky; top: 0;">
                @include('dashboards.sidebar')
            </div>
            <div class="col overflow-y-auto h-100">
                @yield('content')
            </div>
        </div>
        
    </main>
    <footer class="bg-secondary p-1 rounded mt-3">
        <p>e-commercewebsite@copyright.2020</p>
    </footer>
@endsection