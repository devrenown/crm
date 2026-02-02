@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    <h4 class="mb-3">
        Edit Leave Balances – {{ $employee->name }} ({{ $year }})
    </h4>

    <form method="POST" action="{{ route('leave-balances.update', $employee->id) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="year" value="{{ $year }}">

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Leave Type</th>
                    <th>Opening</th>
                    <th>Accrued</th>
                    <th>Carry Forward</th>
                    <th>Manual Adjustment</th>
                    <th>Used</th>
                    <th>Remaining</th>
                </tr>
            </thead>

            <tbody>
                @foreach($leaveTypes as $type)
                    @php
                        $balance = $balances[$type->id] ?? null;
                    @endphp

                    <tr>
                        <td>
                            <strong>{{ $type->name }}</strong>
                            @if($type->monthly_accrual)
                                <div class="text-muted small">Monthly Accrual</div>
                            @endif
                        </td>

                        {{-- OPENING --}}
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[{{ $type->id }}][opening_balance]"
                                value="{{ $balance->opening_balance ?? 0 }}"
                                class="form-control"
                                {{ $type->monthly_accrual ? 'readonly' : '' }}>
                        </td>

                        {{-- ACCRUED --}}
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[{{ $type->id }}][accrued_leaves]"
                                value="{{ $balance->accrued_leaves ?? 0 }}"
                                class="form-control"
                                {{ !$type->monthly_accrual ? 'readonly' : '' }}>
                            @if($type->monthly_accrual)
                                <small class="text-muted">
                                    Rate: {{ $type->accrual_rate }}/month
                                </small>
                            @endif
                        </td>

                        {{-- CARRY FORWARD --}}
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[{{ $type->id }}][carry_forwarded]"
                                value="{{ $balance->carry_forwarded ?? 0 }}"
                                class="form-control"
                                {{ !$type->carry_forward ? 'readonly' : '' }}>
                            @if($type->carry_forward && $type->max_carry_forward)
                                <small class="text-muted">
                                    Max: {{ $type->max_carry_forward }}
                                </small>
                            @endif
                        </td>

                        {{-- MANUAL ADJUSTMENT --}}
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[{{ $type->id }}][manual_adjustment]"
                                value="{{ $balance->manual_adjustment ?? 0 }}"
                                class="form-control">
                        </td>

                        {{-- USED --}}
                        <td class="text-center">
                            {{ $balance->used_leaves ?? 0 }}
                        </td>

                        {{-- REMAINING --}}
                        <td class="text-center fw-bold">
                            {{ $balance->remaining_leaves ?? 0 }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <button class="btn btn-success">
                <i class="fa fa-save"></i> Update Leave Balances
            </button>

            <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
