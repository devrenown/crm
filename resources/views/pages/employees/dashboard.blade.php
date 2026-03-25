@extends('layouts.app')

@php
  $user = auth()->user();
@endphp

@push('page-styles')
@endpush

@section('page-content')
    <div class="content container-fluid">

        <!-- WELCOME CARD -->
        <div class="welcome-card px-4 rounded-3">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h3 class="fw-bold">
                        Welcome Back, {{ $user->fullname }}
                    </h3>
                    <p class="mb-0">
                        Here is your daily work summary.
                    </p>
                </div>
                <div class="col-md-3 text-end">
                    <img src="{{ $user->gender == 1 ?  asset('images/welcome-icon.png') : asset('images/emp-welcome.png') }}" style="max-height: 5rem;">
                </div>
            </div>
        </div>


        <!-- Page Header -->
        <x-breadcrumb>

        </x-breadcrumb>
        <!-- /Page Header -->


        <livewire:employee-attendance 
         :upcomingBirthdays="$upcomingBirthdays" 
         :upcomingWorkAnniversaries="$upcomingWorkAnniversaries"
         :myTasks="$myTasks"
        />

    </div>
@endsection


@push('page-scripts')
@endpush
