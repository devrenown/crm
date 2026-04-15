@extends('layouts.app')

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
            
              <x-slot name="title">{{ __("Employee Profile") }}</x-slot>
            
            <div class="d-flex justify-content-between align-items-center w-100">
              <ul class="breadcrumb">
                  <li class="breadcrumb-item">
                      <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                  </li>
                  <li class="breadcrumb-item active">
                      {{ __('Profile') }}
                  </li>
              </ul>

              <button onclick="history.back()" class="btn btn-sm btn-primary">Back</button>
            </div>
        </x-breadcrumb>
        <!-- /Page Header -->
        <div class="card mb-0">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="profile-view">
                  <div class="profile-img-wrap">
                    <div class="profile-img">
                      <a href="#"
                        ><img
                        src="{{ !empty($user->avatar) ? asset('storage/' . $user->avatar) : Vite::asset('resources/assets/img/user.jpg') }}"
                        alt="User Image"
                      /></a>
                    </div>
                  </div>
                  <div class="profile-basic">
                    <div class="row">

                      <div class="col-md-5">
                        <div class="profile-info-left">
                          <h3 class="user-name m-t-0 mb-3">{{ $user->fullname }}</h3>
                          @if (!empty($user->employeeDetail->department_id))
                          <h5 class="mb-2">{{ __('Department') }} : <span class="text-muted">{{ $user->employeeDetail->department->name ?? '' }}</span> </h5>
                          @endif
                          @if (!empty($user->employeeDetail->designation_id))
                          <p class="mb-2">{{ __('Designation') }} : <span class="text-muted">{{ $user->employeeDetail->designation->name ?? '' }}</span> </p>
                          @endif
                          @if (!empty($user->employeeDetail->emp_id))
                          <p class="mb-2">{{ __('Employee ID') }} : <span class="text-muted">{{ $user->employeeDetail->emp_id ?? '' }}</span> </p>
                          @endif
                          @if (!empty($user->employeeDetail->date_joined))
                          <p class="mb-0">
                            {{ __('Date of Join') }} : <span class="text-muted">{{ format_date($employee->date_joined) }}</span> 
                          </p>
                          @endif

                          <div class="staff-msg">
                            <a class="btn btn-primary btn-sm" href="#"
                              >{{ __('Send Message') }}</a
                            >
                            @if(@$user->onboardingInvitation->is_sent == 1 && $user->is_onboarding_complete == 0)
                            <button class="btn btn-primary btn-sm" id="apr-onboarding">Approve Onboarding</button>
                            @endif
                          </div>
                          <br>
                        </div>
                      </div>

                      <div class="col-md-7">
                        <ul class="personal-info">
                          @if (!empty($user->phone))
                              <li>
                                  <div class="title">{{ __('Phone') }}:</div>
                                  <div class="text"><a href="#">{{ $user->phoneNumber }}</a></div>
                              </li>
                          @endif
                          @if (!empty($user->email))
                              <li>
                                  <div class="title">{{ __('Email') }}:</div>
                                  <div class="text">{{ $user->email }}</div>
                              </li>
                          @endif

                          @if (!empty($employee->dob))
                              <li>
                                  <div class="title">{{ __('Date Of Birth') }}:</div>
                                  <div class="text">{{ format_date($employee->dob) }}</div>
                              </li>
                          @endif

                          @if (!empty($user->gender))
                              <li>
                                  <div class="title">{{ __('Gender') }}:</div>
                                  <div class="text">{{ $user->gender == 1 ? 'Male' : ($user->gender == 2 ? 'Female' : 'Other') }}</div>
                              </li>
                          @endif

                          @if($user->is_onboarding_complete == 0)

                            @if (@$user->onboardingInvitation->is_sent == 1)
                            <button class="btn btn-primary btn-sm" id="send-invite">Reinvite for Onboarding</button>
                            @else
                            <button class="btn btn-primary btn-sm" id="send-invite">Invite for Onboarding</button>
                            @endif
                          @endif
                        </ul>
                      </div>

                      @if (!empty($user->reportingManager || $user->subReportingManager ))
                        <div class="col-md-10">
                            <div class="card py-3 px-4">

                              <div class="row align-items-center mb-2">

                                <div class="col-md-6">
                                  
                                  <div class="">
                                    {{ __('Reporting') }} :
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
                                    {{ __('Co Reporting') }} :
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
                  </div>
                  <div class="pro-edit">
                    @activeCan('edit-employee')
                    <a href="javascript:void(0)" data-url="{{ route('employees.edit', ['employee' => \Crypt::encrypt($user->id)]) }}" data-ajax-modal="true" 
                      data-title="Edit Employee" data-size="lg" data-bs-toggle="tooltip" data-bs-title="{{ __('Edit profile') }}"><i class="fa-solid fa-pencil"></i
                    ></a>
                    @endactiveCan
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card tab-box">
          <div class="row user-tabs">
            <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
              <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item">
                  <a
                    href="#emp_profile"
                    data-bs-toggle="tab"
                    class="nav-link active"
                    >{{ __('Profile') }}</a>
                </li>
                @activeRoleIn(['Admin', 'Hr'])
                <li class="nav-item">
                  <a
                    href="#bank_statutory"
                    data-bs-toggle="tab"
                    class="nav-link"
                    >{{ __('Bank & Statutory') }}
                  </a>
                </li>
                @endactiveRoleIn
                @if (!empty($user->assets) && ($user->assets->count() > 0))
                <li class="nav-item">
                  <a
                    href="#emp_assets"
                    data-bs-toggle="tab"
                    class="nav-link"
                    >{{ __('Assets') }}</a>
                </li>
                @endif

                @activeCan('view-assignedRole')
                  <li>
                    <a href="#emp_roles" data-bs-toggle="tab" class="nav-link">{{ __('Roles') }}</a>
                  </li>
                @endactiveCan
              </ul>
            </div>
          </div>
        </div>

        <div class="tab-content">
          <!-- Profile Info Tab -->
          <div
            id="emp_profile"
            class="pro-overview tab-pane fade show active">
            <div class="row">
                
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">

                      {{ __('Personal Informations') }}
                      {{-- <a href="javascript:void(0)" data-url="{{ route('employee.personal-info', $employee->id) }}"
                      class="edit-icon" data-title="{{ __('Personal Information') }}"
                      data-ajax-modal="true" data-size="lg"
                      
                      <i class="fa-solid fa-pencil"></i>
                      </a> --}}
                      
                      @activeCan('edit-employee')
                      <a href="{{ route('employee.personal-info', $employee->id) }}" class="edit-icon me-1">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      @endactiveCan
                      
                    </h3>
                    <ul class="personal-info">

                      @if (!empty($user->company))
                        <li>
                          <div class="title">{{ __('Company') }}</div>
                          <div class="text">{{ @$user->companyDetail->name }}</div>
                        </li>
                      @endif

                      @if (isset($employee->designation_id) && isset($employee->designation))
                        <li>
                          <div class="title">{{ __('Designation') }}</div>
                          <div class="text">{{ @$employee->designation->name }} {{ $employee->department_id ? '(' . @$employee->department->name . ')' : 'N/A' }}</div>
                        </li>
                      @endif

                      @if (!empty($employee->passport_no))
                        <li>
                          <div class="title">{{ __('Passport No.') }}</div>
                          <div class="text">{{ $employee->passport_no }}</div>
                        </li>
                      @endif
                      @if (!empty($employee->passport_expiry_date))
                      <li>
                        <div class="title">{{ __('Passport Exp Date.') }}</div>
                        <div class="text">{{ format_date($employee->passport_expiry_date) }}</div>
                      </li>
                      @endif
                      @if (!empty($employee->passport_tel))
                      <li>
                        <div class="title">{{ __('Tel') }}</div>
                        <div class="text">{{ $employee->passport_tel }}</a></div>
                      </li>
                      @endif
                      @if (!empty($employee->nationality))
                      <li>
                        <div class="title">{{ __('Nationality') }}</div>
                        <div class="text">{{ $employee->nationality }}</div>
                      </li>
                      @endif
                      @if (!empty($employee->religion))
                      <li>
                        <div class="title">{{ __('Religion') }}</div>
                        <div class="text">{{ $employee->religion }}</div>
                      </li>
                      @endif
                      @if (!empty($employee->marital_status))
                      <li>
                        <div class="title">{{ __('Marital status') }}</div>
                        <div class="text">{{ $employee->marital_status }}</div>
                      </li>
                      @endif
                      @if (!empty($employee->spouse_occupation))
                      <li>
                        <div class="title">{{ __('Employment of spouse') }}</div>
                        <div class="text">{{ $employee->spouse_occupation }}</div>
                      </li>
                      @endif
                      @if (!empty($employee->no_of_children))
                      <li>
                        <div class="title">{{ __('No. of children') }}</div>
                        <div class="text">{{ $employee->no_of_children }}</div>
                      </li>
                      @endif
                    </ul>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      {{ __('Identification') }}
                      
                      @activeCan('edit-employee')
                      <a href="javascript:void(0)" data-url="{{ route('employee.identity', $employee->id) }}" class="edit-icon me-1" data-title="{{ __('Employee Identity') }}" data-ajax-modal="true" data-size="lg" data-bs-toggle="tooltip" data-bs-title="Identity">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      @endactiveCan
                      
                    </h3>
                    
                    <div class="row">
                        <h5>Current Address:</h5>
                        
                        <div class="col-6">City</div>
                        <div class="col-6 muted">{{ $employee->current_city ?? '' }}</div>
                    </div>
                    
                    <div class="row">
                        <h5>Permanent Address:</h5>
                        
                        <div class="col-6">City</div>
                        <div class="col-6 muted">{{ $employee->permanent_city ?? '' }}</div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
                
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      {{ __('Education Informations') }}

                      @activeCan('edit-employee')
                      <a
                      href="javascript:void(0)" data-url="{{ route('employee.education', $employee->id) }}"
                        class="edit-icon" data-title="{{ __('Education Information') }}"
                        data-ajax-modal="true" data-size="lg"
                        data-bs-toggle="tooltip" data-bs-title="Education"
                        ><i class="fa-solid fa-pencil"></i>
                      </a>
                      @endactiveCan
                    </h3>
                    <div class="experience-box">
                      <ul class="experience-list">
                        @if(!empty($employee->education) && $employee->education->count() > 0)
                          @foreach ($employee->education as $education)
                          <li>
                            <div class="experience-user">
                              <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                              <div class="timeline-content">

                                <div class="text-black fw-bold">{{ $education->course }} <span class="text-muted small"> ( {{$education->institution}} ) </span></div>
                                <span class="time">{{ $education->start_date }} - {{ $education->end_date }}</span>
                                @if (!empty($education->file))

                                    @php
                                        $signedUrl = URL::signedRoute(
                                            'secure.document.view',
                                            [
                                                'path'     => encrypt($education->file), // must be full stored path
                                                'mime'     => $education->document_mime ?? 'application/pdf',
                                                'filename' => basename($education->file),
                                                'mode'     => 'watermark'
                                            ],
                                            now()->addMinutes(5)
                                        );
                                    @endphp

                                    <a href="javascript:void(0);"
                                      onclick="openSecureDocument('{{ $signedUrl }}')"
                                      class="d-block mt-1">
                                        {!! \App\Helpers\DocumentStatus::statusBadge($education->status) !!}
                                        {{ __('View File') }}
                                    </a>

                                @endif
                              </div>
                            </div>
                          </li>
                          @endforeach
                        @endif
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      {{ __('Work Experience') }}
                      {{-- <a
                      href="javascript:void(0)" data-url="{{ route('employee.experience', $employee->id) }}"
                          class="edit-icon" data-title="{{ __('Working Experience Information') }}"
                          data-ajax-modal="true" data-size="lg"
                          data-bs-toggle="tooltip" data-bs-title="Working Experience"
                          ><i class="fa-solid fa-pencil"></i>
                      </a> --}}

                      @activeCan('edit-employee')
                      <a
                      href="{{ route('employee.experience', $employee->id) }}" 
                          class="edit-icon"><i class="fa-solid fa-pencil"></i>
                      </a>
                      @endactiveCan
                    </h3>
                    <div class="experience-box">
                      <ul class="experience-list">
                          @if (!empty($employee->workExperience))
                              @foreach ($employee->workExperience as $experience)
                              <li>
                                <div class="experience-user">
                                  <div class="before-circle"></div>
                                </div>
                                <div class="experience-content">
                                  <div class="timeline-content">
                                    <span class="name">{{ $experience->position .__(" At "). $experience->company}}</span>
                                    <span class="time"
                                      >{{ format_date($experience->start_date) }} - {{ format_date($experience->end_date) }} ({{ $experience->dateDifference }}) </span>
                                      @if (!empty($experience->file))
                                          <a href="{{ uploadedAsset($experience->file,'employees/work-experience') }}" target="_blank" rel="noopener noreferrer">{{ __('View File') }}</a>
                                      @endif
                                  </div>
                                </div>
                              </li>
                              @endforeach
                          @endif
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      {{ __('Family Informations') }}
                      @activeCan('edit-employee')
                      <a href="javascript:void(0)" data-url="{{ route('family-information.create', ['user' => $user->id]) }}"
                          class="edit-icon" data-title="{{ __('Add Family Information') }}"
                          data-ajax-modal="true" data-size="lg"
                          data-bs-toggle="tooltip" data-bs-title="Add Family Member"
                          >
                          <i class="fa-solid fa-plus"></i>
                      </a>
                      @endactiveCan
                    </h3>
                    <div class="table-responsive">
                      <table class="table table-nowrap">
                        <thead>
                          <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Relationship') }}</th>
                            <th>{{ __('Date of Birth') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Action') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if ($user->has('family'))
                              @foreach ($user->family as $member)
                              <tr>
                                  @if (!empty($member->picture))
                                  <td>
                                      {!! Spatie\Menu\Laravel\Html::userAvatar($member->name, !empty($member->picture) ? uploadedAsset($member->picture): Vite::asset('resources/assets/img/user.jpg')) !!}
                                  </td>
                                  @else
                                  <td>{{ $member->name }}</td>
                                  @endif
                                  <td>{{ $member->relationship }}</td>
                                  <td>{{ format_date($member->dob) }}</td>
                                  <td>{{ $member->phone }}</td>
                                  <x-table-action class="position-absolute">
                                      <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('family-information.edit', $member->id) }}" data-ajax-modal="true" 
                                          data-title="{{ __('Edit Family Member') }}" data-size="lg" data-bs-toggle="tooltip" data-bs-title="{{ __("Edit Family Member Information") }}">
                                          <i class="fa-solid fa-pencil m-r-5"></i>
                                          {{ __('Edit') }}
                                      </a>
                                      <a class="dropdown-item deleteBtn" data-route="{{ route('family-information.destroy', $member->id) }}"
                                          data-title="{{ __('Delete User') }}" data-bs-toggle="tooltip" data-bs-title="{{ __('Delete Family Member') }}" data-question="{{ __('Are you sure you want to delete?') }}"
                                          href="javascript:void(0)" data-bs-toggle="tootip" data-bs-title="{{ __('Delete Family Member') }}">
                                          <i class="fa-regular fa-trash-can m-r-5"></i>
                                          {{ __('Delete') }}
                                      </a>
                                  </x-table-action>

                                </tr>
                              @endforeach
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      {{ __('Emergency Contact') }}
                      @activeCan('edit-employee')
                      <a href="javascript:void(0)" data-url="{{ route('employee.emergency-contacts', $employee->id) }}"
                          class="edit-icon" data-title="{{ __('Emergency Contacts') }}"
                          data-ajax-modal="true" data-size="lg"
                          >
                          <i class="fa-solid fa-pencil"></i>
                      </a>
                      @endactiveCan
                    </h3>
                    <h5 class="section-title">{{ __('Primary') }}</h5>
                    @php
                        $primary_contact = $employee->emergency_contacts['primary'] ?? null;
                        $secondary_contact = $employee->emergency_contacts['secondary'] ?? null;
                    @endphp
                    @if (!empty($primary_contact))
                    <ul class="personal-info">
                      <li>
                        <div class="title">{{ __('Name') }}</div>
                        <div class="text">{{ $primary_contact['name'] }}</div>
                      </li>
                      <li>
                        <div class="title">{{ __('Relationship') }}</div>
                        <div class="text">{{ $primary_contact['relationship'] }}</div>
                      </li>
                      <li>
                        <div class="title">{{ __('Phone') }}</div>
                        <div class="text">{{ $primary_contact['phone'] }}</div>
                      </li>
                      <li>
                        <div class="title">{{ __('Address') }}</div>
                        <div class="text">{{ $primary_contact['address'] }}</div>
                      </li>
                    </ul>
                    @endif
                    @if (!empty($secondary_contact))
                    <hr />
                    <h5 class="section-title">{{ __('Secondary') }}</h5>
                    <ul class="personal-info">
                      <li>
                        <div class="title">Name</div>
                        <div class="text">{{ $secondary_contact['name']  }}</div>
                      </li>
                      <li>
                        <div class="title">Relationship</div>
                        <div class="text">{{ $secondary_contact['relationship']  }}</div>
                      </li>
                      <li>
                        <div class="title">Phone</div>
                        <div class="text">{{ $secondary_contact['phone'] }}</div>
                      </li>
                    </ul>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /Profile Info Tab -->

          @activeRoleIn(['Admin', 'Hr'])
          <!-- Bank Statutory Tab -->
          <div class="tab-pane fade" id="bank_statutory">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">{{ __('Basic Salary Information') }}</h3>
                <form action="{{ route('employee.salary-setting', $employee->id) }}" method="post">
                  @csrf
                  <div class="row">
                    <input type="hidden" name="salary_detail_id" value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->id : '' }}">
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"
                          >{{ __('Salary basis') }}
                          <span class="text-danger">*</span></label
                        >
                        <select class="form-control" name="basis">
                          <option value="">{{ __('Select salary basis type') }}</option>
                          @foreach (\App\Enums\Payroll\SalaryType::cases() as $item)
                              <option {{ (!empty($employee->salaryDetails) && $employee->salaryDetails->basis === $item) ? 'selected': '' }} value="{{ $item->value }}">{{ $item->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"
                          >{{ __('Salary amount') }}
                        </label>
                        <div class="input-group">
                          <span class="input-group-text">{{ LocaleSettings('currency_symbol') }}</span>
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Type your salary amount"
                            name="base_salary"
                            value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->base_salary : 0.00 }}"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('Payment type') }}</label>
                        <select class="form-control" name="payment_method">
                          <option value="">{{ __('Select payment type') }}</option>
                          @foreach (\App\Enums\Payroll\PaymentMethod::cases() as $item)
                            <option {{ !empty($employee->salaryDetails) && $employee->salaryDetails->payment_method === $item ? 'selected': '' }} value="{{ $item->value }}">{{ $item->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  @if (!empty(SalarySettings('enable_provident_fund')))
                  <hr />
                  <h3 class="card-title">{{ __('PF Information') }}</h3>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('PF contribution') }}</label>
                        <select class="form-control" name="pf_contribution">
                          <option value="">{{ __('Select To Enable') }}</option>
                          <option {{ (!empty($employee->salaryDetails) && $employee->salaryDetails->pf_contribution == '1') ? 'selected': '' }} value="1">{{ __('Yes') }}</option>
                          <option {{ (!empty($employee->salaryDetails) && $employee->salaryDetails->pf_contribution == '0') ? 'selected': '' }} value="0">{{ __('No') }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('PF No.') }}</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="pf_number"
                          value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->pf_number: '' }}"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('Additional Rate') }}</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="additional_pf_rate"
                          value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->additional_pf: '' }}"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('Total rate') }}</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="total_pf_rate"
                          value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->total_pf : '' }}"
                        />
                      </div>
                    </div>
                  </div>
                  @endif
                  @if (!empty(SalarySettings('enable_esi_fund')))  
                  <hr />
                  <h3 class="card-title">{{ __('ESI Information') }}</h3>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('Enable ESI contribution') }}</label>
                        <select class="form-control" name="esi_contribution">
                          <option value="">{{ __('Select To Enable') }}</option>
                          <option {{ !empty($employee->salaryDetails) && $employee->salaryDetails->esi_contribution == '1' ? 'selected': '' }} value="1">{{ __('Yes') }}</option>
                          <option {{ !empty($employee->salaryDetails) && $employee->salaryDetails->esi_contribution == '0' ? 'selected': '' }} value="0">{{ __('No') }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('ESI No.') }}</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="esi_number"
                          value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->esi_number: '' }}"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('Additional Rate') }}</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="additional_esi_rate"
                          value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->additional_esi: '' }}"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label">{{ __('Total rate') }}</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="total_esi_rate"
                          value="{{ !empty($employee->salaryDetails) ? $employee->salaryDetails->total_additional_esi_rate: '' }}"
                        />
                      </div>
                    </div>
                  </div>
                  @endif

                  <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit">
                      {{ __('Save') }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /Bank Statutory Tab -->
          @endactiveRoleIn

          <!-- Assets -->
          <div class="tab-pane fade" id="emp_assets">
              <livewire:employee-asset :user="$user"/>
          </div>
          <!-- /Assets -->

          @activeCan('view-assignedRole')
          <!-- Bank Statutory Tab -->
          <div class="tab-pane fade" id="emp_roles">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">{{ __('Assign Roles') }}</h3>
                <form action="{{ route('employees.assignRoles') }}" method="post" id="assignRoleForm">
                  @csrf
                  <div class="row">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"
                          >{{ __('Roles') }}
                          <span class="text-danger">*</span></label
                        >
                        <select class="form-control select" name="roles[]" data-placeholder="{{ __('Select Roles') }}" multiple required>
                          @foreach ($roles as $key => $role)
                              <option {{ $user->roles->contains('id', $role->id) ? 'selected': '' }} value="{{ $role->name }}">
                                {{ $role->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                  </div>

                  @activeCan('edit-assignedRole')
                  <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit">
                      {{ __('Assign Roles') }}
                    </button>
                  </div>
                  @endactiveCan

                </form>
              </div>
            </div>
          </div>
          <!-- /Bank Statutory Tab -->
          @endactiveCan

        </div>

    </div>


@endsection

@push('page-scripts')

<script>
  $('#apr-onboarding').on('click', function() {
    let isConfirm = confirm('Are you sure you want to approve this ?');
    if(!isConfirm) return;

    $.ajax({
      url: "{{ route('approve-onboarding') }}",
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        user_id: '{{ $user->id }}'
      },
      success: function(res) {
        Toastify({
            text: res.msg,
            className: 'success',
        }).showToast();

        window.location.reload();
      },
      error: function(xhr) {
        console.log(xhr.responseText())
      }
    })

  });

  $('#send-invite').on('click', function() {
    let isConfirm = confirm('Are you sure you want to Send Invite ?');
    if(!isConfirm) return;

    $.ajax({
      url: "{{ route('send-onboarding-invitation') }}",
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        user_id: '{{ $user->id }}',
        invitation_id: "{{ @$user->onboardingInvitation->id ?? '' }}"
      },
      success: function(res) {
        Toastify({
            text: res.msg,
            className: 'success',
        }).showToast();

        window.location.reload();
      },
      error: function(xhr) {
        console.log(xhr.responseText())
      }
    })

  });

  $(document).on('submit', '#assignRoleForm', function (e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    $.ajax({
        url: form.action,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,

        success: function (res) {
            if (res.success) {
                alert(res.message);
                window.location.href = window.location.pathname + '#emp_roles';
            } else {
                alert('Something went wrong!');
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
  });

</script>

@endpush


