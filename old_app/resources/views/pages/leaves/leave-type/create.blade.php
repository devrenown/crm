@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    <x-breadcrumb class="mb-3">
        <x-slot name="title">Create Leave Type</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('leave-type.index') }}">Leave Types</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ul>
    </x-breadcrumb>

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10 col-md-12">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form action="{{ route('leave-type.store') }}" method="POST">
                        @csrf

                        {{-- ================= BASIC INFO ================= --}}
                        <h6 class="fw-bold mb-3">Basic Information</h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label">Leave Type Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="form-control" required>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label">Applicable Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="0">All</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>

                        {{-- ================= LIMITS ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">Limits & Rules</h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label">Max Days / Year</label>
                                <input type="number" name="max_days_per_year" class="form-control">
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Min Days Notice</label>
                                <input type="number" name="min_days_notice" class="form-control">
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Max Days / Application</label>
                                <input type="number" name="max_days_per_application" class="form-control">
                            </div>
                        </div>

                        {{-- ================= ACCRUAL ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">Accrual & Carry Forward</h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4 col-12">
                                <label class="form-label">Monthly Accrual</label>
                                <select name="monthly_accrual" class="form-select">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Accrual Rate / Month</label>
                                <input type="number" step="0.01" name="accrual_rate" class="form-control">
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">Max Carry Forward</label>
                                <input type="number" name="max_carry_forward" class="form-control">
                            </div>
                        </div>

                        {{-- ================= FLAGS ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">Options</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input type="checkbox" name="carry_forward" value="1" class="form-check-input">
                                    <label class="form-check-label">Carry Forward</label>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input type="checkbox" name="is_paid" value="1" class="form-check-input">
                                    <label class="form-check-label">Paid Leave</label>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input type="checkbox" name="requires_document" value="1" class="form-check-input">
                                    <label class="form-check-label">Requires Document</label>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input type="checkbox" name="is_encashable" value="1" class="form-check-input">
                                    <label class="form-check-label">Encashable</label>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- ================= APPROVAL SETTINGS ================= --}}
                        <h6 class="fw-bold mb-3">Approval Settings</h6>

                        <div class="alert alert-info small">
                            <strong>Level 1 Approval:</strong>
                            Reporting Manager / HR / Admin
                            <br>
                            <small>(Mandatory)</small>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="requires_l2" name="requires_l2_approval" value="1"
                                   class="form-check-input">
                            <label class="form-check-label">
                                Require Level 2 Approval
                            </label>
                        </div>

                        <div class="mb-4 d-none" id="l2_roles_box">
                            <label class="form-label">Level 2 Approver Roles</label>
                            <select name="l2_roles[]" class="form-select" multiple size="6">
                                @foreach($roles as $role)
                                    @if(!in_array($role->name, ['Employee', 'Super Admin']))
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted">
                                HR / Manager / Admin bypass team checks
                            </small>
                        </div>

                        {{-- ================= ACTIONS ================= --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-primary px-4">Save</button>
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
