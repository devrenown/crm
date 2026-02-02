@extends('layouts.app')

@section('page-content')

<div class="content container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Leave Types</h4>
        @activeCan('create-leave-type')
            <a href="{{ route('leave-type.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Leave Type
            </a>
        @endactiveCan
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped align-middle table-sm" style="font-size: 13px;">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Paid</th>
                        <th>Max / Year</th>
                        <th class="text-wrap">Carry Forward</th>
                        <th>Max Carry</th>
                        <th class="text-wrap">Monthly Accrual</th>
                        <th class="text-wrap">Accrual Rate</th>
                        <th class="text-wrap">Requires Document</th>
                        <th class="text-wrap">Min Notice</th>
                        <th class="text-wrap">Max Per Application</th>
                        <th>Gender</th>
                        <th>Encashable</th>
                        <th>Active</th>
                        <th>Requires L2</th>
                        <th>L2 Roles</th>
                        @activeAnyCan(['edit-leave-type','delete-leave-type'])
                            <th>Actions</th>
                        @endactiveAnyCan
                    </tr>
                </thead>

                <tbody>
                    @forelse($leaveTypes as $type)
                        <tr>
                            <td>{{ $type->id }}</td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->description }}</td>
                            <td>{{ $type->is_paid ? 'Yes' : 'No' }}</td>
                            <td>{{ $type->max_days_per_year ?? '-' }}</td>
                            <td>{{ $type->carry_forward ? 'Yes' : 'No' }}</td>
                            <td>{{ $type->max_carry_forward ?? '-' }}</td>
                            <td>{{ $type->monthly_accrual ? 'Yes' : 'No' }}</td>
                            <td>{{ $type->accrual_rate }}</td>
                            <td>{{ $type->requires_document ? 'Yes' : 'No' }}</td>
                            <td>{{ $type->min_days_notice ?? '-' }}</td>
                            <td>{{ $type->max_days_per_application ?? '-' }}</td>
                            <td>
                                @php
                                    $genders = [0 => 'All', 1 => 'Male', 2 => 'Female', 3 => 'Other'];
                                @endphp
                                {{ $genders[$type->gender] ?? 'Unknown' }}
                            </td>
                            <td>{{ $type->is_encashable ? 'Yes' : 'No' }}</td>
                            <td>{{ $type->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>{{ $type->requires_l2_approval ? 'Yes' : 'No' }}</td>
                            <td>
                                @if($type->requires_l2_approval && !empty($type->l2_roles))
                                    @foreach($type->l2_roles as $roleId)
                                        @php
                                            $role = $rolesMap[$roleId] ?? null;
                                        @endphp
                                        @if($role)
                                            <span class="badge bg-info mb-1">{{ $role }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    —
                                @endif
                            </td>
                            

                            @activeAnyCan(['edit-leave-type','delete-leave-type'])
                                <td>
                                    @activeCan('edit-leave-type')
                                        <a href="{{ route('leave-type.edit', $type->id) }}"
                                           class="btn btn-sm btn-warning mb-1">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endactiveCan

                                    @activeCan('delete-leave-type')
                                        <form action="{{ route('leave-type.destroy', $type->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger mb-1"
                                                onclick="return confirm('Delete this leave type?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endactiveCan
                                </td>
                            @endactiveAnyCan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="23" class="text-center text-muted">No leave types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a href="{{ route('leaves.index') }}" class="btn btn-secondary mt-2">
                <i class="fa fa-arrow-left"></i> Back
            </a>

        </div>
    </div>
</div>

@endsection
