@extends('layouts.app')

@push('page-styles')
<style>
    .role-perm-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .perm-intro {
        background: #f0f4ff;
        border: 1px solid #d0d8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        font-size: 14px;
        color: #475569;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .perm-intro i {
        font-size: 20px;
        color: #4f6df5;
        margin-top: 2px;
    }

    .role-perm-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 16px;
        border-left: 4px solid #e8ecf1;
        transition: all .2s;
    }

    .role-perm-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    .role-perm-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .role-perm-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .role-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
        background: #64748b;
    }

    .role-name-text {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
    }

    .role-id-badge {
        font-size: 11px;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 8px;
        border-radius: 20px;
    }

    .visibility-toggle {
        display: flex;
        gap: 0;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .vis-option {
        padding: 8px 16px;
        font-size: 13px;
        cursor: pointer;
        background: #fff;
        color: #64748b;
        border: none;
        transition: all .2s;
    }

    .vis-option:hover {
        background: #f8fafc;
    }

    .vis-option.active {
        background: var(--primary, #ff6b35);
        color: #fff;
    }

    .dept-select-area {
        margin-top: 16px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 8px;
        display: none;
    }

    .dept-select-area.visible {
        display: block;
    }

    .dept-select-area h6 {
        font-size: 14px;
        color: #475569;
        margin-bottom: 12px;
    }

    .dept-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 8px;
    }

    .dept-checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #fff;
        border: 1px solid #e8ecf1;
        border-radius: 8px;
        cursor: pointer;
        transition: all .2s;
    }

    .dept-checkbox-item:hover {
        border-color: #4f6df5;
    }

    .dept-checkbox-item.selected {
        border-color: #4f6df5;
        background: #f0f4ff;
    }

    .dept-checkbox-item input {
        accent-color: #4f6df5;
    }

    .dept-checkbox-item label {
        font-size: 13px;
        color: #334155;
        cursor: pointer;
        margin: 0;
    }

    .save-btn {
        margin-top: 12px;
        padding: 8px 24px;
        background: var(--primary, #ff6b35);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all .2s;
    }

    .save-btn:hover {
        opacity: 0.9;
    }

    .save-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .toast-msg {
        position: fixed;
        bottom: 24px;
        right: 24px;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 14px;
        color: #fff;
        z-index: 9999;
        display: none;
        animation: slideIn .3s;
    }

    .toast-msg.success { background: #2ecc71; }
    .toast-msg.error { background: #e74c3c; }

    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endpush

@section('page-content')
<div class="content container-fluid">

    <!-- Page Header -->
    <x-breadcrumb class="col">
        <x-slot name="title">{{ __('Role Department Permissions') }}</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('hierarchy.org-tree') }}">{{ __('Hierarchy') }}</a>
            </li>
            <li class="breadcrumb-item active">
                {{ __('Role Permissions') }}
            </li>
        </ul>
        <x-slot name="right">
            <div class="col-auto ms-auto">
                <a href="{{ route('hierarchy.org-tree') }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-sitemap me-1"></i> Org Tree
                </a>
            </div>
        </x-slot>
    </x-breadcrumb>

    <div class="role-perm-container">

        <!-- Info box -->
        <div class="perm-intro">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                <strong>How it works:</strong> Control which departments each role can see in the Organization Hierarchy.
                Set a role to see <strong>"All Departments"</strong> or only <strong>"Assigned Departments"</strong>.
            </div>
        </div>

        <!-- Role cards -->
        @foreach($roles as $role)
            @php
                $existingPerm = $permissions[$role->id] ?? null;
                $visType = $existingPerm?->visibility_type ?? 'all_departments';
                $assignedDepts = $existingPerm
                    ? $existingPerm->departmentAssignments->pluck('department_id')->toArray()
                    : [];
            @endphp

            <div class="role-perm-card" id="role-card-{{ $role->id }}">
                <div class="role-perm-header">
                    <div class="role-perm-title">
                        <div class="role-icon" style="background:#64748b;">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <div class="role-name-text">{{ $role->name }}</div>
                            <!-- <span class="role-id-badge">Role ID: {{ $role->id }}</span> -->
                        </div>
                    </div>

                    <div class="visibility-toggle">
                        <button class="vis-option {{ $visType === 'all_departments' ? 'active' : '' }}"
                                onclick="setVisibility({{ $role->id }}, 'all_departments', this)">
                            All Departments
                        </button>
                        <button class="vis-option {{ $visType === 'assigned_departments' ? 'active' : '' }}"
                                onclick="setVisibility({{ $role->id }}, 'assigned_departments', this)">
                            Assigned Only
                        </button>
                    </div>
                </div>

                <div class="dept-select-area {{ $visType === 'assigned_departments' ? 'visible' : '' }}"
                     id="dept-area-{{ $role->id }}">
                    <h6>Select departments this role can access:</h6>
                    <div class="dept-checkbox-grid">
                        @foreach($departments as $dept)
    <div class="dept-checkbox-item {{ in_array($dept->id, $assignedDepts) ? 'selected' : '' }}"
         onclick="toggleDept(this, {{ $role->id }}, {{ $dept->id }})">
        <input type="checkbox"
               id="dept-{{ $role->id }}-{{ $dept->id }}"
               value="{{ $dept->id }}"
               {{ in_array($dept->id, $assignedDepts) ? 'checked' : '' }}
               onclick="event.preventDefault()">
        <label for="dept-{{ $role->id }}-{{ $dept->id }}"
               onclick="event.preventDefault()">{{ $dept->name }}</label>
    </div>
@endforeach
                    </div>
                    <button class="save-btn" onclick="savePermission({{ $role->id }})">
                        <i class="fa-solid fa-check me-1"></i> Save Permission
                    </button>
                </div>
            </div>
        @endforeach

        @if($roles->isEmpty())
            <div style="text-align:center;padding:60px;color:#94a3b8;">
                <i class="fa-solid fa-user-shield" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                <p>No roles found for this tenant.</p>
            </div>
        @endif

    </div>

</div>

<!-- Toast -->
<div class="toast-msg" id="toastMsg"></div>
@endsection

@push('page-scripts')
<script>
// Store current visibility per role
const roleVisType = {};
const roleAssignedDepts = {};

document.querySelectorAll('.role-perm-card').forEach(card => {
    const roleId = parseInt(card.id.replace('role-card-', ''));
    const activeBtn = card.querySelector('.vis-option.active');
    roleVisType[roleId] = activeBtn ? (activeBtn.textContent.trim().includes('All') ? 'all_departments' : 'assigned_departments') : 'all_departments';

    // Collect initially checked departments
    const checked = card.querySelectorAll('.dept-checkbox-item.selected input');
    roleAssignedDepts[roleId] = Array.from(checked).map(cb => parseInt(cb.value));
});

function setVisibility(roleId, type, btn) {
    roleVisType[roleId] = type;

    // Update toggle UI
    const card = document.getElementById('role-card-' + roleId);
    card.querySelectorAll('.vis-option').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Show/hide dept area
    const deptArea = document.getElementById('dept-area-' + roleId);
    if (type === 'assigned_departments') {
        deptArea.classList.add('visible');
    } else {
        deptArea.classList.remove('visible');
    }
}

function toggleDept(el, roleId, deptId) {
    const checkbox = el.querySelector('input');
    checkbox.checked = !checkbox.checked;
    el.classList.toggle('selected', checkbox.checked);

    if (!roleAssignedDepts[roleId]) roleAssignedDepts[roleId] = [];

    if (checkbox.checked) {
        if (!roleAssignedDepts[roleId].includes(deptId)) {
            roleAssignedDepts[roleId].push(deptId);
        }
    } else {
        roleAssignedDepts[roleId] = roleAssignedDepts[roleId].filter(id => id !== deptId);
    }
}

function savePermission(roleId) {
    const visType = roleVisType[roleId];
    const deptIds = roleAssignedDepts[roleId] || [];

    const btn = event.target.closest('.save-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...';

    fetch('{{ route("hierarchy.update-role-dept-permission") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            role_id: roleId,
            visibility_type: visType,
            department_ids: deptIds,
        })
    })
    .then(r => r.json())
    .then(data => {
        showToast(data.message || 'Saved!', data.success ? 'success' : 'error');
    })
    .catch(() => showToast('Network error', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Save Permission';
    });
}

function showToast(msg, type) {
    const toast = document.getElementById('toastMsg');
    toast.textContent = msg;
    toast.className = 'toast-msg ' + type;
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 3000);
}
</script>
@endpush
