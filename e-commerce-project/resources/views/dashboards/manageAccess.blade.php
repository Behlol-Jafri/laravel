@extends('dashboards.dashboardLayout')



@section('content')
   <div class="m-3">
      @if (Auth::user()->hasRole('Super Admin'))
      <a href="{{ route('roles.index') }}" class="btn btn-primary mx-1">Roles</a>
      <a href="{{ route('permissions.index') }}" class="btn btn-success mx-1">Permissions</a>
      @endif
      @can('view user')
        <a href="{{ route('data') }}" class="btn btn-warning mx-1">users</a>
      @endcan
   </div>
   @yield('permission-data')
@endsection
