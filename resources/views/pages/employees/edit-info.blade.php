@extends('layouts.app')

@php
    use App\Models\Company;
    $companySettings = app(\App\Settings\CompanySettings::class);
    $companies = Company::all();
    $companies = count($companies) > 0 ? $companies : $companySettings->name;
@endphp

@section('page-content')

<div class="content container-fluid">

    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-4">Edit {{ $employee->firstname }}'s Personal Information</h4>
        <a onclick="window.history.back()" class="btn btn-primary btn-sm">Back</a>
    </div>

    <form action="{{ route('onboard.personal-details') }}" id="employeeForm" method="POST" enctype="multipart/form-data">

        @csrf
        <input type="hidden" value="{{ $employee->id }}" name="employee_id" />
        <input type="hidden" value="{{ $employeeDetail->id }}" name="employee_detail_id" />

        {{-- Basic User Info --}}
        <div class="row">
            <div class="col-md-4">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" name="first_name" id="firstname" 
                       value="{{ old('first_name', $employee->firstname) }}" 
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="lastname" 
                       value="{{ old('last_name', $employee->lastname) }}" 
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" 
                       value="{{ old('email', $employee->email) }}" 
                       class="form-control">
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-4">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="contact" id="phone" 
                       value="{{ old('contact', $employee->phone) }}" 
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="1" {{ $employee->gender == 1 ? 'selected' : '' }}>Male</option>
                    <option value="2" {{ $employee->gender == 2 ? 'selected' : '' }}>Female</option>
                    <option value="3" {{ $employee->gender == 3 ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="avatar" class="form-label">Profile Picture</label>
                <input type="file" name="photo" id="avatar" class="form-control">
                <input type="hidden" name="old_image" value="{{ @$employee->avatar }}" />
                @if($employee->avatar)
                    <img src="{{ asset('storage/'.$employee->avatar) }}" 
                         alt="Avatar" class="img-thumbnail mt-2" width="100">
                @endif
            </div>
        </div>

        {{-- Employee Details --}}

        <hr class="my-4">

        <div class="row">
            <div class="col-md-4">
                <label for="emp_id" class="form-label">Employee ID</label>
                <input type="text" name="emp_id" id="emp_id" 
                       value="{{ old('emp_id', $employeeDetail->emp_id) }}" 
                       class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label for="company" class="form-label">Company *</label>
                @if (is_array($companies) && count($companies) > 0)
                    <select name="company" id="company" class="form-select form-control required">
                        <option selected disabled>--Select Company--</option>

                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ $company->id == @$employee->company ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                @else
                <input type="text" name="company" id="company" 
                       value="{{ old('company', $companies) }}" 
                       class="form-control" readonly>
                @endif
            </div>

            <div class="col-md-4">
                <label for="designation" class="form-label">Designation *</label>
                <select name="designation" id="designation" class="form-select form-control required">
                    @php
                        $designationList = App\Models\Designation::list();
                    @endphp

                    @foreach ($designationList as $designation)
                        <option value="{{ $designation->id }}" {{ @$employeeDetail->designation_id == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label for="dob">Date of Birth </label>
                <input type="date" id="dob" name="dob" value="{{ old('dob', $employeeDetail->dob ?? '') }}"
                    class="form-control">
            </div>

            <div class="col-md-4">
                <label for="blood_group" class="form-label">Blood Group</label>
                <select name="blood_group" id="blood_group" class="form-select form-control">
                    <option value="" selected disabled>--Select Blood Group--</option>
                    <option value="A+" {{ @$employeeDetail->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A-" {{ @$employeeDetail->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B+" {{ @$employeeDetail->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="B-" {{ @$employeeDetail->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="AB+" {{ @$employeeDetail->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="AB-" {{ @$employeeDetail->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                    <option value="O+" {{ @$employeeDetail->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="O-" {{ @$employeeDetail->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="experience" class="form-label">Experience</label>
                <input type="text" name="total_exp" id="experience" 
                       value="{{ old('total_exp', $employeeDetail->total_exp) }}" 
                       class="form-control">
            </div>
        </div>

        <div class="row mt-3">
             <div class="col-md-4">
                 <label for="joining_date">Joining Date </label>

                <input id="joining_date" name="joining_date" value="{{ old('joining_date',  optional($employeeDetail->date_joined)->format('Y-m-d')) }}" type="date"
                class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Marital Status</label>
                <select class="select form-control" name="marital_status">
                    <option value="" disabled selected>-- Marital Status --</option>
                    @foreach (\App\Enums\MaritalStatus::cases() as $item)
                        <option value="{{ $item->value }}" {{ (!empty($employeeDetail->marital_status) && ($employeeDetail->marital_status->value == $item->value)) ? 'selected': ''}}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="no_of_children" class="form-label">Number of Children</label>
                <input type="number" name="no_of_children" id="no_of_children" 
                       value="{{ old('no_of_children', $employeeDetail->no_of_children) }}" 
                       class="form-control">
            </div>
        </div>

        <div class="row mt-3">

            <div class="col-md-4">
                <label for="how_know">How did you know about the opening ? </label>
                <select name="how_know" id="how_know" class="form-select form-control required">
                    <option selected disabled>--Select--</option>
                    <option value="1" {{ @$employeeDetail->know_about == 1 ? 'selected' : '' }}>Company Website</option>
                    <option value="2" {{ @$employeeDetail->know_about == 2 ? 'selected' : '' }}>Friends or Reference</option>
                    <option value="3" {{ @$employeeDetail->know_about == 3 ? 'selected' : '' }}>Job Portal</option>
                    <option value="4" {{ @$employeeDetail->know_about == 4 ? 'selected' : '' }}>Social Media</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">

            @if(!empty($employeeDetail->major_illness))
            <div class="col-md-4">
                <label for="major_illness" class="form-label">Major Illness</label>
                <input type="text" name="specification" id="major_illness" 
                       value="{{ old('specification', $employeeDetail->major_illness) }}" 
                       class="form-control">
            </div>
            @endif

            @if(!empty($employeeDetail->ref_emp_name))
            <div class="col-md-4">
                <label for="ref_emp_name" class="form-label">Refrence Employee Name</label>
                <input type="text" name="ref_emp_name" id="ref_emp_name" 
                       value="{{ old('ref_emp_name', $employeeDetail->ref_emp_name) }}" 
                       class="form-control">
            </div>
            @endif

            

            @if(!empty($employeeDetail->ref_emp_id))
            <div class="col-md-4">
                <label for="ref_emp_id" class="form-label">Refrence Employee ID</label>
                <input type="text" name="ref_emp_id" id="ref_emp_id" 
                       value="{{ old('ref_emp_id', $employeeDetail->ref_emp_id) }}" 
                       class="form-control text-uppercase">
            </div>
            @endif
        </div>

        <hr class="my-4">

        <h4 class="my-4">Bank Details</h4>

        <div class="row mt-3">
            <div class="col-md-4">
                <label for="bank" class="form-label">Bank</label>
                <input type="text" name="bank_name" id="bank" 
                       value="{{ old('bank_name', $employeeDetail->bank) }}" 
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label for="account" class="form-label">Account Number</label>
                <input type="text" name="account_number" id="account"
                   value="{{ old('account_number', !empty($employeeDetail->account) ? decrypt($employeeDetail->account) : '') }}"
                   class="form-control">
            </div>

            <div class="col-md-4">
                <label for="ifsc" class="form-label">IFSC Code</label>
                <input type="text" name="ifsc_code" id="ifsc" 
                       value="{{ old('ifsc_code', $employeeDetail->ifsc) }}" 
                       class="form-control text-uppercase">
            </div>
        </div>

        <div class="row mt-3">

            <div class="col-md-4">
                <label for="branch" class="form-label">Branch</label>
                <input type="text" name="branch_address" id="branch" 
                       value="{{ old('branch_address', $employeeDetail->branch) }}" 
                       class="form-control">
            </div>

            <div class="col-md-8 document-block mb-2">

                <div class="row">

                    <div class="col-md-6">
                        <label for="bank_document" class="form-label">Bank Document (Passbook or Cancelled Cheque)</label>
                        <input id="bank_document" name="bank_document" type="file" accept="image/*,application/pdf"
                            class="form-control">

                        <input type="hidden" name="old_bank_document" id="old_bank_document" value="{{ $employeeDetail->bank_document ?? '' }}">

                        @if(!empty($employeeDetail->bank_document))
                            @php
                                $signedUrl = URL::signedRoute(
                                    'secure.document.view',
                                    [
                                        'path'     => encrypt($employeeDetail->bank_document),
                                        'mime'     => $employeeDetail->bank_document_mime,
                                        'filename' => basename($employeeDetail->bank_document),
                                        'mode'     => 'clean'
                                    ],
                                    now()->addMinutes(5)
                                );
                            @endphp


                            <div class="row">
                                <div class="col-md-6">
                                    <a href="javascript:void(0);"
                                        onclick="openSecureDocument('{{ $signedUrl }}')"
                                        class="d-block mt-1 view-edu-file">
                                        {!! \App\Helpers\DocumentStatus::statusBadge($employeeDetail->documentAction?->status) !!}
                                        View File
                                    </a>
                                </div>
                            </div>
                        @endif
                         <small class="text-muted d-block">
                            <span class="text-danger">*</span>
                            Allowed jpg,jpeg,png,webp,pdf &nbsp; max: 2MB
                        </small>
                    </div>

                    <div class="col-md-6">
                        
                        <label class="form-label">Document Status</label>
                        <select class="form-control form-select status-dropdown" name="bank_document_status">
                            <option value="0" {{ $employeeDetail->documentAction?->status == 0 ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ $employeeDetail->documentAction?->status == 1 ? 'selected' : '' }}>Verified</option>
                            <option value="2" {{ $employeeDetail->documentAction?->status == 2 ? 'selected' : '' }}>Rejected</option>
                        </select>

                        <div class="remarks-container" style="{{ $employeeDetail->documentAction?->status == 2 ? '' : 'display:none;' }}">
                            <span>Remarks</span> 
                            <input type="text" value="{{ $employeeDetail->documentAction?->remark ?? '' }}" name="bank_document_remarks" class="form-control">
                        </div>

                        @if ($employeeDetail->actionBy)
                        <small class="text-muted">By {{ $employeeDetail->actionBy?->fullname .' ('. tz($employeeDetail->documentAction?->action_at, 'd M Y' ) . ')'}}</small>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>

</div>

@endsection

<!--onboard.personal-details-->

@push('page-scripts')

    <script>
        $(document).ready(function () {
            $('#employeeForm').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);

                let actionUrl = form.attr('action');
                let formData = new FormData(this);

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    beforeSend: function() {
                        form.find('button[type=submit]').prop('disabled', true).text('Updating...');
                    },

                    success: function (response) {
                        alert('Employee updated successfully! ✅');
                        window.location.href = "{{ route('employees.index') }}";
                    },

                    error: function (xhr) {

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';

                            $.each(errors, function (key, value) {
                                errorMessage += value[0] + "\n";
                            });

                            alert("Validation Error:\n" + errorMessage);
                        } else {
                            alert("Something went wrong ❌");
                        }
                    },

                    complete: function() {
                        form.find('button[type=submit]').prop('disabled', false).text('Update');
                    }
                });
            });
        });

    </script>

@endpush



