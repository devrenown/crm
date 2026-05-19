@extends('layouts.app')

<style>
    .profile-widget {
        padding: 20px 20px 0px 20px !important;
    }

    .header #toggle_btn {
        padding: 20px 10px !important;
    }

    .header .header-left {
        padding: 10px 20px !important;
    }
</style>

@section('page-content')
    <div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb class="col">
            <x-slot name="title">{{ __('Employees') }}</x-slot>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ __('Employees') }}
                </li>
            </ul>
            <x-slot name="right">
                <div class="col-auto ms-auto">

                    <div class="d-flex align-items-center gap-2">

                        @activeCan('create-employee')
                            <a href="javascript:void(0)"
                               data-url="{{ route('employees.create') }}"
                               class="btn add-btn btn-sm"
                               data-ajax-modal="true"
                               data-size="lg"
                               data-title="Add Employee">
                                <i class="fa-solid fa-plus"></i> {{ __('Add Employee') }}
                            </a>
                        @endactiveCan

                        <form action="{{ route('employees.index') }}" method="GET" class="mb-0">
                            <select class="form-control" name="status" id="status" style="max-width: 100px;" onchange="this.form.submit()">
                                <option value="active" {{ request('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>

                        <div class="view-icons d-flex align-items-center">
                            <a href="{{ route('employees.index') }}"
                               class="grid-view btn btn-link bg-primary text-white p-2">
                                <i class="fa fa-th"></i>
                            </a>
                            <a href="{{ route('employees.list') }}"
                               class="list-view btn btn-link p-2">
                                <i class="fa-solid fa-bars"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </x-slot>
        </x-breadcrumb>
        <!-- /Page Header -->


        <div class="row staff-grid-row">
            @if (!empty($employees))
                @foreach ($employees as $employee)
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                    <div class="profile-widget">
                        <div class="profile-img">
                            @activeCan('view-employees')
                                <a href="{{ route('employees.show', ['employee' => \Crypt::encrypt($employee->id)]) }}" class="avatar">
                                    <img src="{{ !empty($employee->avatar) ? asset('storage/' . $employee->avatar) : asset('images/user.jpg') }}" alt="User Image">
                                </a>
                            @else
                                <span class="avatar">
                                    <img src="{{ !empty($employee->avatar) ? asset('storage/' . $employee->avatar) : asset('images/user.jpg') }}" alt="User Image">
                                </span>
                            @endactiveCan

                        </div>
                        <div class="dropdown profile-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            @activeAnyCan(['edit-employee', 'delete-employee'])
                            <div class="dropdown-menu dropdown-menu-right">
                                @activeCan('edit-employee')
                                <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('employees.edit', ['employee' => \Crypt::encrypt($employee->id)]) }}" data-ajax-modal="true"
                                    data-title="Edit Employee" data-size="lg">
                                    <i class="fa-solid fa-pencil m-r-5"></i>
                                    {{ __('Edit') }}
                                </a>
                                @endactiveCan
                                @activeCan('delete-employee')
                                <a class="dropdown-item deleteBtn" data-route="{{ route('employees.destroy', $employee->id) }}" data-title="Delete Employee"
                                    data-question="Are you sure you want to delete?" href="javascript:void(0)">
                                    <i class="fa-regular fa-trash-can m-r-5"></i>
                                    {{ __('Delete') }}
                                </a>
                                @endactiveCan
                            </div>
                            @endactiveAnyCan
                        </div>

                        @activeCan('view-employees')
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="{{ route('employees.show', ['employee' => \Crypt::encrypt($employee->id)]) }}">{{ $employee->fullname }}</a></h4>
                        @else
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis">{{ $employee->fullname }}</h4>
                        @endactiveCan

                        @if (!empty($employee->employeeDetail) && !empty($employee->employeeDetail->designation))
                        <div class="small text-muted">{{ $employee->employeeDetail->designation->name }}</div>
                        @endif

                        <div class="d-flex justify-content-between align-item-center">
                            <div>
                                <span class="badge bg-inverse-{{ $employee->is_active == 1 ? 'success' : ($employee->is_active == 2 ? 'warning' : 'danger') }}">{{ $employee->is_active == 1 ? 'Active' : ($employee->is_active == 2 ? 'Pending' : 'Deactive') }}</span>
                            </div>

                            <p class="text-sm fw-bold">{{ $employee->shift?->shift?->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach

                {{ $employees->withQueryString()->links() }}
            @endif
        </div>
    </div>
@endsection