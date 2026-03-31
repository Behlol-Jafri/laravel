@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-7">

      <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        ← Back
      </a>

      <div class="card border shadow-sm">
        <div class="card-header bg-white d-flex align-items-center gap-3 py-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-primary"
            style="width:52px;height:52px;flex-shrink:0">
            {{ strtoupper(substr($user->name, 0, 2)) }}
          </div>
          <div>
            <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
            <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-secondary' }}">
              {{ ucfirst($user->role) }}
            </span>
          </div>
        </div>

        <div class="card-body">
          <table class="table table-borderless">
            <tr>
              <td class="text-muted" width="140">Email</td>
              <td class="fw-semibold">{{ $user->email }}</td>
            </tr>
            <tr>
              <td class="text-muted">Role</td>
              <td class="fw-semibold">{{ ucfirst($user->role) }}</td>
            </tr>
            <tr>
              <td class="text-muted">Joined</td>
              <td class="fw-semibold">{{ $user->created_at->format('d M Y') }}</td>
            </tr>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection