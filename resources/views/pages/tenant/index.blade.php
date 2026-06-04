@extends('layouts.app')

@push('page-style')

@endpush

@section('page-content')

	<div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb class="col">
            <x-slot name="title">{{ __('Organizations') }}</x-slot>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ __('Organizations') }}
                </li>
            </ul>
            <x-slot name="right">
                <div class="col-auto float-end ms-auto">
                    @activeCan('create-organization')
                    <a href="javascript:void(0)" data-url="{{ route('tenant.create') }}" class="btn add-btn"
                        data-ajax-modal="true" data-size="lg" data-title="Add Organization">
                        <i class="fa-solid fa-plus"></i> {{ __('Add Organization') }}
                    </a>
                    @endactiveCan
                    <div class="view-icons">
                        <a href="{{ route('tenant.index') }}" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                        <a href="#" class="list-view btn btn-link"><i class="fa-solid fa-bars"></i></a>
                    </div>
                </div>
            </x-slot>
        </x-breadcrumb>
        <!-- /Page Header -->

        <div class="row staff-grid-row">
            @if (!empty($tenants))
                @foreach ($tenants as $key => $tenant)

                @php
	                $theme      = $settings[$tenant->id]['theme'] ?? collect();
	        		$company    = $settings[$tenant->id]['company'] ?? collect();

	        		$logo = $theme->firstWhere('name', 'logo_dark')
	             	?? $theme->firstWhere('name', 'logo_light');

	             	$adr = $company->firstWhere('name', 'address');
	             	$address = trim(@$adr->payload, '"');

	             	$file = trim(@$logo->payload, '"');

	             	$badge = match($tenant->status) {
				        \App\Enums\TenantStatus::ACTIVE => 'success',
				        \App\Enums\TenantStatus::INACTIVE => 'warning',
				        default => 'danger',
				    };
             	@endphp

                <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                    <div class="card">

                       <div class="p-3 text-center" style="height:100px; display:flex; align-items:center; justify-content:center;">

                            @php
                                $logoPath = $file ? asset('storage/' . ltrim($file, '/')) : asset('images/company-placeholder.png');
                            @endphp

                            <img 
                                src="{{ $logoPath }}"
                                alt="Organization Logo"
                                style="max-height:80px; max-width:100%; object-fit:contain;"
                                onerror="this.onerror=null;this.src='{{ asset('images/company-placeholder.png') }}';"
                            >

                        </div>

                        <div class="dropdown profile-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            
                            <div class="dropdown-menu dropdown-menu-right">

                            	<a class="dropdown-item" href="{{ route('tenant.show', encrypt($tenant->id)) }}">
                                    <i class="fa-solid fa-eye m-r-5"></i>
                                    {{ __('View Details') }}
                                </a>
                                
                                <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('tenant.edit', $tenant->id) }}" data-ajax-modal="true"
                                    data-title="Edit Organization" data-size="lg">
                                    <i class="fa-solid fa-pencil m-r-5"></i>
                                    {{ __('Edit') }}
                                </a>
                               
                                {{-- <a class="dropdown-item deleteBtn" data-route="#" data-title="Delete Employee"
                                    data-question="Are you sure you want to delete?" href="javascript:void(0)">
                                    <i class="fa-regular fa-trash-can m-r-5"></i>
                                    {{ __('Delete') }}
                                </a> --}}
                                
                            </div>
                            
                        </div>

                        <div class="p-3">
                        	<h4 class="card-title mb-2">{{ $tenant->name }}</h4>
                        	<div class="small text-muted">{{ $address ?? 'N/A' }}</div>
                        </div>

                        <div class="bg-light pt-3 px-3 row">
                        	<p class="col-6">Status:</p>
                        	<div class="col-6">
                        		<span class="badge bg-inverse-{{$badge}} ">{{ $tenant->status }}</span>
                        	</div>

                        	<p class="col-6">Join Date:</p>
                        	<p class="col-6">{{ date('d M Y', strtotime($tenant->created_at)) }}</p>

                        	<p class="col-lg-4">Domain:</p>
                        	<small class="col-lg-8">{{ $tenant->domain }} <a href="{{ 'https://' . $tenant->domain }}" target="_blank" title="Visit"><i class="bi bi-box-arrow-up-right ms-2"></i> </a></small>
                        </div>

                        
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

@endsection