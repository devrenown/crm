@extends('layouts.app')

@push('page-style')
    {{-- Add your CSS here if needed --}}
@endpush

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
                    <img src="{{ asset('storage/users/'.$employee->avatar) }}" 
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
                <select name="company" id="company" class="form-select form-control required">
                    <option selected disabled>--Select Company--</option>
                    @php
                        use App\Models\Company;
                        $companies = Company::all();
                    @endphp

                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ $company->id == @$employee->company ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
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
                <div class="cal-icon">
                    <input id="dob" name="dob" value="{{ old('dob', $employeeDetail->dob ?? '') }}" type="text"
                        class="form-control datepicker">
                </div>
            </div>

            <div class="col-md-4">
                <label for="blood_group" class="form-label">Blood Group *</label>
                <select name="blood_group" id="blood_group" class="form-select form-control required">
                    <option selected disabled>--Select Blood Group--</option>
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
                <div class="cal-icon">
                    <input id="joining_date" name="joining_date" value="{{ old('joining_date', $employeeDetail->date_joined ?? '') }}" type="text"
                        class="form-control datepicker">
                </div>
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
                e.preventDefault(); // stop normal form submit
        
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
                        // Optional: disable button or show loader
                        form.find('button[type=submit]').prop('disabled', true).text('Updating...');
                    },
                    success: function (response) {
                        // Show success notification
                        alert('Employee updated successfully! ✅');
        
                        // Optionally redirect back to employee list
                        window.location.href = "{{ route('employees.index') }}";
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
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

