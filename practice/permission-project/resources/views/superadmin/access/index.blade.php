@extends('layouts.app')
@section('title', 'Access Management')
@section('page-title', 'Access Management')

@section('content')
<div class="page-header">
    <div>
        <h1>Grant & Manage Access</h1>
        <p>Control which admins can see vendor data, and which vendors can see user data.</p>
    </div>
    <a href="{{ route('superadmin.access.all') }}" class="btn btn-outline-primary">
        <i class="bi bi-diagram-3 me-2"></i>View All Grants
    </a>
</div>

{{-- Access hierarchy info --}}
<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle me-2"></i>
    <strong>How it works:</strong>
    Super Admin → grants Admin access to Vendors &nbsp;|&nbsp;
    Admin → grants Vendor access to Users &nbsp;|&nbsp;
    Vendor → grants access to Users
</div>

<div class="row g-4">
    {{-- GRANT FORM --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-shield-plus text-success"></i>
                <span>Grant Access to Admins / Vendors / Users</span>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('superadmin.access.grant') }}" id="grantForm">
                    @csrf

                    {{-- Step 1: Choose target role --}}
                    <div class="mb-4">
                        <label class="form-label fw-600">Step 1 — Select Target Role</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-warning role-tab active" data-target="admins">
                                <i class="bi bi-person-badge me-1"></i>Admins
                            </button>
                            <button type="button" class="btn btn-outline-info role-tab" data-target="vendors">
                                <i class="bi bi-shop me-1"></i>Vendors
                            </button>
                            <button type="button" class="btn btn-outline-success role-tab" data-target="users">
                                <i class="bi bi-person me-1"></i>Users
                            </button>
                        </div>
                    </div>

                    {{-- Step 2: Select users --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-600 mb-0">Step 2 — Select Users (checkboxes)</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light" id="selectAll">Select All</button>
                                <button type="button" class="btn btn-sm btn-light" id="selectNone">Clear</button>
                            </div>
                        </div>

                        {{-- Admins --}}
                        <div id="panel-admins" class="user-panel">
                            @if($admins->isEmpty())
                                <p class="text-muted small">No admins found.</p>
                            @else
                            <div class="row g-2">
                                @foreach($admins as $admin)
                                <div class="col-md-6">
                                    <label class="access-user-card" id="card-{{ $admin->id }}">
                                        <div class="access-check">
                                            <input type="checkbox" name="grantee_ids[]" value="{{ $admin->id }}"
                                                   class="grant-cb" onchange="syncCard(this)">
                                            <div class="access-check-box"></div>
                                        </div>
                                        <div class="user-avatar-sm" style="background:#ffedd5;color:#ea580c">
                                            {{ strtoupper(substr($admin->name,0,1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="fw-600 small">{{ $admin->name }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $admin->email }}</div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Vendors --}}
                        <div id="panel-vendors" class="user-panel d-none">
                            @if($vendors->isEmpty())
                                <p class="text-muted small">No vendors found.</p>
                            @else
                            <div class="row g-2">
                                @foreach($vendors as $vendor)
                                <div class="col-md-6">
                                    <label class="access-user-card" id="card-{{ $vendor->id }}">
                                        <div class="access-check">
                                            <input type="checkbox" name="grantee_ids[]" value="{{ $vendor->id }}"
                                                   class="grant-cb" onchange="syncCard(this)">
                                            <div class="access-check-box"></div>
                                        </div>
                                        <div class="user-avatar-sm" style="background:#cffafe;color:#0891b2">
                                            {{ strtoupper(substr($vendor->name,0,1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-600 small">{{ $vendor->name }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $vendor->email }}</div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Users --}}
                        <div id="panel-users" class="user-panel d-none">
                            @if($users->isEmpty())
                                <p class="text-muted small">No users found.</p>
                            @else
                            <div class="row g-2">
                                @foreach($users as $user)
                                <div class="col-md-6">
                                    <label class="access-user-card" id="card-{{ $user->id }}">
                                        <div class="access-check">
                                            <input type="checkbox" name="grantee_ids[]" value="{{ $user->id }}"
                                                   class="grant-cb" onchange="syncCard(this)">
                                            <div class="access-check-box"></div>
                                        </div>
                                        <div class="user-avatar-sm" style="background:#dcfce7;color:#16a34a">
                                            {{ strtoupper(substr($user->name,0,1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-600 small">{{ $user->name }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $user->email }}</div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Step 3: Access level --}}
                    <div class="mb-4">
                        <label class="form-label fw-600">Step 3 — Access Level</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input type="radio" name="access_level" value="read" id="lvl_read"
                                       class="form-check-input" checked>
                                <label for="lvl_read" class="form-check-label">
                                    <i class="bi bi-eye me-1 text-info"></i>Read Only
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="access_level" value="write" id="lvl_write"
                                       class="form-check-input">
                                <label for="lvl_write" class="form-check-label">
                                    <i class="bi bi-pencil me-1 text-warning"></i>Read & Write
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="access_level" value="full" id="lvl_full"
                                       class="form-check-input">
                                <label for="lvl_full" class="form-check-label">
                                    <i class="bi bi-shield-fill me-1 text-danger"></i>Full Access
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Expiry (optional) --}}
                    <div class="mb-4">
                        <label class="form-label fw-600">Expiry Date <span class="text-muted">(optional)</span></label>
                        <input type="date" name="expires_at" class="form-control" style="max-width:250px">
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-shield-check me-2"></i>Grant Access
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- CURRENT GRANTS --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-list-check me-2 text-primary"></i>My Active Grants</span>
                @if($currentGrants->count() > 0)
                <form method="POST" action="{{ route('superadmin.access.revoke-multiple') }}" id="revokeAllForm">
                    @csrf
                    @foreach($currentGrants as $g)
                        <input type="hidden" name="grant_ids[]" value="{{ $g->id }}">
                    @endforeach
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Revoke ALL grants?')">
                        Revoke All
                    </button>
                </form>
                @endif
            </div>

            @if($currentGrants->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-shield-x display-5 d-block mb-2 opacity-25"></i>
                    No active grants yet.<br>
                    <small>Use the form to grant access.</small>
                </div>
            @else
                {{-- Revoke multiple form --}}
                <form method="POST" action="{{ route('superadmin.access.revoke-multiple') }}" id="revokeMultipleForm">
                    @csrf
                    <div class="px-3 pt-3 pb-1 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                            <label class="form-check-label small fw-600" for="checkAll">Select All</label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-danger" id="revokeSelectedBtn" style="display:none"
                                onclick="return confirm('Revoke selected?')">
                            <i class="bi bi-trash me-1"></i>Revoke Selected
                        </button>
                    </div>

                    @foreach($currentGrants as $grant)
                    <div class="grant-item">
                        <div class="form-check me-2">
                            <input type="checkbox" name="grant_ids[]" value="{{ $grant->id }}"
                                   class="form-check-input revoke-cb">
                        </div>
                        <div class="flex-1">
                            <div class="fw-600 small">{{ $grant->grantee->name }}</div>
                            <div class="d-flex gap-1 mt-1 flex-wrap">
                                <span class="pill-badge badge-{{ $grant->grantee->role }}" style="font-size:10px">
                                    {{ $grant->grantee->getRoleLabel() }}
                                </span>
                                <span class="pill-badge badge-processing" style="font-size:10px">
                                    {{ ucfirst($grant->access_level) }}
                                </span>
                                @if($grant->expires_at)
                                    <span class="pill-badge badge-warning" style="font-size:10px">
                                        Exp: {{ $grant->expires_at->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('superadmin.access.revoke', $grant) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Revoke access?')" title="Revoke">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Role tab switching
document.querySelectorAll('.role-tab').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.role-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.user-panel').forEach(p => p.classList.add('d-none'));
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.target).classList.remove('d-none');
        // Uncheck all hidden panels
        document.querySelectorAll('.grant-cb').forEach(cb => {
            cb.checked = false;
            const card = cb.closest('.access-user-card');
            if (card) card.classList.remove('selected');
        });
    });
});

// Sync card selected state with checkbox
function syncCard(cb) {
    const card = cb.closest('.access-user-card');
    if (card) card.classList.toggle('selected', cb.checked);
}

// Select all / none
document.getElementById('selectAll')?.addEventListener('click', () => {
    const visible = document.querySelector('.user-panel:not(.d-none)');
    visible.querySelectorAll('.grant-cb').forEach(cb => {
        cb.checked = true;
        syncCard(cb);
    });
});

document.getElementById('selectNone')?.addEventListener('click', () => {
    document.querySelectorAll('.grant-cb').forEach(cb => {
        cb.checked = false;
        syncCard(cb);
    });
});

// Revoke multiple checkboxes
const checkAll = document.getElementById('checkAll');
if (checkAll) {
    checkAll.addEventListener('change', function () {
        document.querySelectorAll('.revoke-cb').forEach(cb => cb.checked = this.checked);
        updateRevokeBtn();
    });
}

document.querySelectorAll('.revoke-cb').forEach(cb => {
    cb.addEventListener('change', updateRevokeBtn);
});

function updateRevokeBtn() {
    const anyChecked = [...document.querySelectorAll('.revoke-cb')].some(cb => cb.checked);
    const btn = document.getElementById('revokeSelectedBtn');
    if (btn) btn.style.display = anyChecked ? 'inline-block' : 'none';
}
</script>
@endpush
@endsection
