@php
    $type = $type ?? 'status';
@endphp


{{-- ================= LEAVE STATUS BADGE ================= --}}
@if($type === 'status')
    @php
        $status = $leave->status ?? 'Pending';

        $colors = [
            'Pending'          => 'warning',
            'Pending_level_1'  => 'secondary',
            'Pending_level_2'  => 'info',
            'Approved'         => 'success',
            'Rejected'         => 'danger',
            'Cancelled'        => 'dark',
        ];

        $labels = [
            'Pending'          => 'Pending',
            'Pending_level_1'  => 'Pending L1',
            'Pending_level_2'  => 'Pending L2',
            'Approved'         => 'Approved',
            'Rejected'         => 'Rejected',
            'Cancelled'        => 'Cancelled',
        ];

        $color = $colors[$status] ?? 'secondary';
        $label = $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    @endphp

    <span class="badge bg-{{ $color }}">
        {{ $label }}
    </span>
@endif


{{-- ================= LEAVE TERM BADGE ================= --}}
@if($type === 'term')
    @php
        $term = $leave->term ?? 'Fullday';

        $colors = [
            'Fullday'    => 'primary',
            'Halfday'    => 'warning',
            'Shortleave' => 'info',
        ];

        $labels = [
            'Fullday'    => 'Full Day',
            'Halfday'    => 'Half Day',
            'Shortleave' => 'Short Leave',
        ];

        $color = $colors[$term] ?? 'secondary';
        $label = $labels[$term] ?? $term;
    @endphp

    <span class="badge bg-{{ $color }}">
        {{ $label }}
    </span>
@endif


{{-- ================= APPROVAL STAGE BADGE ================= --}}
@if($type === 'approval')
    @php
        $stage = $leave->approval_stage ?? 'L1';

        $colors = [
            'L1'    => 'secondary',
            'L2'    => 'info',
            'FINAL' => 'success',
        ];

        $labels = [
            'L1'    => 'Level 1',
            'L2'    => 'Level 2',
            'FINAL' => 'Final Approval',
        ];

        $color = $colors[$stage] ?? 'dark';
        $label = $labels[$stage] ?? $stage;
    @endphp

    <span class="badge bg-{{ $color }}">
        {{ $label }}
    </span>
@endif
