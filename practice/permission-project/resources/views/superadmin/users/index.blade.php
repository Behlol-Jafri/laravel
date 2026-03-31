@extends('layouts.app')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')
<div class="page-header">
    <div>
        <h1>All Users</h1>
        <p>Manage all platform users across every role.</p>
    </div>
    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>Create User
    </a>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search name or email…"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="super_admin" {{ request('role')=='super_admin'?'selected':'' }}>Super Admin</option>
                    <option value="admin"       {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="vendor"      {{ request('role')=='vendor'?'selected':'' }}>Vendor</option>
                    <option value="user"        {{ request('role')=='user'?'selected':'' }}>User</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('superadmin.users') }}" class="btn btn-light ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $u)
                <tr>
                    <td class="text-muted">{{ $u->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-sm" style="background:#e2e8f0;color:#475569">
                                {{ strtoupper(substr($u->name,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-600">{{ $u->name }}</div>
                                <small class="text-muted">{{ $u->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="pill-badge badge-{{ $u->role }}">{{ $u->getRoleLabel() }}</span></td>
                    <td>{{ $u->phone ?? '—' }}</td>
                    <td>
                        @if($u->is_active)
                            <span class="pill-badge badge-completed">Active</span>
                        @else
                            <span class="pill-badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('superadmin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('superadmin.users.toggle', $u) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $u->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="bi bi-{{ $u->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            @if(!$u->isSuperAdmin() || \App\Models\User::where('role','super_admin')->count() > 1)
                            <form method="POST" action="{{ route('superadmin.users.destroy', $u) }}"
                                  onsubmit="return confirm('Delete {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-people display-6 d-block mb-2 opacity-25"></i>
                        No users found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="px-4 py-3 border-top">{{ $users->links() }}</div>
    @endif
</div>
@endsection
