@extends('layouts.app')
@section('title', 'My Vendors')
@section('page-title', 'My Vendors')

@section('content')
<div class="page-header">
    <div><h1>My Vendors</h1><p>Vendors you have been granted access to by Super Admin.</p></div>
</div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Vendor</th><th>Phone</th><th>Products</th><th>Joined</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($vendors as $v)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-sm" style="background:#cffafe;color:#0891b2">{{ strtoupper(substr($v->name,0,1)) }}</div>
                            <div><div class="fw-600">{{ $v->name }}</div><small class="text-muted">{{ $v->email }}</small></div>
                        </div>
                    </td>
                    <td>{{ $v->phone ?? '—' }}</td>
                    <td>{{ $v->products()->count() }}</td>
                    <td class="text-muted small">{{ $v->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.vendors.show', $v) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-5">
                    <i class="bi bi-shop display-6 d-block mb-2 opacity-25"></i>
                    No vendors assigned. Ask Super Admin to grant access.
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($vendors->hasPages())<div class="px-4 py-3 border-top">{{ $vendors->links() }}</div>@endif
</div>
@endsection
