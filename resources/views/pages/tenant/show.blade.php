@extends('layouts.app')

@section('page-content')

<div class="content container-fluid">

    <!-- Page Header -->
    <x-breadcrumb class="col">
        <x-slot name="title">
            {{ __('Organization Details') }} :– {{ $tenant->name }} 
        </x-slot>

        <x-slot name="right">
            <div class="col-auto float-end ms-auto">
                <a href="{{ route('tenant.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-circle-left"></i> {{ __('Go Back') }}
                </a>
            </div>
        </x-slot>
    </x-breadcrumb>


    <!-- Tenant Overview -->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">

                    <!-- Tenant Logo -->
                    <img src="{{ $file ? asset('storage/' . $file) : asset('images/company-placeholder.png') }}"
                         class="img-fluid mb-3"
                         style="max-height: 110px;" alt="Logo">

                    <h4 class="mb-1">{{ $tenant->name }}</h4>
                    <p class="text-muted mb-2">{{ $tenant->domain }}</p>

                    <span class="badge 
                        @if($tenant->status == \App\Enums\TenantStatus::ACTIVE) bg-inverse-success
                        @elseif($tenant->status == \App\Enums\TenantStatus::INACTIVE) bg-inverse-secondary
                        @else bg-inverse-danger
                        @endif">
                        {{ $tenant->status->value }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="col-md-8">
            <div class="row">

            	<div class="col-md-4 col-sm-6 mb-1">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3>{{ count($tenant->users) }}</h3>
                            <p class="text-muted">Total Users</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-1">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3>{{ count($tenant->employees) }}</h3>
                            <p class="text-muted">Employees</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-1">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3>{{ $clientCount }}</h3>
                            <p class="text-muted">Clients</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-1">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3>{{ @$projectCount ?? 0 }}</h3>
                            <p class="text-muted">Projects</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-1">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3>{{ $invoiceCount }}</h3>
                            <p class="text-muted">Invoices</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-1">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3>{{ @$activeUserCount ?? 0 }}</h3>
                            <p class="text-muted">Active Users</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Company Information -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Admin Information</h5>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-lg-4 mb-2 mb-lg-0 mb-md-0">
                    <img src="{{ @$tenant->adminUser->avatar ? asset('storage/' . @$tenant->adminUser->avatar) : asset('images/user.jpg') }}" style="height: 100px; width: 100px; object-fit: cover;">
                </div>

                <div class="col-lg-4 mb-2 mb-lg-0 mb-md-0">
                    <strong class="col-lg-6">Full Name:</strong>
                    <p class="col-lg-6">{{ @$tenant->adminUser->fullname ?? 'Not Provided' }}</p>

                    <strong class="col-lg-6">Phone:</strong>
                    <p class="col-lg-6">{{ @$tenant->adminUser->phone ?? 'Not Provided' }}</p>
                </div>

                <div class="col-lg-4 mb-2 mb-lg-0 mb-md-0">
                    <strong class="col-lg-6">Email:</strong>
                    <p class="col-lg-12">{{ @$tenant->adminUser->email ?? 'Not Provided' }}</p>

                    <!-- <strong class="col-lg-12">Address:</strong>
                    <p class="col-lg-6">{{ @$tenant->adminUser->address ?? 'Not Provided' }}</p> -->
                </div>

            </div>
        </div>
    </div>

    <!-- Company Information -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Plan Details</h5>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-lg-4 mb-2 mb-lg-0 mb-md-0">
                    <strong class="col-lg-6">Plan</strong>
                    <p class="col-lg-6">{{ @$subscription->plan->name }}</p>

                    <strong class="col-lg-6">Start Date:</strong>
                    <p class="col-lg-6">{{ date('d M Y', strtotime($subscription->start_date)) }}</p>
                </div>

                <div class="col-lg-4 mb-2 mb-lg-0 mb-md-0">
                    <strong class="col-lg-6">Duration:</strong>
                    <p class="col-lg-6">{{ @$subscription->plan->duration ?? '0' }}days</p>

                    <strong class="col-lg-6">End Date:</strong>
                    <p class="col-lg-6">{{ date('d M Y', strtotime($subscription->end_date)) }}</p>
                </div>

                <div class="col-lg-4 mb-2 mb-lg-0 mb-md-0">
                    @php
                      $badge = match ($subscription->status) {
                        1 => '<span class="badge bg-inverse-success badge-sm">Active</span>',
                        2 => '<span class="badge bg-inverse-danger badge-sm">Expired</span>',
                        3 => '<span class="badge bg-inverse-secondary badge-sm">Canceled</span>',
                        default => null
                      }
                    @endphp

                    <strong class="col-lg-6">Status:</strong>
                    <p class="col-lg-6">{!! $badge !!}</p>
                </div>

            </div>
        </div>
    </div>


    <!-- Company Information -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Company Information</h5>
        </div>

        @php
            $address = $company->firstWhere('name', 'address');
            $phone   = $company->firstWhere('name', 'phone');
            $mobile   = $company->firstWhere('name', 'mobile');
            $email   = $company->firstWhere('name', 'email');
            $companyName = $company->firstWhere('name', 'name');
        @endphp

        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-2">
                    <strong>Custom Company Name:</strong><br>
                    {{ trim($companyName->payload, '"') ?? 'Not Provided' }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Email:</strong><br>
                    {{ trim($email->payload, '"') ?? 'Not Provided' }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Phone No:</strong><br>
                    {{ trim($phone->payload, '"') ?? 'Not Provided' }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Mobile No:</strong><br>
                    {{ trim($mobile->payload, '"') ?? 'Not Provided' }}
                </div>
                

                <div class="col-md-6 mb-2">
                    <strong>Address:</strong><br>
                    {{ trim($address->payload, '"') ?? 'Not Provided' }}
                </div>

            </div>
        </div>
    </div>


    <!-- Theme Settings -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Theme Settings</h5>
        </div>

        <div class="card-body">

            @php
                $primaryColor = $theme->firstWhere('name', 'primary_color');
            @endphp

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Primary Color:</strong><br>
                    <span style="display:inline-block;width:40px;height:20px;background:{{ $primaryColor->payload ?? '#ccc' }};"></span>
                </div>
            </div>

        </div>
    </div>


</div>

@endsection
