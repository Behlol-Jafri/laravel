@extends('layouts.app')
@section('title', 'Manage Access')
@section('page-title', 'Manage Access')

@section('content')
<div class="page-header">
    <div>
        <h1>Grant & Manage Access</h1>
        <p>Grant vendors and users access to your data.</p>
    </div>
</div>

<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle me-2"></i>
    As an Admin, you can grant <strong>Vendors</strong> and <strong>Users</strong> access. Vendors you see here were granted to you by Super Admin.
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-shield-plus me-2 text-success"></i>Grant Access</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.access.grant') }}">
                    @csrf

                    <div class="mb-4">
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-outline-info role-tab active" data-target="vendors">
                                <i class="bi bi-shop me-1"></i>Vendors
                            </button>
                            <button type="button" class="btn btn-outline-success role-tab" data-target="users">
                                <i class="bi bi-person me-1"></i>Users
                            </button>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <label class="form-label fw-600 mb-0">Select Users</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light" id="selectAll">All</button>
                                <button type="button" class="btn btn-sm btn-light" id="selectNone">None</button>
                            </div>
                        </div>

                        <div id="panel-vendors" class="user-panel">
                            @forelse($vendors as $v)
                            <div class="col-12 mb-2">
                                <label class="access-user-card">
                                    <div class="access-check">
                                        <input type="checkbox" name="grantee_ids[]" value="{{ $v->id }}" class="grant-cb" onchange="syncCard(this)">
                                        <div class="access-check-box"></div>
                                    </div>
                                    <div class="user-avatar-sm" style="background:#cffafe;color:#0891b2">{{ strtoupper(substr($v->name,0,1)) }}</div>
                                    <div><div class="fw-600 small">{{ $v->name }}</div><div class="text-muted" style="font-size:11px">{{ $v->email }}</div></div>
                                </label>
                            </div>
                            @empty
                                <p class="text-muted small">No vendors assigned to you by Super Admin yet.</p>
                            @endforelse
                        </div>

                        <div id="panel-users" class="user-panel d-none">
                            @forelse($allUsers as $u)
                            <div class="col-12 mb-2">
                                <label class="access-user-card">
                                    <div class="access-check">
                                        <input type="checkbox" name="grantee_ids[]" value="{{ $u->id }}" class="grant-cb" onchange="syncCard(this)">
                                        <div class="access-check-box"></div>
                                    </div>
                                    <div class="user-avatar-sm" style="background:#dcfce7;color:#16a34a">{{ strtoupper(substr($u->name,0,1)) }}</div>
                                    <div><div class="fw-600 small">{{ $u->name }}</div><div class="text-muted" style="font-size:11px">{{ $u->email }}</div></div>
                                </label>
                            </div>
                            @empty
                                <p class="text-muted small">No users found.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Access Level</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input type="radio" name="access_level" value="read" id="lvl_read" class="form-check-input" checked>
                                <label for="lvl_read" class="form-check-label"><i class="bi bi-eye me-1 text-info"></i>Read</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="access_level" value="write" id="lvl_write" class="form-check-input">
                                <label for="lvl_write" class="form-check-label"><i class="bi bi-pencil me-1 text-warning"></i>Write</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="access_level" value="full" id="lvl_full" class="form-check-input">
                                <label for="lvl_full" class="form-check-label"><i class="bi bi-shield-fill me-1 text-danger"></i>Full</label>
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

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-list-check me-2 text-primary"></i>Active Grants</div>
            @if($currentGrants->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-shield-x display-5 d-block mb-2 opacity-25"></i>No grants yet.
                </div>
            @else
                <form method="POST" action="{{ route('admin.access.revoke-multiple') }}" id="revokeForm">
                    @csrf
                    <div class="px-3 pt-3 pb-1 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkAll">
                            <label class="form-check-label small fw-600" for="checkAll">Select All</label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-danger" id="revokeBtn" style="display:none"
                                onclick="return confirm('Revoke selected?')">
                            <i class="bi bi-trash me-1"></i>Revoke
                        </button>
                    </div>
                    @foreach($currentGrants as $g)
                    <div class="grant-item">
                        <input type="checkbox" name="grant_ids[]" value="{{ $g->id }}" class="form-check-input revoke-cb me-2">
                        <div class="flex-1">
                            <div class="fw-600 small">{{ $g->grantee->name }}</div>
                            <span class="pill-badge badge-{{ $g->grantee->role }}" style="font-size:10px">{{ $g->grantee->getRoleLabel() }}</span>
                            <span class="pill-badge badge-processing ms-1" style="font-size:10px">{{ ucfirst($g->access_level) }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.access.revoke', $g) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Revoke?')">
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
document.querySelectorAll('.role-tab').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.role-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.user-panel').forEach(p => p.classList.add('d-none'));
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.target).classList.remove('d-none');
        document.querySelectorAll('.grant-cb').forEach(cb => { cb.checked = false; syncCard(cb); });
    });
});
function syncCard(cb) { cb.closest('.access-user-card')?.classList.toggle('selected', cb.checked); }
document.getElementById('selectAll')?.addEventListener('click', () => {
    document.querySelector('.user-panel:not(.d-none)').querySelectorAll('.grant-cb').forEach(cb => { cb.checked = true; syncCard(cb); });
});
document.getElementById('selectNone')?.addEventListener('click', () => {
    document.querySelectorAll('.grant-cb').forEach(cb => { cb.checked = false; syncCard(cb); });
});
document.getElementById('checkAll')?.addEventListener('change', function() {
    document.querySelectorAll('.revoke-cb').forEach(cb => cb.checked = this.checked);
    updateBtn();
});
document.querySelectorAll('.revoke-cb').forEach(cb => cb.addEventListener('change', updateBtn));
function updateBtn() {
    const any = [...document.querySelectorAll('.revoke-cb')].some(cb => cb.checked);
    const btn = document.getElementById('revokeBtn');
    if (btn) btn.style.display = any ? '' : 'none';
}
</script>
@endpush
@endsection
