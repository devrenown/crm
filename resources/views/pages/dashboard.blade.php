@extends('layouts.app')

@push('page-styles')
    <!-- Chart CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/morris/morris.css') }}">
@endpush

@section('page-content')
    <div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb>
            <x-slot name="title">{{ __('Welcome') }}
                {{ !empty(auth()->user()->fullname) ? auth()->user()->fullname . ' !' : '' }}</x-slot>
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
            </ul>
        </x-breadcrumb>
        <!-- /Page Header -->

        @activeRole('Super Admin')
            @include('pages.dashboards.super-admin')
        @endactiveRole

        @activeRoleIn(['Admin', 'Manager', 'Accountant', 'Hr', 'Tl', 'Client'])

            @include('pages.dashboards.admin')
        @endactiveRoleIn


    </div>
@endsection
