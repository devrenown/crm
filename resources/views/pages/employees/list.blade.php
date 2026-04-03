@extends('layouts.app')


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
                               class="btn add-btn"
                               data-ajax-modal="true"
                               data-size="lg"
                               data-title="Add Employee">
                                <i class="fa-solid fa-plus"></i> {{ __('Add Employee') }}
                            </a>
                        @endactiveCan

                        <form action="{{ route('employees.list') }}" method="GET" class="mb-0">
                            <select class="form-control" name="status" id="status" style="max-width: 100px;" onchange="this.form.submit()">
                                <option value="active" {{ request('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>

                        <div class="view-icons d-flex align-items-center">
                            <a href="{{ route('employees.index') }}"
                               class="grid-view btn btn-link px-2">
                                <i class="fa fa-th"></i>
                            </a>
                            <a href="{{ route('employees.list') }}"
                               class="list-view btn btn-link bg-primary text-white px-2">
                                <i class="fa-solid fa-bars"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </x-slot>
        </x-breadcrumb>
        <!-- /Page Header -->

        <!-- Search Filter -->

        <!-- /Search Filter -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-striped custom-table w-100']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('page-scripts')
@vite([
    "resources/js/datatables.js"
])
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@endpush