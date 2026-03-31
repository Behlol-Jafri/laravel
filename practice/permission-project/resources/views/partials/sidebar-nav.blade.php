@php $role = auth()->user()->role; @endphp

@if($role === 'super_admin')
    <div class="nav-section-title">Main</div>
    <a href="{{ route('superadmin.dashboard') }}" class="sidebar-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <div class="nav-section-title">User Management</div>
    <a href="{{ route('superadmin.users') }}" class="sidebar-link {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> All Users
    </a>

    <div class="nav-section-title">Access Control</div>
    <a href="{{ route('superadmin.access') }}" class="sidebar-link {{ request()->routeIs('superadmin.access') ? 'active' : '' }}">
        <i class="bi bi-shield-lock-fill"></i> Grant Access
    </a>
    <a href="{{ route('superadmin.access.all') }}" class="sidebar-link {{ request()->routeIs('superadmin.access.all') ? 'active' : '' }}">
        <i class="bi bi-diagram-3-fill"></i> All Grants
    </a>

    <div class="nav-section-title">Data</div>
    <a href="{{ route('superadmin.products') }}" class="sidebar-link {{ request()->routeIs('superadmin.products') ? 'active' : '' }}">
        <i class="bi bi-box-seam-fill"></i> Products
    </a>
    <a href="{{ route('superadmin.orders') }}" class="sidebar-link {{ request()->routeIs('superadmin.orders') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff"></i> Orders
    </a>

@elseif($role === 'admin')
    <div class="nav-section-title">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <div class="nav-section-title">Access Control</div>
    <a href="{{ route('admin.access') }}" class="sidebar-link {{ request()->routeIs('admin.access') ? 'active' : '' }}">
        <i class="bi bi-shield-lock-fill"></i> Manage Access
    </a>

    <div class="nav-section-title">Vendors</div>
    <a href="{{ route('admin.vendors') }}" class="sidebar-link {{ request()->routeIs('admin.vendors*') ? 'active' : '' }}">
        <i class="bi bi-shop"></i> My Vendors
    </a>

    <div class="nav-section-title">Users</div>
    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <i class="bi bi-person-lines-fill"></i> All Users
    </a>

    <div class="nav-section-title">Data</div>
    <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
        <i class="bi bi-box-seam-fill"></i> Products
    </a>
    <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff"></i> Orders
    </a>

@elseif($role === 'vendor')
    <div class="nav-section-title">Main</div>
    <a href="{{ route('vendor.dashboard') }}" class="sidebar-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <div class="nav-section-title">Access Control</div>
    <a href="{{ route('vendor.access') }}" class="sidebar-link {{ request()->routeIs('vendor.access') ? 'active' : '' }}">
        <i class="bi bi-shield-lock-fill"></i> Manage Access
    </a>

    <div class="nav-section-title">My Store</div>
    <a href="{{ route('vendor.products') }}" class="sidebar-link {{ request()->routeIs('vendor.products*') ? 'active' : '' }}">
        <i class="bi bi-box-seam-fill"></i> Products
    </a>
    <a href="{{ route('vendor.orders') }}" class="sidebar-link {{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff"></i> Orders
    </a>

    <div class="nav-section-title">Customers</div>
    <a href="{{ route('vendor.users') }}" class="sidebar-link {{ request()->routeIs('vendor.users*') ? 'active' : '' }}">
        <i class="bi bi-person-lines-fill"></i> My Users
    </a>

@else {{-- user --}}
    <div class="nav-section-title">Main</div>
    <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>

    <div class="nav-section-title">Shop</div>
    <a href="{{ route('user.products') }}" class="sidebar-link {{ request()->routeIs('user.products') ? 'active' : '' }}">
        <i class="bi bi-bag-fill"></i> Browse Products
    </a>
    <a href="{{ route('user.orders') }}" class="sidebar-link {{ request()->routeIs('user.orders') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff"></i> My Orders
    </a>
    <a href="{{ route('user.profile') }}" class="sidebar-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
        <i class="bi bi-person-fill"></i> Profile
    </a>
@endif
