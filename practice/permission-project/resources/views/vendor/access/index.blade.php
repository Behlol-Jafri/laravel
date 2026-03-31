@extends('layouts.app')
@section('title', 'Manage Access')
@section('page-title', 'Manage Access')

@section('content')
<div class="page-header">
    <div>
        <h1>Grant & Manage Access</h1>
        <p>Control which users can view your products and place orders.</p>
    </div>
</div>

<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle me-2"></i>
    As a Vendor, you can grant <strong>Users</strong> access to browse and order your products.
    Only users you grant access to will see your store.
</div>

<div class="row g-4">
    {{-- Grant Form --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-shield-plus me-2 text-success"></i>Grant Access to Users</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('vendor.access.grant') }}">
                    @csrf

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-600 mb-0">Select Users</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light" id="selectAll">All</button>
                                <button type="button" class="btn btn-sm btn-light" id="selectNone">None</button>
                            </div>
                        </div>

                        @if($allUsers->isEmpty())
                            <div class="text-center text-muted py-4 bg-light rounded">
                                <i class="bi bi-person-x display-5 d-block mb-2 opacity-50"></i>
                                No users registered yet.
                            </div>
                        @else
                            <div class="row g-2">
                                @foreach($allUsers as $u)
                                <div class="col-md-6">
                                    <label class="access-user-card">
                                        <div class="access-check">
                                            <input type="checkbox" name="grantee_ids[]" value="{{ $u->id }}"
                                                   class="grant-cb" onchange="syncCard(this)">
                                            <div class="access-check-box"></div>
                                        </div>
                                        <div class="user-avatar-sm" style="background:#dcfce7;color:#16a34a">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-600 small">{{ $u->name }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $u->email }}</div>
                                        </div>
                                        {{-- Show if already granted --}}
                                        @if($currentGrants->where('grantee_id', $u->id)->isNotEmpty())
                                            <span class="ms-auto badge bg-success" style="font-size:9px">Granted</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Access Level</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check">
                                <input type="radio" name="access_level" value="read" id="lvl_read" class="form-check-input" checked>
                                <label for="lvl_read" class="form-check-label">
                                    <i class="bi bi-eye me-1 text-info"></i>Read Only
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="access_level" value="write" id="lvl_write" class="form-check-input">
                                <label for="lvl_write" class="form-check-label">
                                    <i class="bi bi-pencil me-1 text-warning"></i>Read & Write
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="access_level" value="full" id="lvl_full" class="form-check-input">
                                <label for="lvl_full" class="form-check-label">
                                    <i class="bi bi-shield-fill me-1 text-danger"></i>Full
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Expiry Date <span class="text-muted">(optional)</span></label>
                        <input type="date" name="expires_at" class="form-control" style="max-width:220px">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-shield-check me-2"></i>Grant Access
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Active Grants --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-list-check me-2 text-primary"></i>Active Grants</span>
                @if($currentGrants->count() > 0)
                    <span class="badge bg-success">{{ $currentGrants->count() }} active</span>
                @endif
            </div>

            @if($currentGrants->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-shield-x display-5 d-block mb-2 opacity-25"></i>
                    No active grants.<br>
                    <small>Grant users access to see your products.</small>
                </div>
            @else
                <form method="POST" action="{{ route('vendor.access.revoke-multiple') }}" id="revokeForm">
                    @csrf
                    <div class="px-3 pt-3 pb-1 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                            <label class="form-check-label small fw-600" for="checkAll">Select All</label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-danger" id="revokeSelectedBtn"
                                style="display:none" onclick="return confirm('Revoke selected grants?')">
                            <i class="bi bi-trash me-1"></i>Revoke
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
                            <div class="text-muted" style="font-size:11px">{{ $grant->grantee->email }}</div>
                            <div class="d-flex gap-1 mt-1">
                                <span class="pill-badge badge-processing" style="font-size:10px">
                                    {{ ucfirst($grant->access_level) }}
                                </span>
                                @if($grant->expires_at)
                                    <span class="pill-badge badge-warning" style="font-size:10px">
                                        Exp: {{ $grant->expires_at->format('d M') }}
                                    </span>
                                @else
                                    <span class="pill-badge badge-completed" style="font-size:10px">No Expiry</span>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('vendor.access.revoke', $grant) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Revoke access for {{ $grant->grantee->name }}?')"
                                    title="Revoke">
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
function syncCard(cb) {
    cb.closest('.access-user-card')?.classList.toggle('selected', cb.checked);
}
document.getElementById('selectAll')?.addEventListener('click', () => {
    document.querySelectorAll('.grant-cb').forEach(cb => { cb.checked = true; syncCard(cb); });
});
document.getElementById('selectNone')?.addEventListener('click', () => {
    document.querySelectorAll('.grant-cb').forEach(cb => { cb.checked = false; syncCard(cb); });
});
document.getElementById('checkAll')?.addEventListener('change', function () {
    document.querySelectorAll('.revoke-cb').forEach(cb => cb.checked = this.checked);
    updateBtn();
});
document.querySelectorAll('.revoke-cb').forEach(cb => cb.addEventListener('change', updateBtn));
function updateBtn() {
    const any = [...document.querySelectorAll('.revoke-cb')].some(cb => cb.checked);
    const btn = document.getElementById('revokeSelectedBtn');
    if (btn) btn.style.display = any ? '' : 'none';
}
</script>
@endpush
@endsection
