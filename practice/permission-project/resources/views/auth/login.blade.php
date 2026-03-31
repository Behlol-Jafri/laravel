@extends('layouts.app')
@section('title', 'Login')

@section('content')
<style>
    body { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); min-height: 100vh; }
    .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .auth-card { background: #fff; border-radius: 20px; padding: 44px 44px; width: 100%; max-width: 440px; box-shadow: 0 25px 60px rgba(0,0,0,.35); }
    .auth-logo { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #0f172a; }
    .auth-logo span { color: #2563eb; }
    .auth-subtitle { color: #64748b; font-size: 14px; margin-top: 6px; }
    .divider { height: 1px; background: #f1f5f9; margin: 28px 0; }
    .demo-pills { display: flex; flex-wrap: wrap; gap: 8px; }
    .demo-pill {
        padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
        cursor: pointer; border: 1.5px solid transparent; transition: all .15s;
    }
    .demo-pill:hover { transform: translateY(-1px); }
    .dp-super { background:#fee2e2; color:#dc2626; border-color:#fecaca; }
    .dp-admin { background:#ffedd5; color:#ea580c; border-color:#fed7aa; }
    .dp-vendor{ background:#cffafe; color:#0891b2; border-color:#a5f3fc; }
    .dp-user  { background:#dcfce7; color:#16a34a; border-color:#bbf7d0; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="auth-logo">Role<span>Guard</span></div>
            <div class="auth-subtitle">Sign in to your account</div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0"
                           placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0"
                           placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-600">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="divider"></div>

        <div class="mb-3">
            <p class="text-muted small mb-2 fw-600">Quick Demo Login:</p>
            <div class="demo-pills">
                <span class="demo-pill dp-super" onclick="fillDemo('superadmin@demo.com')">
                    <i class="bi bi-shield-fill-check me-1"></i>Super Admin
                </span>
                <span class="demo-pill dp-admin" onclick="fillDemo('admin@demo.com')">
                    <i class="bi bi-person-badge me-1"></i>Admin
                </span>
                <span class="demo-pill dp-vendor" onclick="fillDemo('vendor@demo.com')">
                    <i class="bi bi-shop me-1"></i>Vendor
                </span>
                <span class="demo-pill dp-user" onclick="fillDemo('user@demo.com')">
                    <i class="bi bi-person me-1"></i>User
                </span>
            </div>
        </div>

        <div class="text-center mt-3">
            <span class="text-muted small">Don't have an account? </span>
            <a href="{{ route('register') }}" class="small fw-600 text-decoration-none">Register here</a>
        </div>
    </div>
</div>

<script>
function fillDemo(email) {
    document.querySelector('input[name=email]').value = email;
    document.querySelector('input[name=password]').value = 'password';
}
</script>
@endsection
