@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    {{-- ================= PAGE HEADER ================= --}}
    <x-breadcrumb class="col">
        <x-slot name="title">New Leave Request</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('leaves.index') }}">Leave Requests</a>
            </li>
            <li class="breadcrumb-item active">
                New
            </li>
        </ul>
    </x-breadcrumb>

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10 col-md-12">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Apply Leave</h5>
                </div>

                <div class="card-body">

                    {{-- Display validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="leave-create-form"
                          action="{{ route('leaves.store') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        {{-- ================= BASIC INFO ================= --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Leave Type</label>
                            <select name="leave_type_id" class="form-select" required>
                                <option value="">Select leave type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}"
                                            data-requires-document="{{ $type->requires_document ? 1 : 0 }}"
                                            data-max-days="{{ $type->max_days_per_application ?? 0 }}"
                                            {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ================= DATES ================= --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date"
                                       name="start_date"
                                       class="form-control"
                                       value="{{ old('start_date') }}"
                                       required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date"
                                       name="end_date"
                                       class="form-control"
                                       value="{{ old('end_date') }}">
                                <small class="text-muted d-block">
                                    Leave blank for single day
                                </small>
                            </div>
                        </div>

                        {{-- ================= PER-DAY TERMS ================= --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Leave Terms (Per Day)
                            </label>

                            <div id="term_details_container"
                                 class="border rounded p-2 bg-light">
                                <small class="text-muted">
                                    Select leave type for each date
                                </small>
                            </div>
                        </div>

                        {{-- ================= REASON ================= --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason</label>
                            <textarea name="reason"
                                      class="form-control"
                                      rows="5"
                                      required>{{ old('reason') }}</textarea>
                        </div>

                        {{-- ================= DOCUMENT ================= --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Attach Document</label>
                            <input type="file"
                                   name="document"
                                   class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted d-block">
                                Required only if leave type mandates document
                            </small>
                        </div>

                        {{-- ================= ACTIONS ================= --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('leaves.index') }}"
                               class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn btn-primary px-4">
                                Apply Leave
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

{{-- ================= JS ================= --}}
@push('page-scripts')
<script>
(function() {
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    const leaveTypeSelect = document.querySelector('[name="leave_type_id"]');
    const documentInput = document.querySelector('[name="document"]');
    const container = document.getElementById('term_details_container');

    function generateTermFields() {
        container.innerHTML = '';

        const startDate = startDateInput.value;
        const endDate = endDateInput.value || startDate;
        if (!startDate) return;

        if (new Date(endDate) < new Date(startDate)) {
            alert('End date cannot be before start date');
            endDateInput.value = '';
            return;
        }

        const start = new Date(startDate);
        const end = new Date(endDate);
        let index = 0;

        const maxDays = parseInt(leaveTypeSelect.selectedOptions[0]?.dataset.maxDays || 0);
        const totalDays = Math.floor((end - start) / (1000*60*60*24)) + 1;
        if(maxDays && totalDays > maxDays){
            alert(`Maximum ${maxDays} day(s) allowed for this leave type.`);
            endDateInput.value = startDate;
            return;
        }

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dayStr = d.toISOString().split('T')[0];

            const row = document.createElement('div');
            row.className = 'row g-2 align-items-end mb-3';

            row.innerHTML = `
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-0">Date</label>
                    <div class="form-control-plaintext">${dayStr}</div>
                    <input type="hidden" name="term_details[${index}][date]" value="${dayStr}">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Term</label>
                    <select name="term_details[${index}][term]"
                            class="form-select day-term" required>
                        <option value="Fullday">Full Day</option>
                        <option value="Halfday">Half Day</option>
                        <option value="Shortleave">Short Leave</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 halfday-box d-none">
                    <label class="form-label fw-semibold">Half Day Type</label>
                    <select name="term_details[${index}][half_day_type]"
                            class="form-select">
                        <option value="First">First Half</option>
                        <option value="Second">Second Half</option>
                    </select>
                </div>

                <div class="col-12 col-md-4 shortleave-box d-none">
                    <label class="form-label fw-semibold">Short Leave Hours</label>
                    <input type="number"
                        name="term_details[${index}][short_leave_hours]"
                        class="form-control"
                        min="0.25"
                        step="0.25"
                        placeholder="e.g. 1.5">
                </div>
            `;

            index++;
            container.appendChild(row);
        }

        attachChangeListeners();
    }

    function attachChangeListeners() {
        document.querySelectorAll('.day-term').forEach(select => {
            toggleTermFields(select);
            select.addEventListener('change', () => toggleTermFields(select));
        });
    }

    function toggleTermFields(select) {
        const row = select.closest('.row');
        const halfDayBox = row.querySelector('.halfday-box');
        const shortLeaveBox = row.querySelector('.shortleave-box');

        // Reset required
        halfDayBox.classList.add('d-none');
        shortLeaveBox.classList.add('d-none');
        halfDayBox.querySelector('select').required = false;
        shortLeaveBox.querySelector('input').required = false;

        if (select.value === 'Halfday') {
            halfDayBox.classList.remove('d-none');
            halfDayBox.querySelector('select').required = true;
        }

        if (select.value === 'Shortleave') {
            shortLeaveBox.classList.remove('d-none');
            shortLeaveBox.querySelector('input').required = true;
        }
    }

    // Document required based on leave type
    leaveTypeSelect.addEventListener('change', function () {
        const requiresDoc = this.selectedOptions[0]?.dataset.requiresDocument == 1;
        documentInput.required = requiresDoc;
        generateTermFields();
    });

    startDateInput.addEventListener('change', generateTermFields);
    endDateInput.addEventListener('change', generateTermFields);

    if (startDateInput.value) generateTermFields();
})();
</script>
@endpush
