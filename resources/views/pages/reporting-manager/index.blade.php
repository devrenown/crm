@extends('layouts.app')

@push('page-styles')
    <style>
    	.card-header-image {
			height: 60px; 
			width: 60px;
			top: 0;
			left: 50%;
			transform: translate(-50%, -50%);
    	}
    </style>
@endpush

@section('page-content')
    <div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb>
            <x-slot name="title">{{ __('Reporting Managers') }}</x-slot>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#">{{ __('Reporting Managers') }}</a>
                </li>
            </ul>
            <x-slot name="right">
                <div class="col-auto float-end ms-auto">
                   
                    <a href="javascript:void(0)" data-url="{{ route('reporting-manager.assign.view') }}" class="btn add-btn"
                        data-ajax-modal="true" data-size="lg" data-title="Assign Reporting Manager">
                        <i class="fa-solid fa-plus"></i> {{ __('Assign Project Manager') }}
                    </a> 
                </div>
            </x-slot>
        </x-breadcrumb>
        <!-- /Page Header -->

        <div class="row">
            @if (!empty($reportingManagers) && $reportingManagers->count() > 0)
                @foreach ($reportingManagers as $list)
                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex">
                        <div class="card w-100 position-relative">
                            <div class="card-body">

                            	<div class="position-absolute card-header-image mt-2">
                            		<img src="{{ !empty($list->avatar) ? uploadedAsset($list->avatar, 'users') : asset('images/user.jpg') }}" alt="Avatar" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                            	</div>
                                
                                <div class="dropdown dropdown-action profile-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            data-url="{{ route('reporting-manager.edit', ['reporting_manager' => ($list->id)]) }}"
                                            data-ajax-modal="true" data-title="Edit Project" data-size="lg">
                                            <i class="fa-solid fa-pencil m-r-5"></i>
                                            {{ __('Edit') }}
                                        </a>
                                    
                                   
                                        <a class="dropdown-item deleteBtn"
                                            data-route="{{ route('reporting-manager.delete', ['reporting_manager' => $list->id]) }}" data-title="Delete Reporting Manager"
                                            data-question="Are you sure you want to delete reporting manager?" href="javascript:void(0)">
                                            <i class="fa-regular fa-trash-can m-r-5"></i>
                                            {{ __('Delete') }}
                                        </a>
                                        
                                    </div>
                                </div>
                                
                                <h4 class="project-title mt-4">
                                    
                                    <a href="@activeCan('show-Employeeprofile') {{ route('employees.show', ['employee' => \Crypt::encrypt($list->id)]) }} @else # @endactiveCan" class="text-primary">{{ $list->fullname }}</a>
                                </h4>
                                <small class="block text-ellipsis m-b-15">
                                    <span class="text-xs">{{ $list->subordinates->count() ?? 0 }}</span> <span
                                        class="text-muted">{{ __('Members') }}</span>
                                </small>
                                <p class="text-muted">
                                    {{$list->email}}
                                </p>
                                <div class="">
                                	<div class="pro-deadline d-flex justify-content-between m-b-15">
                                        <div class="sub-title">
                                            {{ __('Department') }}:
                                        </div>
                                        <div class="text-muted">
                                            {{ @$list->employeeDetail->department->name }}
                                        </div>
                                    </div>

                                    <div class="pro-deadline d-flex justify-content-between m-b-15">
                                        <div class="sub-title">
                                            {{ __('Designation') }}:
                                        </div>
                                        <div class="text-muted">
                                            {{ @$list->employeeDetail->designation->name }}
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                @php
                                    $reportingManagersTeam = $list->subordinates;
                                @endphp
                                @if (!empty($reportingManagersTeam) && $reportingManagersTeam->count() > 0)
                                    <div class="project-members m-b-15">
                                        <div>{{ __('Team') }} :</div>
                                        <ul class="team-members">
                                            @foreach ($reportingManagersTeam as $team)
                                                <li>
                                                    <a href="@activeCan('show-Employeeprofile') {{ route('employees.show', ['employee' => \Crypt::encrypt($team->id)]) }} @else # @endactiveCan"
                                                        data-bs-toggle="tooltip" title="{{ $team->fullname }}">
                                                        <img src="{{ !empty($team->avatar) ? uploadedAsset($team->avatar, 'users') : asset('images/user.jpg') }}"
                                                            alt="{{ __('Avatar') }}">
                                                    </a>
                                                </li>
                                            @endforeach
                                            
                                        </ul>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection


@push('page-scripts')
    <!-- Page Js -->
    @vite([
        "resources/assets/css/ckeditor.css",
        "resources/js/ckeditor.js"
    ])
    <!-- /Page Js -->
@endpush