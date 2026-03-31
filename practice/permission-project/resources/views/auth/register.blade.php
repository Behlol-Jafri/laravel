@extends('layouts.app')
@section('title', 'Register')

@section('content')
<style>
    body { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); min-height: 100vh; }
    .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .auth-card { background: #fff; border-radius: 20px; padding: 44px; width: 100%; max-width: 500px; box-shadow: 0 25px 60px rgba(0,0,0,.35); }
    .auth-logo { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #0f172a; }
    .auth-logo span { color: #2563eb; }
    .role-select-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .role-option { display: none; }
    .role-label {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 16px 10px; border-radius: 10px; border: 2px solid #e2e8f0;
        cursor: pointer; transition: all .15s ease; text-align: center;
        font-size: 12px; font-weight: 600;
    }
    .role-label i { font-size: 22px; margin-bottom: 6px; }
    .role-option:checked + .role-label { border-color: #2563eb; background: #eff6ff; color: #2563eb; }
    .rl-admin  .role-label:hover, .role-option:checked + .role-label { }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="auth-logo">Role<span>Guard</span></div>
            <p class="text-muted small mt-1">Create your account</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="John Doe"
                           value="{{ old('name') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com"
                           value="{{ old('email') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Phone <span class="text-muted">(optional)</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="+92 300 0000000"
                           value="{{ old('phone') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Register As</label>
                <div class="role-select-grid">
                    <div class="rl-admin">
                        <input type="radio" name="role" value="admin" id="role_admin" class="role-option"
                               {{ old('role') === 'admin' ? 'checked' : '' }}>
                        <label for="role_admin" class="role-label">
                            <i class="bi bi-person-badge text-warning"></i>
                            Admin
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="role" value="vendor" id="role_vendor" class="role-option"
                               {{ old('role') === 'vendor' ? 'checked' : '' }}>
                        <label for="role_vendor" class="role-label">
                            <i class="bi bi-shop text-info"></i>
                            Vendor
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="role" value="user" id="role_user" class="role-option"
                               {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                        <label for="role_user" class="role-label">
                            <i class="bi bi-person text-success"></i>
                            User
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </button>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted small">Already have an account? </span>
            <a href="{{ route('login') }}" class="small fw-600 text-decoration-none">Sign in</a>
        </div>
    </div>
</div>
@endsection
