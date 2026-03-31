@extends('layouts.app')

@section('content')
<div class="container py-4">

  {{-- Page Header --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h3 class="fw-bold mb-0">User Permission Manager</h3>
      <p class="text-muted small mb-0">Check a box to grant read access. Uncheck to revoke.</p>
    </div>
    <span class="badge bg-primary fs-6">Admin Dashboard</span>
  </div>

  {{-- Stats Row --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-0 bg-light text-center py-3">
        <div class="fs-2 fw-bold text-primary">{{ $users->count() }}</div>
        <div class="text-muted small">Total Users</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 bg-light text-center py-3">
        <div class="fs-2 fw-bold text-success" id="active-count">0</div>
        <div class="text-muted small">Active Permissions</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 bg-light text-center py-3">
        <div class="fs-2 fw-bold text-warning" id="restricted-count">{{ $users->count() }}</div>
        <div class="text-muted small">Restricted Users</div>
      </div>
    </div>
  </div>

  {{-- Toast Notification --}}
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast align-items-center border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body fw-semibold" id="toast-message"></div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  {{-- Users Accordion --}}
  <div class="accordion" id="usersAccordion">
    @foreach($users as $index => $user)
    <div class="accordion-item border mb-2 rounded shadow-sm">

      {{-- Accordion Header --}}
      <h2 class="accordion-header">
        <button class="accordion-button collapsed rounded" type="button"
          data-bs-toggle="collapse"
          data-bs-target="#collapse{{ $user->id }}">

          <div class="d-flex align-items-center gap-3 w-100">
            {{-- Avatar --}}
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
              style="width:42px;height:42px;background:{{ ['#1D9E75','#378ADD','#D85A30','#D4537E','#7F77DD'][$index % 5] }};flex-shrink:0">
              {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            {{-- Name & Email --}}
            <div class="flex-grow-1">
              <div class="fw-semibold">{{ $user->name }}</div>
              <div class="text-muted small">{{ $user->email }}</div>
            </div>

            {{-- Permission Count Badge --}}
            <span class="badge bg-secondary me-3" id="count-badge-{{ $user->id }}">
              @php
                $grantedCount = collect($permissions[$user->id] ?? [])->filter()->count();
              @endphp
              {{ $grantedCount > 0 ? $grantedCount . ' access' : 'no access' }}
            </span>
          </div>
        </button>
      </h2>

      {{-- Accordion Body --}}
      <div id="collapse{{ $user->id }}" class="accordion-collapse collapse">
        <div class="accordion-body bg-white">
          <p class="text-muted small mb-3">
            <i class="bi bi-shield-check"></i>
            Select which users <strong>{{ $user->name }}</strong> can read data of:
          </p>

          <div class="row g-2">
            @foreach($users as $target)
              @if($user->id !== $target->id)
              @php $hasAccess = $permissions[$user->id][$target->id] ?? false; @endphp

              <div class="col-md-6 col-lg-4">
                <div class="perm-card card border p-3 {{ $hasAccess ? 'border-success bg-success bg-opacity-10' : '' }}"
                  id="card-{{ $user->id }}-{{ $target->id }}">
                  <div class="form-check d-flex align-items-center gap-2 mb-0">
                    <input
                      class="form-check-input perm-checkbox"
                      type="checkbox"
                      style="width:20px;height:20px;cursor:pointer"
                      id="perm-{{ $user->id }}-{{ $target->id }}"
                      data-user="{{ $user->id }}"
                      data-target="{{ $target->id }}"
                      {{ $hasAccess ? 'checked' : '' }}
                    >
                    <label class="form-check-label w-100" for="perm-{{ $user->id }}-{{ $target->id }}"
                      style="cursor:pointer">
                      <div class="fw-semibold small">{{ $target->name }}</div>
                      <div class="text-muted" style="font-size:11px">{{ $target->email }}</div>
                    </label>
                    <span class="badge ms-auto {{ $hasAccess ? 'bg-success' : 'bg-secondary' }}"
                      id="status-badge-{{ $user->id }}-{{ $target->id }}"
                      style="font-size:10px">
                      {{ $hasAccess ? 'Granted' : 'None' }}
                    </span>
                  </div>
                </div>
              </div>

              @endif
            @endforeach
          </div>

        </div>
      </div>

    </div>
    @endforeach
  </div>

</div>

<script>
const CSRF = '{{ csrf_token() }}';
const TOGGLE_URL = '{{ route("admin.permissions.toggle") }}';

// Count active permissions on load
let activeCount = {{ collect($permissions)->flatten()->filter()->count() }};
document.getElementById('active-count').textContent = activeCount;

function showToast(message, type) {
  const toast = document.getElementById('liveToast');
  const msg   = document.getElementById('toast-message');
  toast.className = `toast align-items-center border-0 text-bg-${type}`;
  msg.textContent = message;
  new bootstrap.Toast(toast, { delay: 2500 }).show();
}

function updateBadge(userId) {
  const checked = document.querySelectorAll(`.perm-checkbox[data-user="${userId}"]:checked`).length;
  const badge   = document.getElementById(`count-badge-${userId}`);
  badge.textContent = checked > 0 ? checked + ' access' : 'no access';
  badge.className   = checked > 0 ? 'badge bg-success me-3' : 'badge bg-secondary me-3';
}

document.querySelectorAll('.perm-checkbox').forEach(checkbox => {
  checkbox.addEventListener('change', async function () {
    const userId   = this.dataset.user;
    const targetId = this.dataset.target;
    const canRead  = this.checked ? 1 : 0;
    const card     = document.getElementById(`card-${userId}-${targetId}`);
    const badge    = document.getElementById(`status-badge-${userId}-${targetId}`);

    try {
      const res  = await fetch(TOGGLE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify({
          user_id: userId,
          target_user_id: targetId,
          can_read: canRead
        })
      });

      const data = await res.json();

      if (data.success) {
        // Update card style
        if (canRead) {
          card.classList.add('border-success', 'bg-success', 'bg-opacity-10');
          badge.className   = 'badge ms-auto bg-success';
          badge.textContent = 'Granted';
          activeCount++;
        } else {
          card.classList.remove('border-success', 'bg-success', 'bg-opacity-10');
          badge.className   = 'badge ms-auto bg-secondary';
          badge.textContent = 'None';
          activeCount--;
        }

        document.getElementById('active-count').textContent = activeCount;
        updateBadge(userId);
        showToast(data.message, data.type);
      }

    } catch (err) {
      showToast('Something went wrong!', 'danger');
      this.checked = !this.checked; // revert
    }
  });
});
</script>
@endsection