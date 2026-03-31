@extends('layouts.app')
@section('title', 'Create User')
@section('page-title', 'Create User')

@section('content')
<div class="page-header">
    <h1>Create User</h1>
    <a href="{{ route('admin.users') }}" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Back</a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-plus me-2"></i>User Details</div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-3"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
                        <div class="col-md-6"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
                        <div class="col-12"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
                        <div class="col-12"><button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Create</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
