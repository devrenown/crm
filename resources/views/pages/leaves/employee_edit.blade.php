@extends('layouts.app')

@section('page-content')
@php
    $leave = $leaveRequest ?? $leave;

    // Ensure term_details is an array keyed by date
    $termDetails = is_string($leave->term_details)
        ? json_decode($leave->term_details, true) ?? []
        : ($leave->term_details ?? []);

    // Convert to array of objects [{date:'2026-01-23', term:'Fullday', ...}, ...]
    $termDetailsArray = [];
    foreach($termDetails as $date => $detail){
        $termDetailsArray[] = array_merge(['date'=>$date], $detail);
    }
@endphp

<div class="content container-fluid">

    <x-breadcrumb class="col">
        <x-slot name="title">Edit Leave Request</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">Leave Requests</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ul>
    </x-breadcrumb>

    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Leave Request</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('leaves.update', $leave->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- BASIC INFO --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Leave Type</label>
                                <select name="leave_type_id" class="form-select" required>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" {{ $leave->leave_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- DATES --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ $leave->start_date?->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ $leave->end_date?->format('Y-m-d') }}">
                            </div>
                        </div>

                        {{-- TERM DETAILS --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Leave Terms (per day)</label>
                            <div id="term_details_container" class="border rounded p-2 bg-light"></div>
                        </div>

                        {{-- REASON --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Reason</label>
                                <textarea class="form-control" rows="8" name="reason">{{ $leave->reason }}</textarea>
                            </div>
                        </div>

                        {{-- DOCUMENT UPLOAD --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Upload New Document (optional)</label>
                                <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($leave->document_path)
                                    @php
                                        $url = URL::temporarySignedRoute(
                                            'leaves.document.view',
                                            now()->addMinutes(2),
                                            ['leave' => $leave->id]
                                        );
                                    @endphp
                                        <button type="button" class="btn mt-2 btn-outline-primary btn-sm"
                                                onclick="openSecureDocument('{{ $url }}')">
                                            <i class="bi bi-file-earmark-text"></i> View
                                        </button>
                                        <small class="text-muted">Uploading a new will replace the existing one.</small>  
                                @endif
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-success px-4">Save Changes</button>
                            <a href="{{ route('leaves.index') }}" class="btn btn-secondary px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@push('page-scripts')
<script>
(function() {
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    const leaveTypeSelect = document.querySelector('[name="leave_type_id"]');
    const container = document.getElementById('term_details_container');

    // Correct format of existing terms: array of objects [{date, term, half_day_type, short_leave_hours}]
    let existingTerms = @json($termDetailsArray);

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

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dayStr = d.toISOString().split('T')[0];

            const existing = existingTerms.find(t => t.date === dayStr) || {};

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
                    <select name="term_details[${index}][term]" class="form-select day-term" required>
                        <option value="Fullday" ${existing.term==='Fullday'?'selected':''}>Full Day</option>
                        <option value="Halfday" ${existing.term==='Halfday'?'selected':''}>Half Day</option>
                        <option value="Shortleave" ${existing.term==='Shortleave'?'selected':''}>Short Leave</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 halfday-box ${existing.term==='Halfday'?'':'d-none'}">
                    <label class="form-label fw-semibold">Half Day Type</label>
                    <select name="term_details[${index}][half_day_type]" class="form-select">
                        <option value="First" ${existing.half_day_type==='First'?'selected':''}>First Half</option>
                        <option value="Second" ${existing.half_day_type==='Second'?'selected':''}>Second Half</option>
                    </select>
                </div>

                <div class="col-12 col-md-4 shortleave-box ${existing.term==='Shortleave'?'':'d-none'}">
                    <label class="form-label fw-semibold">Short Leave Hours</label>
                    <input type="number" name="term_details[${index}][short_leave_hours]" class="form-control"
                           min="0.25" step="0.25" placeholder="e.g. 1.5"
                           value="${existing.short_leave_hours || ''}">
                </div>
            `;
            container.appendChild(row);
            index++;
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

    startDateInput.addEventListener('change', generateTermFields);
    endDateInput.addEventListener('change', generateTermFields);
    leaveTypeSelect.addEventListener('change', generateTermFields);

    // Initial render
    generateTermFields();
})();
</script>
@endpush
@endsection
