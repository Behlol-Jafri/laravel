@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit: {{ $user->name }}</h1>
        <p>Update user information and role.</p>
    </div>
    <a href="{{ route('superadmin.users') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Details</div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.users.update', $user) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role *</label>
                            <select name="role" class="form-select" required>
                                <option value="super_admin" {{ old('role',$user->role)=='super_admin'?'selected':'' }}>Super Admin</option>
                                <option value="admin"       {{ old('role',$user->role)=='admin'?'selected':'' }}>Admin</option>
                                <option value="vendor"      {{ old('role',$user->role)=='vendor'?'selected':'' }}>Vendor</option>
                                <option value="user"        {{ old('role',$user->role)=='user'?'selected':'' }}>User</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-muted">(leave blank to keep)</span></label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                       {{ $user->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Account Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>Update User
                            </button>
                            <a href="{{ route('superadmin.users') }}" class="btn btn-light ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
