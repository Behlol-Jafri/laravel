@extends('layouts.app')
@section('title', 'My Customers')
@section('page-title', 'My Customers')

@section('content')
<div class="page-header">
    <div><h1>My Customers</h1><p>Users you have granted access to your store.</p></div>
    <a href="{{ route('vendor.access') }}" class="btn btn-primary">
        <i class="bi bi-shield-plus me-2"></i>Manage Access
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Customer</th><th>Phone</th><th>Orders</th><th>Joined</th><th>Action</th></tr>
            </thead>
            <tbody>
            @forelse($users as $u)
                <tr>
                    <td class="text-muted">{{ $u->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-sm" style="background:#dcfce7;color:#16a34a">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-600">{{ $u->name }}</div>
                                <small class="text-muted">{{ $u->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $u->phone ?? '—' }}</td>
                    <td>{{ $u->orders()->count() }}</td>
                    <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('vendor.users.show', $u) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-people display-6 d-block mb-2 opacity-25"></i>
                        No customers yet. <a href="{{ route('vendor.access') }}">Grant access to users</a>.
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
