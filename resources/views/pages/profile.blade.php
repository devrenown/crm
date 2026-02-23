@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp
@push('page-styles')
    <style>
      .profile-info-left {
        border: none !important;
      }
      .text-primary {
        color: #307AFB !important;
      }
   </style>
@endpush

@section('page-content')
    <div class="content container-fluid">

        <!-- Page Header -->
        <x-breadcrumb>
            <x-slot name="title">{{ __('Profile') }}</x-slot>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ __('Profile') }}
                </li>
            </ul>
        </x-breadcrumb>
        <!-- /Page Header -->
        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#">
                                        <img
                                            src="{{ $user->avatar 
                                                ? \Storage::url(app('tenant')->domain . '/' . $user->id . '/' . $user->avatar) 
                                                : asset('images/user.jpg') }}"
                                            alt="User Image 1"
                                            style="object-fit: cover;">
                                    </a>


                                    {{-- <a href="#"><img
                                    src="{{ $user->avatar }}"
                                    alt="User Image 1" style="object-fit: cover;"></a> --}}
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">

                                    <div class="col-md-5 mb-3">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-3">{{ $user->fullname }}</h3>
                                            @if (!empty($employee->department_id))
                                              <h5 class="mb-2">{{ __('Department') }} : <span class="text-muted">{{ $employee->department->name ?? '' }}</span> </h5>
                                            @endif
                                            @if (!empty($employee->designation_id))
                                              <p class="mb-2">{{ __('Designation') }} : <span class="text-muted">{{ $employee->designation->name ?? '' }}</span> </p>
                                            @endif
                                            @if (!empty($employee->emp_id))
                                              <p class="mb-2">{{ __('Employee ID') }} : <span class="text-muted">{{ $employee->emp_id ?? '' }}</span> </p>
                                            @endif
                                            @if (!empty($employee->date_joined))
                                              <p class="mb-0">
                                                {{ __('Date of Join') }} : <span class="text-muted">{{ format_date($employee->date_joined) }}</span> 
                                              </p>
                                            @endif

                                            <div class="d-flex gap-2 justify-content-center justify-content-lg-start">
                                                <div class="staff-msg">
                                                    <a class="btn btn-primary btn-sm" href="apps/chat">{{ __('Send Message') }}</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            @if (!empty($user->phone))
                                                <li>
                                                    <div class="title">{{ __('Phone') }}:</div>
                                                    <div class="text"><a href="#">{{ $user->phoneNumber }}</a>
                                                    </div>
                                                </li>
                                            @endif
                                            @if (!empty($user->email))
                                                <li>
                                                    <div class="title">{{ __('Email') }}:</div>
                                                    <div class="text"><a href="">{{ $user->email }}</a></div>
                                                </li>
                                            @endif

                                            {{-- @if (!empty($user->address))
                                                <li>
                                                    <div class="title">{{ __('Address') }}:</div>
                                                    <div class="text"><a href="">{{ $user->address }}</a></div>
                                                </li>
                                            @endif --}}

                                            @if (!empty($user->gender))
                                                <li>
                                                    <div class="title">{{ __('Gender') }}:</div>
                                                    <div class="text"><a href="">{{ $user->gender == 1 ? 'Male' : ($user->gender == 2 ? 'Female' : 'Other') }}</a></div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                </div>

                                @if (!empty($user->reportingManager || $user->subReportingManager ))
                                    <div class="col-md-10">
                                        <div class="card py-3 px-4">

                                          <div class="row align-items-center mb-2">

                                            <div class="col-md-6">
                                              
                                              <div class="">
                                                {{ __('Reporting Manager') }} :
                                              </div>
                                              
                                            </div>

                                            <div class="col-md-6">
                                              <div class="bg-light py-2 px-4 mb-0">
                                                <p class="mb-0"><i class="fa-solid fa-user text-primary me-2 fs-5"></i>
                                                {{ $user->reportingManager->fullname ?? 'N/A' }}</p>
                                              </div>
                                            </div>

                                          </div>

                                          <div class="row align-items-center">
                                            <div class="col-md-6">
                                              <div class="">
                                                {{ __('Sub Reporting Manager') }} :
                                              </div>
                                            </div>

                                            <div class="col-md-6">
                                              <div class="bg-light py-2 px-4 mb-0">
                                                <p class="mb-0"><i class="fa-solid fa-user-group text-primary me-2 fs-5"></i>
                                                {{ $user->subReportingManager->fullname ?? 'N/A' }}</p>
                                              </div>
                                            </div>

                                          </div>

                                      </div>
                                    </div>

                                  @else
                                    <div class="col-md-10">
                                        <div class="card py-3 px-4 text-center">
                                          <p class="fs-5 fw-bold">No Reporting Manager Assigned !</p>
                                        </div>
                                    </div>
                                  @endif

                            </div>

                            <div class="pro-edit">
                                <a data-ajax-modal="true" data-title="Profile Information" data-size="lg" class="edit-icon"
                                    href="javascript:void(0)" data-url="{{ route('profile.edit') }}"><i
                                        class="fa-solid fa-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3 p-4 shadow-sm">

            <h4 class="mb-4">Change Password</h4>

            <form action="{{ route('profile.update-password') }}" method="POST" id="update-password">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="row">
                    <div class="mb-3 col-lg-6">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" required>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="new_password_confirmation" placeholder="Confirm new password" required>
                    </div>

                </div>

                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-primary btn-sm">Update Password</button>
                </div>

            </form>
        </div>


    </div>
@endsection
