@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    <x-breadcrumb class="mb-3">
        <x-slot name="title">Create Leave Type</x-slot>

        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('leave-type.index') }}">Leave Types</a>
            </li>

            <li class="breadcrumb-item active">
                Create
            </li>
        </ul>
        
    </x-breadcrumb>

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10 col-md-12">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form action="{{ route('leave-type.store') }}" method="POST">
                        @csrf

                        {{-- ================= BASIC ================= --}}
                        <h6 class="fw-bold mb-3">
                            Basic Information
                        </h6>

                        <div class="row g-3 mb-3">

                            <div class="col-md-4 col-12">
                                <label class="form-label">
                                    Leave Type Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control"
                                    required
                                >
                            </div>

                           <div class="col-md-4 col-12">
                                <label class="form-label">
                                    Leave Type
                                </label>

                                <select
                                    name="leave_code"
                                    class="form-select"
                                >
                                    <option value="">
                                        -- Select Leave --
                                    </option>

                                    <option value="CASUAL" {{ old('leave_code') == 'CASUAL' ? 'selected' : '' }}>
                                        Casual Leave
                                    </option>

                                    <option value="SICK" {{ old('leave_code') == 'SICK' ? 'selected' : '' }}>
                                        Sick Leave
                                    </option>

                                    <option value="EARNED" {{ old('leave_code') == 'EARNED' ? 'selected' : '' }}>
                                        Earned Leave
                                    </option>

                                    <option value="ANNUAL" {{ old('leave_code') == 'ANNUAL' ? 'selected' : '' }}>
                                        Annual Leave
                                    </option>

                                    <option value="VACATION" {{ old('leave_code') == 'VACATION' ? 'selected' : '' }}>
                                        Vacation Leave
                                    </option>

                                    <option value="BIRTHDAY" {{ old('leave_code') == 'BIRTHDAY' ? 'selected' : '' }}>
                                        Birthday Leave
                                    </option>

                                    <option value="MATERNITY" {{ old('leave_code') == 'MATERNITY' ? 'selected' : '' }}>
                                        Maternity Leave
                                    </option>

                                    <option value="PATERNITY" {{ old('leave_code') == 'PATERNITY' ? 'selected' : '' }}>
                                        Paternity Leave
                                    </option>

                                    <option value="ADOPTION" {{ old('leave_code') == 'ADOPTION' ? 'selected' : '' }}>
                                        Adoption Leave
                                    </option>

                                    <option value="BEREAVEMENT" {{ old('leave_code') == 'BEREAVEMENT' ? 'selected' : '' }}>
                                        Bereavement Leave
                                    </option>

                                    <option value="COMP_OFF" {{ old('leave_code') == 'COMP_OFF' ? 'selected' : '' }}>
                                        Compensatory Off
                                    </option>

                                    <option value="MARRIAGE" {{ old('leave_code') == 'MARRIAGE' ? 'selected' : '' }}>
                                        Marriage Leave
                                    </option>

                                    <option value="STUDY" {{ old('leave_code') == 'STUDY' ? 'selected' : '' }}>
                                        Study Leave
                                    </option>

                                    <option value="SABBATICAL" {{ old('leave_code') == 'SABBATICAL' ? 'selected' : '' }}>
                                        Sabbatical Leave
                                    </option>

                                    <option value="WFH" {{ old('leave_code') == 'WFH' ? 'selected' : '' }}>
                                        Work From Home
                                    </option>

                                    <option value="JURY_DUTY" {{ old('leave_code') == 'JURY_DUTY' ? 'selected' : '' }}>
                                        Jury Duty Leave
                                    </option>

                                    <option value="MILITARY" {{ old('leave_code') == 'MILITARY' ? 'selected' : '' }}>
                                        Military Leave
                                    </option>

                                    <option value="RELIGIOUS" {{ old('leave_code') == 'RELIGIOUS' ? 'selected' : '' }}>
                                        Religious Leave
                                    </option>

                                    <option value="UNPAID" {{ old('leave_code') == 'UNPAID' ? 'selected' : '' }}>
                                        Unpaid Leave
                                    </option>

                                    <option value="OTHER" {{ old('leave_code') == 'OTHER' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label">
                                    Applicable Gender
                                </label>

                                <select
                                    name="gender"
                                    class="form-select"
                                >
                                    <option value="0">
                                        All
                                    </option>

                                    <option value="1">
                                        Male
                                    </option>

                                    <option value="2">
                                        Female
                                    </option>

                                    <option value="3">
                                        Other
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="2"
                                class="form-control"
                            >{{ old('description') }}</textarea>
                        </div>

                        {{-- ================= LIMITS ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">
                            Limits & Rules
                        </h6>

                        <div class="row g-3 mb-3">

                            <div class="col-md-4">
                                <label class="form-label">
                                    Max Days / Year
                                </label>

                                <input
                                    type="number"
                                    name="max_days_per_year"
                                    value="{{ old('max_days_per_year') }}"
                                    class="form-control"
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Min Days Notice
                                </label>

                                <input
                                    type="number"
                                    name="min_days_notice"
                                    value="{{ old('min_days_notice') }}"
                                    class="form-control"
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Max Days / Application
                                </label>

                                <input
                                    type="number"
                                    name="max_days_per_application"
                                    value="{{ old('max_days_per_application') }}"
                                    class="form-control"
                                >
                            </div>

                        </div>

                        {{-- ================= ACCRUAL ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">
                            Accrual & Carry Forward
                        </h6>

                        <div class="row g-3 mb-3">

                            <div class="col-md-4">
                                <label class="form-label">
                                    Monthly Accrual
                                </label>

                                <select
                                    name="monthly_accrual"
                                    id="monthly_accrual"
                                    class="form-select"
                                >
                                    <option value="0">
                                        No
                                    </option>

                                    <option value="1">
                                        Yes
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Accrual Rate / Month
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    name="accrual_rate"
                                    value="{{ old('accrual_rate') }}"
                                    class="form-control"
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Max Carry Forward
                                </label>

                                <input
                                    type="number"
                                    name="max_carry_forward"
                                    value="{{ old('max_carry_forward') }}"
                                    class="form-control"
                                >
                            </div>

                        </div>

                        {{-- ================= OPTIONS ================= --}}
                        <h6 class="fw-bold mt-4 mb-3">
                            Options
                        </h6>

                        <div class="row g-3 mb-4">

                            @php
                                $checks = [
                                    ['carry_forward','Carry Forward'],
                                    ['monthly_accrual','Monthly Accrual'],
                                    ['allow_during_probation','Allow During Probation'],
                                    ['is_paid','Paid Leave'],
                                    ['requires_document','Requires Document'],
                                    ['is_encashable','Encashable'],
                                    ['is_active','Active'],
                                ];
                            @endphp

                            @foreach($checks as [$field, $label])

                                <div class="col-md-4 col-6">

                                    <div class="form-check">

                                        <input
                                            type="checkbox"
                                            name="{{ $field }}"
                                            value="1"
                                            class="form-check-input"
                                            {{ old($field) ? 'checked' : '' }}
                                        >

                                        <label class="form-check-label">
                                            {{ $label }}
                                        </label>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                        <hr>

                        {{-- ================= APPROVAL ================= --}}
                        <h6 class="fw-bold mb-3">
                            Approval Settings
                        </h6>

                        <div class="alert alert-info small">
                            <strong>Level 1 Approval:</strong>
                            Reporting Manager / HR / Admin
                        </div>

                        <div class="form-check mb-3">

                            <input
                                type="checkbox"
                                id="requires_l2"
                                name="requires_l2_approval"
                                value="1"
                                class="form-check-input"
                            >

                            <label class="form-check-label">
                                Require Level 2 Approval
                            </label>

                        </div>

                        <div
                            class="mb-4 d-none"
                            id="l2_roles_box"
                        >

                            <label class="form-label">
                                Level 2 Approver Roles
                            </label>

                            <select
                                name="l2_roles[]"
                                class="form-select"
                                multiple
                                size="6"
                            >

                                @foreach($roles as $role)

                                    @if(
                                        !in_array(
                                            strtolower($role->name),
                                            ['employee', 'super admin']
                                        )
                                    )

                                        <option value="{{ $role->id }}">
                                            {{ $role->name }}
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                        </div>

                        {{-- ================= ACTIONS ================= --}}
                        <div class="d-flex justify-content-end gap-2">

                            <button class="btn btn-primary px-4">
                                Save
                            </button>

                            <a
                                href="{{ route('leave-type.index') }}"
                                class="btn btn-secondary px-4"
                            >
                                Back
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>

    document
        .getElementById('requires_l2')
        .addEventListener('change', function () {

            document
                .getElementById('l2_roles_box')
                .classList.toggle('d-none', !this.checked);

        });

</script>
@endsection