<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RBAC System') | RoleGuard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --color-super: #dc3545;
            --color-admin: #fd7e14;
            --color-vendor: #0dcaf0;
            --color-user: #198754;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #1e293b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            margin: 0;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            font-weight: 800;
            color: #fff;
        }

        .brand-super  { background: linear-gradient(135deg,#dc3545,#c0392b); }
        .brand-admin  { background: linear-gradient(135deg,#fd7e14,#e67e22); }
        .brand-vendor { background: linear-gradient(135deg,#0dcaf0,#0891b2); }
        .brand-user   { background: linear-gradient(135deg,#198754,#16a34a); }

        .sidebar-brand .brand-text {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .sidebar-brand .brand-role {
            font-size: 10px;
            font-weight: 500;
            color: var(--sidebar-text);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; }

        .nav-section-title {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #475569;
            padding: 16px 12px 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all .15s ease;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }

        .sidebar-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        .sidebar-link i { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-link .badge-count {
            margin-left: auto;
            background: rgba(255,255,255,.1);
            color: #fff;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 20px;
        }

        /* Role pill in sidebar */
        .sidebar-user-info {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar-sm {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #334155;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-user-name { font-size: 13px; font-weight: 600; color: #e2e8f0; }
        .sidebar-user-role { font-size: 11px; color: var(--sidebar-text); }

        /* ── Main content ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            flex: 1;
        }

        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .role-super_admin { background:#fee2e2; color:#dc2626; }
        .role-admin       { background:#ffedd5; color:#ea580c; }
        .role-vendor      { background:#cffafe; color:#0891b2; }
        .role-user        { background:#dcfce7; color:#16a34a; }

        /* ── Page content ── */
        .page-content { padding: 28px; flex: 1; }

        /* ── Cards ── */
        .card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 22px;
            font-weight: 600;
            font-size: 15px;
        }

        /* ── Stat cards ── */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,.09);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-icon-red    { background:#fee2e2; color:#dc2626; }
        .stat-icon-orange { background:#ffedd5; color:#ea580c; }
        .stat-icon-blue   { background:#dbeafe; color:#2563eb; }
        .stat-icon-green  { background:#dcfce7; color:#16a34a; }
        .stat-icon-purple { background:#f3e8ff; color:#9333ea; }
        .stat-icon-cyan   { background:#cffafe; color:#0891b2; }

        .stat-value { font-size: 26px; font-weight: 700; color: #0f172a; line-height: 1; }
        .stat-label { font-size: 13px; color: #64748b; margin-top: 3px; }

        /* ── Tables ── */
        .table-card { background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.06); }
        .table { margin:0; }
        .table thead th { background:#f8fafc; border-bottom:2px solid #e2e8f0; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.8px; color:#64748b; padding:14px 18px; }
        .table tbody td { padding:14px 18px; vertical-align:middle; border-color:#f1f5f9; font-size:14px; }
        .table tbody tr:hover { background:#f8fafc; }

        /* ── Badges ── */
        .badge-super_admin { background:#fee2e2; color:#dc2626; }
        .badge-admin       { background:#ffedd5; color:#ea580c; }
        .badge-vendor      { background:#cffafe; color:#0891b2; }
        .badge-user        { background:#dcfce7; color:#16a34a; }
        .badge-pending     { background:#fef9c3; color:#ca8a04; }
        .badge-processing  { background:#dbeafe; color:#2563eb; }
        .badge-completed   { background:#dcfce7; color:#16a34a; }
        .badge-cancelled   { background:#fee2e2; color:#dc2626; }

        .pill-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        /* ── Buttons ── */
        .btn { font-size: 13px; font-weight: 500; border-radius: 8px; padding: 8px 16px; }
        .btn-sm { padding: 5px 11px; font-size: 12px; }
        .btn-primary { background:#2563eb; border-color:#2563eb; }
        .btn-primary:hover { background:#1d4ed8; border-color:#1d4ed8; }

        /* ── Access management checkboxes ── */
        .access-user-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all .15s ease;
        }

        .access-user-card:hover { border-color: #93c5fd; background:#eff6ff; }
        .access-user-card.selected { border-color: #2563eb; background:#eff6ff; }

        .access-check input { display:none; }
        .access-check-box {
            width: 20px; height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
            transition: all .15s ease;
            flex-shrink: 0;
        }

        .access-user-card.selected .access-check-box {
            background: #2563eb; border-color: #2563eb;
        }

        .access-user-card.selected .access-check-box::after {
            content: '✓';
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        /* ── Alerts ── */
        .alert { border: 0; border-radius: 10px; font-size: 14px; }
        .alert-success { background: #f0fdf4; color: #166534; }
        .alert-danger  { background: #fef2f2; color: #991b1b; }
        .alert-info    { background: #eff6ff; color: #1e40af; }
        .alert-warning { background: #fffbeb; color: #92400e; }

        /* ── Forms ── */
        .form-control, .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            padding: 9px 14px;
            transition: border-color .15s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header h1 { font-size: 22px; font-weight: 700; color: #0f172a; margin: 0; }
        .page-header p  { font-size: 13px; color: #64748b; margin: 0; }

        /* ── Access grant card ── */
        .grant-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            gap: 12px;
        }

        .grant-item:last-child { border-bottom: none; }

        /* Mobile responsive */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar { padding: 0 16px; }
            .page-content { padding: 16px; }
        }
    </style>

    @stack('styles')
</head>
<body>

@auth
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        @php $role = auth()->user()->role; @endphp
        <div class="brand-icon brand-{{ str_replace('_','-',$role) }}">
            @if($role === 'super_admin') SA
            @elseif($role === 'admin') AD
            @elseif($role === 'vendor') VN
            @else US @endif
        </div>
        <div>
            <div class="brand-text">RoleGuard</div>
            <div class="brand-role">{{ auth()->user()->getRoleLabel() }} Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @include('partials.sidebar-nav')
    </nav>

    <div class="sidebar-user-info">
        <div class="user-avatar-sm">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
        <div>
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">{{ auth()->user()->email }}</div>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <header class="topbar">
        <button class="btn btn-sm btn-light d-lg-none me-2" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-actions">
            <span class="role-badge role-{{ auth()->user()->role }}">
                {{ auth()->user()->getRoleLabel() }}
            </span>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->email }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@else
    @yield('content')
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    }

    // Access card checkbox behavior
    document.querySelectorAll('.access-user-card').forEach(card => {
        card.addEventListener('click', function () {
            this.classList.toggle('selected');
            const cb = this.querySelector('input[type=checkbox]');
            if (cb) cb.checked = !cb.checked;
        });
    });
</script>
@stack('scripts')
</body>
</html>
