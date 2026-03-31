@extends('layouts.app')
@section('title', 'All Users')
@section('page-title', 'User Management')

@section('content')
<div class="page-header">
    <div><h1>All Users</h1></div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Create User</a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search name or email…" value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Search</button>
                <a href="{{ route('admin.users') }}" class="btn btn-light ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>User</th><th>Phone</th><th>Joined</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($users as $u)
                <tr>
                    <td class="text-muted">{{ $u->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-sm" style="background:#dcfce7;color:#16a34a">{{ strtoupper(substr($u->name,0,1)) }}</div>
                            <div><div class="fw-600">{{ $u->name }}</div><small class="text-muted">{{ $u->email }}</small></div>
                        </div>
                    </td>
                    <td>{{ $u->phone ?? '—' }}</td>
                    <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline"
                              onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-5">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div class="px-4 py-3 border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
