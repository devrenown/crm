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
            <x-slot name="title">{{ activeRole() !== \App\Enums\UserType::EMPLOYEE->value ? "Employee's Latest Work Reports" : 'Work Reports' }}</x-slot>

            <ul class="breadcrumb w-100 justify-content-between">
                <div class="d-flex">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ __('Work Reports') }}
                    </li>
                </div>

                @if(activeRole() !== \App\Enums\UserType::EMPLOYEE->value)
                <div class="mb-3">
                    <select id="filterRange" class="form-select" style="width:220px;">
                        <option value="all">All Reports</option>
                        <option value="today">Today</option>
                        <option value="7days">Last 7 Days</option>
                        <option value="1month">Last 1 Month</option>
                        <option value="1year">Last 1 Year</option>
                    </select>
                </div>
                @endif
            </ul>
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

$(document).on('change', '#filterRange', function() {
    var table = $('#tasks-table').DataTable();
    var filterValue = $(this).val(); 

    var currentUrl = table.ajax.url();
    
    // 2. Remove any existing 'filter' parameter to avoid duplicates
    // This is a robust way to ensure only the latest filter is applied
    var url = new URL(currentUrl, window.location.origin);
    url.searchParams.delete('filter'); 
    
    // 3. Add the new 'filter' parameter if it's not 'all'
    if (filterValue !== 'all') {
        url.searchParams.append('filter', filterValue);
    }
    
    // 4. Set the new AJAX URL and reload the table
    table.ajax.url(url.toString()).load();
});

</script>

@endpush