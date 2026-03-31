@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="page-header"><h1>My Profile</h1></div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                    <div class="user-avatar-sm" style="width:64px;height:64px;font-size:26px;background:#dbeafe;color:#2563eb">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-700 mb-1">{{ $user->name }}</h5>
                        <span class="role-badge role-{{ $user->role }}">{{ $user->getRoleLabel() }}</span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Email</label>
                        <div class="fw-600">{{ $user->email }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Phone</label>
                        <div class="fw-600">{{ $user->phone ?? '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Member Since</label>
                        <div class="fw-600">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted small">Total Orders</label>
                        <div class="fw-600">{{ $user->orders()->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
