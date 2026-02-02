@extends('layouts.app')

@push('page-styles')
@endpush

@section('page-content')
    <div class="content container-fluid">

       {{-- <div class="d-flex gap-3 flex-wrap justify-content-between">
            <div class="card mb-0 flex-grow-1 p-3 d-flex flex-column align-items-center text-center">
                <i class="fa-solid fa-calendar-days fa-2x text-primary mb-2"></i>
                <h4>Total Leave</h4>
                <strong class="fs-3">5</strong>
            </div>

            <div class="card mb-0 flex-grow-1 p-3 d-flex flex-column align-items-center text-center">
                <i class="fa-solid fa-hourglass-half fa-2x text-warning mb-2"></i>
                <h4>Pending Leave</h4>
                <strong class="fs-3">2</strong>
            </div>

            <div class="card mb-0 flex-grow-1 p-3 d-flex flex-column align-items-center text-center">
                <i class="fa-solid fa-circle-check fa-2x text-success mb-2"></i>
                <h4>Approved Leave</h4>
                <strong class="fs-3">3</strong>
            </div>
        </div> --}}



        <!-- Page Header -->
        <x-breadcrumb>

        </x-breadcrumb>
        <!-- /Page Header -->


        <livewire:employee-attendance />

    </div>
@endsection


@push('page-scripts')
@endpush
