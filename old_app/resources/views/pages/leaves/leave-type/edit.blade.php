@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    <x-breadcrumb class="mb-3">
        <x-slot name="title">Edit Leave Type</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('leave-type.index') }}">Leave Types</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ul>
    </x-breadcrumb>

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10 col-md-12">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form action="{{ route('leave-type.update', $leaveType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ================= BASIC INFO ================= --}}
                        <h6 class="fw-bold mb-3">Basic Information</h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label">Leave Type Name</label>
                                <input type="text" name="name"
                                       value="{{ old('name', $leaveType->name) }}"
                                       class="form-control" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label">Applicable Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="0" {{ $leaveType->gender == 0 ? 'selected' : '' }}>All</option>
                                    <option value="1" {{ $leaveType->gender == 1 ? 'selected' : '' }}>Male</option>
                                    <option value="2" {{ $leaveType->gender == 2 ? 'selected' : '' }}>Female</option>
                                    <option value="3" {{ $leaveType->gender == 3 ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="2"
                                      class="form-control">{{ old('description', $leaveType->description) }}</textarea>
                        </div>

                        {{-- ================= LIMITS ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">Limits & Rules</h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label">Max Days / Year</label>
                                <input type="number" name="max_days_per_year"
                                       value="{{ old('max_days_per_year', $leaveType->max_days_per_year) }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Min Days Notice</label>
                                <input type="number" name="min_days_notice"
                                       value="{{ old('min_days_notice', $leaveType->min_days_notice) }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Max Days / Application</label>
                                <input type="number" name="max_days_per_application"
                                       value="{{ old('max_days_per_application', $leaveType->max_days_per_application) }}"
                                       class="form-control">
                            </div>
                        </div>

                        {{-- ================= ACCRUAL ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">Accrual & Carry Forward</h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label">Monthly Accrual</label>
                                <select name="monthly_accrual" class="form-select">
                                    <option value="0" {{ $leaveType->monthly_accrual == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $leaveType->monthly_accrual == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Accrual Rate / Month</label>
                                <input type="number" step="0.01" name="accrual_rate"
                                       value="{{ old('accrual_rate', $leaveType->accrual_rate) }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Max Carry Forward</label>
                                <input type="number" name="max_carry_forward"
                                       value="{{ old('max_carry_forward', $leaveType->max_carry_forward) }}"
                                       class="form-control">
                            </div>
                        </div>

                        {{-- ================= OPTIONS ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">Options</h6>

                        <div class="row g-3 mb-4">
                            @php
                                $checks = [
                                    ['carry_forward','Carry Forward'],
                                    ['is_paid','Paid Leave'],
                                    ['requires_document','Requires Document'],
                                    ['is_encashable','Encashable'],
                                    ['is_active','Active'],
                                ];
                            @endphp

                            @foreach($checks as [$field,$label])
                                <div class="col-md-3 col-6">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               name="{{ $field }}"
                                               value="1"
                                               class="form-check-input"
                                               {{ $leaveType->$field ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        {{-- ================= APPROVAL SETTINGS ================= --}}
                        <h6 class="fw-bold mb-3">Approval Settings</h6>

                        <div class="alert alert-info small">
                            <strong>Level 1 Approval (Default)</strong><br>
                            Reporting Manager / HR / Admin
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="requires_l2"
                                   name="requires_l2_approval" value="1"
                                   class="form-check-input"
                                   {{ $leaveType->requires_l2_approval ? 'checked' : '' }}>
                            <label class="form-check-label">
                                Require Level 2 Approval
                            </label>
                        </div>
                        

                        <div class="mb-4 {{ $leaveType->requires_l2_approval ? '' : 'd-none' }}"
                             id="l2_roles_box">
                            <label class="form-label">Level 2 Approver Roles</label>
                            <select name="l2_roles[]" class="form-select" multiple size="6">
                                @foreach($roles as $role)
                                    @if(!in_array(strtolower($role->name), ['employee','super admin']))
                                        <option value="{{ $role->id }}"
                                            {{ in_array((int)$role->id, $selectedRoles ?? [], true) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            <small class="text-muted">
                                HR / Admin bypass team checks
                            </small>
                        </div>

                        {{-- ============ ACTIONS ============ --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-primary px-4">Update</button>
                            <a href="{{ route('leave-type.index') }}" class="btn btn-secondary px-4">Back</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('requires_l2').addEventListener('change', function () {
        document.getElementById('l2_roles_box')
            .classList.toggle('d-none', !this.checked);
    });
</script>
@endsection
