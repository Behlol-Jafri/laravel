@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h3 class="fw-bold mb-0">
        @if(auth()->user()->isAdmin())
          All Users
        @else
          Users You Can Access
        @endif
      </h3>
      <p class="text-muted small mb-0">
        @if(auth()->user()->isAdmin())
          Admin view — full access to all records
        @else
          Only showing users you have permission to view
        @endif
      </p>
    </div>
    @if(auth()->user()->isAdmin())
      <a href="{{ route('admin.permissions') }}" class="btn btn-warning btn-sm">
        Manage Permissions
      </a>
    @endif
  </div>

  @if($users->isEmpty())
    <div class="alert alert-info">
      <strong>No access yet.</strong> Ask admin to grant you permission to view other users.
    </div>
  @else
  <div class="row g-3">
    @foreach($users as $index => $user)
    <div class="col-md-6 col-lg-4">
      <div class="card border shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
              style="width:48px;height:48px;flex-shrink:0;background:{{ ['#1D9E75','#378ADD','#D85A30','#D4537E','#7F77DD'][$index % 5] }}">
              {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
              <div class="fw-semibold">{{ $user->name }}</div>
              <div class="text-muted small">{{ $user->email }}</div>
            </div>
          </div>
          <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary w-100">
            View Profile
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif

</div>
@endsection