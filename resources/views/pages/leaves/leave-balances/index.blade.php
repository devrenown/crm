@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
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

    {{-- Card --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Year</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($leaveBalances as $row)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">
                                {{ $row->user?->name }}
                            </div>
                            <small class="text-muted">
                                {{ $row->leave_types_count }} leave types
                            </small>
                        </td>

                        <td>
                            <span class="badge bg-info">
                                {{ $row->year }}
                            </span>
                        </td>

                        <td class="text-end pe-4">
                            <a href="{{ route('leave-balances.edit', $row->user_id) }}?year={{ $row->year }}"
                            class="btn btn-sm btn-outline-warning me-1"
                            title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>

                            <form action="{{ route('leave-balances.destroy', $row->user_id) }}?year={{ $row->year }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete all leave balances for this employee and year?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty

                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
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
