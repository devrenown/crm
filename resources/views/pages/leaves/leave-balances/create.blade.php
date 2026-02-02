@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    <h4 class="mb-3">Generate Leave Balances</h4>

    <form action="{{ route('leave-balances.store') }}" method="POST">
        @csrf

        {{-- EMPLOYEE --}}
        <div class="mb-3">
            <label class="form-label">Employee</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- YEAR --}}
        <div class="mb-3">
            <label class="form-label">Year</label>
            <input type="number"
                   name="year"
                   class="form-control"
                   value="{{ $year }}"
                   required>
        </div>

        <hr>

        {{-- LEAVE TYPES --}}
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Leave Type</th>
                    <th>Opening</th>
                    <th>Accrued</th>
                    <th>Carry Forward</th>
                    <th>Manual Adjustment</th>
                </tr>
            </thead>

            <tbody>
            @foreach($leaveTypes as $type)
                <tr>
                    <td>
                        <strong>{{ $type->name }}</strong>
                        <div class="small text-muted">
                            @if($type->monthly_accrual)
                                Monthly accrual ({{ $type->accrual_rate }}/month)
                            @else
                                Yearly allocation
                            @endif
                        </div>
                    </td>

                    {{-- OPENING (SYSTEM) --}}
                    <td>
                        <input type="number"
                            name="leave_types[{{ $type->id }}][opening_balance]"
                            class="form-control"
                            value="0"
                            readonly>
                    </td>

                    {{-- ACCRUED (SYSTEM) --}}
                    <td>
                        <input type="number"
                            name="leave_types[{{ $type->id }}][accrued_leaves]"
                            class="form-control"
                            value="0"
                            readonly>
                    </td>

                    {{-- CARRY FORWARD (SYSTEM) --}}
                    <td>
                        <input type="number"
                            name="leave_types[{{ $type->id }}][carry_forwarded]"
                            class="form-control"
                            value="0"
                            readonly>
                    </td>

                    {{-- MANUAL ADJUSTMENT (HR CONTROLLED) --}}
                    <td>
                        <input type="number"
                            step="0.01"
                            name="leave_types[{{ $type->id }}][manual_adjustment]"
                            class="form-control"
                            value="0">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <button class="btn btn-primary">
                <i class="fa fa-cogs"></i> Generate Leave Balances
            </button>

            <a href="{{ route('leave-balances.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection


