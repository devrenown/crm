@extends('layouts.app')

@section('page-content')
@php
    $leave = $leaveRequest ?? $leave;

    $termDetails = is_string($leave->term_details) 
        ? json_decode($leave->term_details, true) ?? [] 
        : ($leave->term_details ?? []);
@endphp

<div class="content container-fluid">

    {{-- ================= PAGE HEADER ================= --}}
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
                    <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ================= BASIC INFO ================= --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Leave Type</label>
                                <select name="leave_type_id" class="form-select" required {{ $canEditFields ? '' : 'disabled' }}>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" {{ $leave->leave_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- ================= DATES ================= --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ $leave->start_date?->format('Y-m-d') }}" {{ $canEditFields ? '' : 'disabled' }} required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ $leave->end_date?->format('Y-m-d') }}" {{ $canEditFields ? '' : 'disabled' }} >
                            </div>
                        </div>

                        

                        {{-- =============== TERM DETAILS =============== --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Leave Terms (per day)</label>
                                <div id="term_details_container">
                                    @php
                                        $start = \Carbon\Carbon::parse($leave->start_date);
                                        $end   = \Carbon\Carbon::parse($leave->end_date ?? $leave->start_date);
                                    @endphp

                                    @for($date = $start->copy(); $date <= $end; $date->addDay())
                                        @php
                                            $dayKey = $date->format('Y-m-d');
                                            $dayTerm = collect($termDetails)->firstWhere('date', $dayKey)
                                                ?? [
                                                    'term' => $leave->term,
                                                    'half_day_type' => $leave->half_day_type,
                                                    'short_leave_hours' => $leave->short_leave_hours
                                                ];
                                        @endphp

                                        <div class="row g-2 align-items-end mb-3 border-bottom pb-2">

                                            {{-- Date --}}
                                            <div class="col-12 col-md-3">
                                                <label class="form-label fw-semibold mb-0">Date</label>
                                                <div>{{ $date->format('d M Y') }}</div>
                                            </div>

                                            {{-- Term --}}
                                            <div class="col-12 col-md-3">
                                                <label class="form-label fw-semibold">Term</label>
                                                <select name="term_details[{{ $dayKey }}][term]" class="form-select day-term" {{ $canEditFields ? '' : 'disabled' }}>
                                                    <option value="Fullday" {{ $dayTerm['term'] === 'Fullday' ? 'selected' : '' }}>Full Day</option>
                                                    <option value="Halfday" {{ $dayTerm['term'] === 'Halfday' ? 'selected' : '' }}>Half Day</option>
                                                    <option value="Shortleave" {{ $dayTerm['term'] === 'Shortleave' ? 'selected' : '' }}>Short Leave</option>
                                                </select>
                                            </div>

                                            {{-- Half Day --}}
                                            <div class="col-12 col-md-3 halfday-box {{ $dayTerm['term'] === 'Halfday' ? '' : 'd-none' }}" {{ $canEditFields ? '' : 'disabled' }}>
                                                <label class="form-label fw-semibold">Half Day Type</label>
                                                <select name="term_details[{{ $dayKey }}][half_day_type]" class="form-select">
                                                    <option value="First" {{ ($dayTerm['half_day_type'] ?? '') === 'First' ? 'selected' : '' }}>First Half</option>
                                                    <option value="Second" {{ ($dayTerm['half_day_type'] ?? '') === 'Second' ? 'selected' : '' }}>Second Half</option>
                                                </select>
                                            </div>

                                            {{-- Short Leave --}}
                                            <div class="col-12 col-md-3 shortleave-box {{ $dayTerm['term'] === 'Shortleave' ? '' : 'd-none' }}">
                                                <label class="form-label fw-semibold">Short Leave Hours</label>
                                                <input type="number" step="0.25" min="0.25" max="8"
                                                    name="term_details[{{ $dayKey }}][short_leave_hours]"
                                                    class="form-control"
                                                    value="{{ $dayTerm['short_leave_hours'] ?? '' }}"
                                                    placeholder="e.g. 1.5" {{ $canEditFields ? '' : 'disabled' }}>
                                            </div>

                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- ================= REASON ================= --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" >Reason</label>
                                <textarea class="form-control" rows="3" {{ $canEditFields ? '' : 'disabled' }}>{{ $leave->reason }}</textarea>
                            </div>
                        </div>

                        {{-- ================= STATUS ================= --}}

                        @php
                            $isEmployee = auth()->user()->isEmployee();
                        @endphp

                        @if(!$isEmployee)
                            @php
                                $disableStatus =
                                    !$canAct ||
                                    $leave->approval_stage === 'FINAL' ||
                                    ($isL1 && $leave->approval_stage !== 'L1') ||
                                    ($isL2 && $leave->approval_stage !== 'L2');
                            @endphp

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status"
                                            class="form-select"
                                            {{ $disableStatus ? 'disabled' : '' }}>
                                        @foreach(['Pending','Approved','Rejected'] as $s)
                                            <option value="{{ $s }}"
                                                {{ $leave->status === $s ? 'selected' : '' }}>
                                                {{ $s }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        {{-- ================= STATUS DEPENDENT REMARKS ================= --}}
                        @if($canAct)
                        @if($isL1)
                            <div id="approve_box_l1" class="mb-3 d-none">
                                <label class="form-label fw-semibold">Approval Remark (L1)</label>
                                <textarea name="approved_level_1_remark"
                                    class="form-control"
                                    rows="2">{{ old('approved_level_1_remark') }}</textarea>
                            </div>

                            <div id="reject_box_l1" class="mb-3 d-none">
                                <label class="form-label fw-semibold">Rejection Reason (L1)</label>
                                <textarea name="rejection_reason_l1"
                                    class="form-control"
                                    rows="2">{{ old('rejection_reason_l1') }}</textarea>
                            </div>
                        @endif

                        @if($isL2)
                            <div id="approve_box_l2" class="mb-3 d-none">
                                <label class="form-label fw-semibold">Approval Remark (L2)</label>
                                <textarea name="approved_level_2_remark"
                                    class="form-control"
                                    rows="2">{{ old('approved_level_2_remark') }}</textarea>
                            </div>

                            <div id="reject_box_l2" class="mb-3 d-none">
                                <label class="form-label fw-semibold">Rejection Reason (L2)</label>
                                <textarea name="rejection_reason_l2"
                                    class="form-control"
                                    rows="2">{{ old('rejection_reason_l2') }}</textarea>
                            </div>
                        @endif

                    @else
                        <div class="alert alert-info">
                            This leave request has already Approved.
                        </div>
                    @endif

                      {{-- ================= APPROVAL INFO ================= --}}
                    @if(!$isEmployee)
                       <hr>
                        <h6 class="fw-semibold mb-2">Approval History</h6>

                        <ul class="list-unstyled small mb-0">

                            {{-- ================= L1 ACTION ================= --}}
                            @if($leave->approved_level_1_id || $leave->rejected_at_level === 'L1')
                                <li class="mb-2">
                                    <strong>
                                        {{ $leave->leaveType->requires_l2_approval ? 'L1' : '' }}
                                        {{ $leave->rejected_at_level === 'L1' ? 'Rejection:' : 'Approval:' }}
                                    </strong>

                                    {{ $leave->level1Approver?->name ?? $leave->user?->reportingManager?->name }}

                                    <span class="text-muted">
                                        on {{
                                            $leave->rejected_at_level === 'L1'
                                                ? $leave->updated_at?->format('d M Y, H:i')
                                                : $leave->approved_level_1_on?->format('d M Y, H:i')
                                        }}
                                    </span>

                                    {{-- L1 Remark --}}
                                    @if($leave->rejected_at_level === 'L1' && $leave->rejection_reason_l1)
                                        <div class="text-danger fst-italic ms-3">
                                            Reason: {{ $leave->rejection_reason_l1 }}
                                        </div>
                                    @elseif($leave->approved_level_1_remark)
                                        <div class="text-muted fst-italic ms-3">
                                            Remark: {{ $leave->approved_level_1_remark }}
                                        </div>
                                    @endif
                                </li>
                            @endif


                            {{-- ============== L2 ACTION ============== --}}
                            @if(
                                $leave->leaveType->requires_l2_approval &&
                                ($leave->approved_level_2_id || $leave->rejected_at_level === 'L2')
                            )
                                <li>
                                    <strong>
                                        {{ $leave->rejected_at_level === 'L2' ? 'L2 Rejection:' : 'L2 Approval:' }}
                                    </strong>

                                    {{ $leave->level2Approver?->name }}

                                    <span class="text-muted">
                                        on {{
                                            $leave->rejected_at_level === 'L2'
                                                ? $leave->updated_at?->format('d M Y, H:i')
                                                : $leave->approved_level_2_on?->format('d M Y, H:i')
                                        }}
                                    </span>

                                    {{-- L2 Remark --}}
                                    @if($leave->rejected_at_level === 'L2' && $leave->rejection_reason_l2)
                                        <div class="text-danger fst-italic ms-3">
                                            Reason: {{ $leave->rejection_reason_l2 }}
                                        </div>
                                    @elseif($leave->approved_level_2_remark)
                                        <div class="text-muted fst-italic ms-3">
                                            Remark: {{ $leave->approved_level_2_remark }}
                                        </div>
                                    @endif
                                </li>
                            @endif

                        </ul>
                    @endif


                        {{-- =============== ACTION BUTTONS =============== --}}
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

@if(session('allow_manual_adjust'))
<div class="modal fade show" id="manualAdjustModal" tabindex="-1" style="display:block;">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Insufficient Leave Balance</h5>
                <button type="button" class="btn-close" onclick="closeManualAdjustModal()"></button>
            </div>

            <div class="modal-body">
                <div class="alert alert-warning">
                    {{ session('message') }}
                    <br>
                    <strong>
                        Required adjustment:
                        {{ session('required_adjustment') }} days
                    </strong>
                </div>

                <form method="POST"
                      action="{{ route('leaves.update', session('leave_id')) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" value="Approved">

                    <div class="row">
                        <div class="col-12">
                            <x-form.input-block>
                                <x-form.label>
                                    {{ __('Manual Adjustment (Days)') }}
                                </x-form.label>

                                <x-form.input
                                    type="number"
                                    step="0.01"
                                    min="{{ session('required_adjustment') }}"
                                    name="manual_adjustment"
                                    required
                                />
                            </x-form.input-block>
                        </div>

                        <div class="col-12">
                            <x-form.input-block>
                                <x-form.label>
                                    {{ __('Adjustment Reason') }}
                                </x-form.label>

                                <textarea name="adjustment_reason"
                                          class="form-control"
                                          rows="3"
                                          required></textarea>
                            </x-form.input-block>
                        </div>

                        <input type="hidden"
                        name="approved_level_2_remark"
                        value="{{ old('approved_level_2_remark') }}">
                    </div>

                    <div class="submit-section d-flex justify-content-end gap-2">
                        <x-form.button class="btn btn-primary submit-btn">
                            {{ __('Apply & Approve') }}
                        </x-form.button>

                        <button type="button"
                                class="btn btn-secondary submit-btn"
                                onclick="cancelLeaveApproval()">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function closeManualAdjustModal() {
        document.getElementById('manualAdjustModal').remove();
        document.body.classList.remove('modal-open');
        document.querySelector('.modal-backdrop')?.remove();
    }

    function cancelLeaveApproval() {
        window.location.href = "{{ route('leaves.index') }}";
    }

    // force modal open
    document.body.classList.add('modal-open');
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    document.body.appendChild(backdrop);
</script>
@endif


{{-- =============== JS =============== --}}
@push('page-scripts')
<script>
(function () {

    /* =====================================================
     * TERM ROWS (PER DAY)
     * ===================================================== */

    function collectExistingTerms() {
        const data = [];
        document.querySelectorAll('[name^="term_details"]').forEach(el => {
            const match = el.name.match(/term_details\[(.*?)\]\[(.*?)\]/);
            if (!match) return;

            let row = data.find(d => d.date === match[1]);
            if (!row) {
                row = { date: match[1] };
                data.push(row);
            }
            row[match[2]] = el.value;
        });
        return data;
    }

    function generateTermRows() {
        const startDate = document.querySelector('input[name="start_date"]')?.value;
        const endDate   = document.querySelector('input[name="end_date"]')?.value;
        const container = document.getElementById('term_details_container');

        if (!startDate || !endDate || !container) return;

        const existingTerms = collectExistingTerms();
        const termDetails   = @json($termDetails);

        container.innerHTML = '';
        const canEditFields = @json($canEditFields);
        const start = new Date(startDate);
        const end   = new Date(endDate);

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {

            const dayKey = d.toISOString().split('T')[0];

            const dayTerm =
                existingTerms.find(t => t.date === dayKey) ||
                termDetails.find(t => t.date === dayKey) || {
                    term: "{{ $leave->term }}",
                    half_day_type: "{{ $leave->half_day_type }}",
                    short_leave_hours: "{{ $leave->short_leave_hours }}"
                };

            container.insertAdjacentHTML('beforeend', `
                <div class="row g-2 align-items-end mb-3 border-bottom pb-2">

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold mb-0">Date</label>
                        <div>${d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })}</div>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold">Term</label>
                        <select name="term_details[${dayKey}][term]" class="form-select day-term" ${canEditFields ? '' : 'disabled'}>
                            <option value="Fullday" ${dayTerm.term === 'Fullday' ? 'selected' : ''}>Full Day</option>
                            <option value="Halfday" ${dayTerm.term === 'Halfday' ? 'selected' : ''}>Half Day</option>
                            <option value="Shortleave" ${dayTerm.term === 'Shortleave' ? 'selected' : ''}>Short Leave</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 halfday-box ${dayTerm.term === 'Halfday' ? '' : 'd-none'}">
                        <label class="form-label fw-semibold">Half Day Type</label>
                        <select name="term_details[${dayKey}][half_day_type]" class="form-select" ${dayTerm.term === 'Halfday' ? '' : 'disabled'} ${canEditFields ? '' : 'disabled'}>
                            <option value="First"  ${dayTerm.half_day_type === 'First' ? 'selected' : ''}>First Half</option>
                            <option value="Second" ${dayTerm.half_day_type === 'Second' ? 'selected' : ''}>Second Half</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 shortleave-box ${dayTerm.term === 'Shortleave' ? '' : 'd-none'}">
                        <label class="form-label fw-semibold">Short Leave Hours</label>
                        <input type="number"
                               step="0.25"
                               min="0.25"
                               max="8"
                               name="term_details[${dayKey}][short_leave_hours]"
                               class="form-control"
                               value="${dayTerm.short_leave_hours || ''}"
                               ${dayTerm.term === 'Shortleave' ? '' : 'disabled'}
                               placeholder="e.g. 1.5" ${canEditFields ? '' : 'disabled'}>
                    </div>

                </div>
            `);
        }

        attachTermListeners();
    }

    function attachTermListeners() {
        document.querySelectorAll('.day-term').forEach(select => {
            select.addEventListener('change', function () {

                const row = this.closest('.row');
                const halfBox  = row.querySelector('.halfday-box');
                const shortBox = row.querySelector('.shortleave-box');

                // Hide + disable all
                halfBox.classList.add('d-none');
                shortBox.classList.add('d-none');

                halfBox.querySelectorAll('select').forEach(i => i.disabled = true);
                shortBox.querySelectorAll('input').forEach(i => i.disabled = true);

                if (this.value === 'Halfday') {
                    halfBox.classList.remove('d-none');
                    halfBox.querySelectorAll('select').forEach(i => i.disabled = false);
                }

                if (this.value === 'Shortleave') {
                    shortBox.classList.remove('d-none');
                    shortBox.querySelectorAll('input').forEach(i => i.disabled = false);
                }
            });
        });
    }

    generateTermRows();
    document.querySelector('input[name="start_date"]')?.addEventListener('change', generateTermRows);
    document.querySelector('input[name="end_date"]')?.addEventListener('change', generateTermRows);


    /* =====================================================
     * APPROVAL / REJECTION / CANCELLATION BOXES
     * ===================================================== */

     const status = document.querySelector('select[name="status"]');

    const boxes = {
        approve_l1: document.getElementById('approve_box_l1'),
        approve_l2: document.getElementById('approve_box_l2'),
        reject_l1:  document.getElementById('reject_box_l1'),
        reject_l2:  document.getElementById('reject_box_l2'),
    };

    const approvalStage = @json($leave->approval_stage); // L1 or L2
    
    function hideAll() {
        Object.values(boxes).forEach(el => el?.classList.add('d-none'));
    }
    const canAct   = @json($canAct);
    const isL1     = @json($isL1);
    const isL2     = @json($isL2);
    const approvedL1 = @json($leave->approved_level_1_id);
    const approvedL2 = @json($leave->approved_level_2_id);

    function toggleFields() {
        hideAll();

        if (!canAct || !status || !status.value) return;

    if (isL1) {
        if (status.value === 'Approved') boxes.approve_l1.classList.remove('d-none');
        if (status.value === 'Rejected') boxes.reject_l1.classList.remove('d-none');
    }

    if (isL2) {
        if (status.value === 'Approved') boxes.approve_l2.classList.remove('d-none');
        if (status.value === 'Rejected') boxes.reject_l2.classList.remove('d-none');
    }

    }

    status?.addEventListener('change', toggleFields);
    toggleFields();

})();
</script>
@endpush


@endsection
