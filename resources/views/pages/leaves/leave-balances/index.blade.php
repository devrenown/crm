@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h3 class="mb-1">Leave Balances</h3>
            <p class="text-muted mb-0">
                Manage yearly leave allocations for employees
            </p>
        </div>

        <a href="{{ route('leave-balances.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add Leave Balance
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row g-2">

                <div class="col-md-4">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search employee..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-dark w-100">
                        Filter
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('leave-balances.index') }}"
                       class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>
        </div>
    </form>

    {{-- Table Card --}}
    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Employee</th>
                        <th>Year</th>
                        <!-- <th>Leave Types</th> -->
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($leaveBalances as $row)

                    <tr>

                        {{-- Employee --}}
                        <td class="ps-3">
                            <div class="fw-semibold">
                                {{ $row->user?->name }}
                            </div>
                            <small class="text-muted">
                               {{ optional($row->user->employeeDetail)->designation?->name ?? 'Employee' }}
                            </small>
                        </td>

                        {{-- Year --}}
                        <td>
                            <span class="badge bg-info">
                                {{ $row->year }}
                            </span>
                        </td>

                        {{-- Leave Types --}}
                        <!-- <td>
                            <span class="badge bg-secondary">
                                {{ $row->leave_types_count }} Types
                            </span>
                        </td> -->

                        {{-- Actions --}}
                        <td class="text-end pe-3">

                            <a href="{{ route('leave-balances.edit', $row->user_id) }}?year={{ $row->year }}"
                               class="btn btn-sm btn-outline-warning"
                               title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>

                            <form action="{{ route('leave-balances.destroy', $row->user_id) }}?year={{ $row->year }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete all leave balances for this employee and year?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No leave balances found
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($leaveBalances->hasPages())
            <div class="card-footer bg-white">
                {{ $leaveBalances->links() }}
            </div>
        @endif

    </div>

</div>
@endsection