@extends('layouts.app')
@section('title', 'All Access Grants')
@section('page-title', 'All Access Grants')

@section('content')
<div class="page-header">
    <div>
        <h1>All Access Grants</h1>
        <p>Complete overview of every access grant on the platform.</p>
    </div>
    <a href="{{ route('superadmin.access') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Grant Access
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Granter</th>
                    <th>Grantee</th>
                    <th>Access Level</th>
                    <th>Status</th>
                    <th>Expires</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
            @forelse($grants as $g)
                <tr>
                    <td class="text-muted">{{ $g->id }}</td>
                    <td>
                        <div class="fw-600">{{ $g->granter->name }}</div>
                        <span class="pill-badge badge-{{ $g->granter->role }}" style="font-size:10px">
                            {{ $g->granter->getRoleLabel() }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-600">{{ $g->grantee->name }}</div>
                        <span class="pill-badge badge-{{ $g->grantee->role }}" style="font-size:10px">
                            {{ $g->grantee->getRoleLabel() }}
                        </span>
                    </td>
                    <td>
                        <span class="pill-badge badge-processing">{{ ucfirst($g->access_level) }}</span>
                    </td>
                    <td>
                        @if($g->is_active)
                            <span class="pill-badge badge-completed">Active</span>
                        @else
                            <span class="pill-badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $g->expires_at ? $g->expires_at->format('d M Y') : '—' }}
                    </td>
                    <td class="text-muted small">{{ $g->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-shield-x display-6 d-block mb-2 opacity-25"></i>
                        No access grants found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($grants->hasPages())
        <div class="px-4 py-3 border-top">{{ $grants->links() }}</div>
    @endif
</div>
@endsection
