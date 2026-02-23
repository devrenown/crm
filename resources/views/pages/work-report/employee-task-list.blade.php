@extends('layouts.app')

@section('page-content')

<style>
    #tasks-table th:nth-child(3),
    #tasks-table td:nth-child(3) {
        width: 200px !important;  /* Description column */
    }
</style>
    <div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb class="col">
            <x-slot name="title">{{ __('Employee Work History') }}</x-slot>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('work-report.index') }}">{{ __('Work Reports') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ __('Employee Work History') }}
                </li>
            </ul>
            <x-slot name="right">
                <div class="col-auto float-end ms-auto">
                    <a href="javascript:history.back()" class="btn btn-dark btn-sm rounded me-3">
                        <i class="fa-solid fa-circle-left"></i> {{ __('Go Back') }}
                    </a>
                    @can('create-tasks')
                        <a href="javascript:void(0)" data-url="{{ route('clockout-modal') }}" class="btn add-btn" data-ajax-modal="true"
                            data-size="lg" data-title="{{ __('Add Ticket') }}">
                            <i class="fa-solid fa-plus"></i> {{ __('Add Work Reports') }}
                        </a>
                    @endcan
                </div>
            </x-slot>
        </x-breadcrumb>
        <!-- /Page Header -->
        

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
    "resources/js/datatables.js",
    "resources/assets/css/ckeditor.css",
    "resources/js/ckeditor.js"
])
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

<script>
    $('#tasks-table').DataTable({
        autoWidth: false,
        columnDefs: [
            { width: "200px", targets: 2 }, 
        ]
    });
</script>
@endpush