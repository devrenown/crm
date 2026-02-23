@extends('layouts.app')

@section('page-content')
<div class="content container-fluid">

    <x-breadcrumb class="col">
        <x-slot name="title">Shifts</x-slot>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Shifts</li>
        </ul>
        <x-slot name="right">
                <div class="col-auto float-end ms-auto">
                    @activeCan('create-shift')
                        <a href="javascript:void(0)" data-url="{{ route('shift.create') }}" class="btn add-btn" data-ajax-modal="true"
                            data-size="lg" data-title="{{ __('Add Shift') }}">
                            <i class="fa-solid fa-plus"></i> {{ __('Add Shift') }}
                        </a>
                    @endactiveCan

                    <div class="view-icons">
                        <a href="{{ route('shift.index') }}" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                        <a href="{{ route('shift.list') }}" class="list-view btn btn-link"><i class="fa-solid fa-bars"></i></a>
                    </div>
                </div>
        </x-slot>
    </x-breadcrumb>

    <div class="row">
        @foreach($shifts as $shift)
        <div class="col-md-4 col-lg-3">
            <div class="card shift-card shadow-sm">
                <div class="card-body position-relative">

                    @activeAnyCan(['edit-shift','delete-shift'])
                    <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                        <a href="#" class="text-muted" data-bs-toggle="dropdown">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            @activeCan('edit-shift')
                            <a class="dropdown-item"
                               href="javascript:void(0)"
                               data-url="{{ route('shift.edit', $shift->id) }}"
                               data-ajax-modal="true"
                               data-size="lg"
                               data-title="Edit Shift">
                                <i class="fa fa-pencil me-2"></i> Edit
                            </a>
                            @endactiveCan

                            @activeCan('delete-shift')
                            <a class="dropdown-item deleteBtn"
                               href="javascript:void(0)"
                               data-route="{{ route('shift.destroy', $shift->id) }}"
                               data-title="Delete Shift"
                               data-question="Are you sure?">
                                <i class="fa fa-trash me-2"></i> Delete
                            </a>
                            @endactiveCan

                            <a class="dropdown-item"
                               href="javascript:void(0)"
                               data-url="{{ route('shift.add-remove-employee-view', $shift->id) }}"
                               data-ajax-modal="true"
                               data-size="lg"
                               data-title="Add/Remove Employees From This Shift">
                               <i class="fa-solid fa-users-gear me-2"></i> Add/Remove Employees
                            </a>
                        </div>
                    </div>
                    @endactiveAnyCan

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-truncate">{{ $shift->name }}</h5>
                        <span class="badge bg-inverse-{{ $shift->status == 1 ? 'success' : 'danger' }} me-3">
                            {{ $shift->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <p class="text-muted mb-3 mt-2">
                        <i class="fa fa-clock me-1"></i>
                        {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                        -
                        {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                    </p>

                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>
                            Break: {{ $shift->break_minutes ?? 0 }} min
                        </span>
                        <span>
                            Grace: {{ $shift->grace_minutes ?? 0 }} min
                        </span>
                    </div>

                    <div class="shift-stats mb-3">
                        <h3 class="mb-0">{{ $shift->employees_count }}</h3>
                        <small class="text-muted">Employees Assigned</small>
                    </div>

                    <a href="{{ route('shifts.employees', $shift->id) }}"
                       class="btn btn-sm btn-outline-primary w-100">
                        View Employees
                    </a>

                </div>
            </div>
        </div>

        @endforeach
    </div>

</div>
@endsection
