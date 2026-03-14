@extends('layouts.app')

@push('page-styles')
    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/morris/morris.css') }}">
@endpush

@section('page-content')
    <div class="content container-fluid">

        <!-- Welcome Header -->
        <div class="welcome-card px-4 mb-4 d-flex align-items-center justify-content-between">
            <h4 class="fw-bold mb-0">Welcome Back, {{ !empty(auth()->user()->fullname) ? auth()->user()->fullname . ' !' : '' }}</h4>
            <img src="{{ asset('images/welcome-icon.png') }}" alt="">
        </div>

        @activeRole('Super Admin')
            @include('pages.dashboards.super-admin')
        @endactiveRole

        @activeRoleIn(['Admin', 'Manager', 'Accountant', 'Hr', 'Tl', 'Client'])

            @include('pages.dashboards.admin')
        @endactiveRoleIn


    </div>
@endsection
