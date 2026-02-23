@extends('layouts.app')

@section('page-content')
    <div class="content container-fluid">

        @php
            $docFields = [
                'offer_letter' => 'Offer Letter',
                'appointment_letter' => 'Appointment Letter',
                'experience_letter' => 'Experience Letter',
                'relieving_letter' => 'Relieving Letter',
                'increment_letter' => 'Increment Letter',
                'salary_slip' => 'Salary Slip',
                'bank_statement' => 'Bank Statement',
            ];
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Employment Details</h4>
            <a onclick="window.history.back()" class="btn btn-primary btn-sm">Back</a>
        </div>

        <form id="employmentForm" action="{{ route('onboard.employement') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="emp_detail_id" value="{{ $employeeDetail->id }}">

            <div id="employment-wrapper">
                @forelse($experiences as $index => $exp)
                    <div class="employment-item border rounded p-3 mb-3">

                        <input type="hidden" name="exp_ids[]" value="{{ $exp->id }}">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Employment</h5>

                            <span class="delete-icon filled-delete"
                                data-route="{{ route('onboard.deleteEmployement', ['employement_id' => $exp->id]) }}" style="cursor: pointer;">
                                <i class="fa-regular fa-trash-can"></i>
                            </span>
                           
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Job Position </label>
                                <input type="text" name="positions[]" class="form-control" value="{{ $exp->position }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Name </label>
                                <input type="text" name="companies[]" class="form-control" value="{{ $exp->company }}">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <x-form.input-block class="mb-2">
                                    <x-form.label class="mb-0">
                                        {{ __('Reporting Manager / HR Name*') }}</x-form.label>
                                    <x-form.input class="floating person_name" type="text"
                                        name="person_names[]" value="{{ $exp->person_name ?? '' }}" />
                                </x-form.input-block>
                            </div>

                            <div class="col-md-6">
                                <x-form.input-block class="mb-2">
                                    <x-form.label class="mb-0">
                                        {{ __('Reporting Manager / HR Contact Number*') }}</x-form.label>
                                    <input type="text" name="person_contacts[]" class="form-control"
                                    value="{{ $exp->person_contact }}">
                                </x-form.input-block>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Employee ID</label>
                                <input type="text" name="emp_ids[]" class="form-control text-uppercase" value="{{ $exp->employee_id }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location </label>
                                <input type="text" name="locations[]" class="form-control" value="{{ $exp->location }}">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Start Date </label>
                                <div class="cal-icon">
                                    <input id="dob" name="exp_start_dates[]" value="{{ old('dob', $exp->start_date ?? '') }}" type="text"
                                        class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>End Date </label>
                                <div class="cal-icon">
                                    <input id="dob" name="exp_end_dates[]" value="{{ old('dob', $exp->end_date ?? '') }}" type="text"
                                        class="form-control datepicker">
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-3">Documents</h6>
                        <div class="row">
                            
                            @if(!empty($docFields))
                                @foreach($docFields as $field => $label)
                                    <div class="col-md-6 mb-3">
                                        <label>{{ $label }}</label>
                                        <input type="file" name="{{ $field }}s[]" class="form-control" accept="image/*,application/pdf">
                                        <input type="hidden" name="old_{{ $field }}s[]" value="{{ $exp->$field }}">
                                        @if($exp->$field)
                                            <a href="{{ asset('storage/employees/work-experience/' . $exp->$field) }}" target="_blank"
                                                class="d-block mt-1">
                                                View {{ $label }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-3">
                            <label>Reason for Leaving</label>
                            <textarea name="leaving_reasons[]" class="form-control"
                                rows="2">{{ $exp->leaving_reason }}</textarea>
                        </div>
                    </div>
                @empty
                    {{-- If no experiences exist, show one blank form --}}
                    <div class="employment-item border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Employment</h5>

                            <span class="delete-icon remove-employment" type="button" href="javascript:void(0)" style="cursor: pointer;">
                                <i class="fa-regular fa-trash-can"></i>
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Job Position</label>
                                <input type="text" name="positions[]" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Name</label>
                                <input type="text" name="companies[]" class="form-control">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <x-form.input-block class="mb-2">
                                    <x-form.label class="mb-0">
                                        {{ __('Reporting Manager / HR Name') }}</x-form.label>
                                    <x-form.input class="floating person_name" type="text"
                                        name="person_name[]" value="{{ old('person_name') }}" />
                                </x-form.input-block>
                            </div>

                            <div class="col-md-6">
                                <x-form.input-block class="mb-2">
                                    <x-form.label class="mb-0">
                                        {{ __('Reporting Manager / HR Contact Number') }}</x-form.label>
                                    <x-form.input class="floating person_contact number" type="text"
                                        name="person_contact[]" value="{{ old('person_contact') }}" maxlength="10" />
                                </x-form.input-block>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-form.input-block class="mb-2">
                                    <x-form.label class="mb-0"> {{ __('Employee ID (if assigned)') }}</x-form.label>
                                    <x-form.input class="floating emp_ids text-uppercase" type="text" name="emp_ids[]"
                                        value="{{ old('emp_ids') }}" />
                                </x-form.input-block>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Location</label>
                                <input type="text" name="locations[]" class="form-control">
                            </div>

                        </div>

                        <div class="row">
                            
                            <div class="col-md-6 mb-3">
                                <label>Start Date </label>
                                <div class="cal-icon">
                                    <input name="exp_start_dates[]" value="{{ old('exp_start_dates') }}" type="text"
                                        class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>End Date</label>
                                <div class="cal-icon">
                                    <input name="exp_end_dates[]" value="{{ old('exp_end_dates') }}" type="text"
                                        class="form-control datepicker">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Contact Person</label>
                                <input type="text" name="person_contacts[]" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Employee ID</label>
                                <input type="text" name="emp_ids[]" class="form-control text-uppercase">
                            </div>
                        </div>

                        <h6 class="mt-3">Documents</h6>
                        <div class="row">
                                                     
                            @foreach($docFields as $field => $label)
                                <div class="col-md-6 mb-3">
                                    <label>{{ $label }}</label>
                                    <input type="file" name="{{ $field }}s[]" class="form-control" accept="image/*,application/pdf">
                                    <input type="hidden" name="old_{{ $field }}s[]" value="">
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label>Reason for Leaving</label>
                            <textarea name="leaving_reasons[]" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="add-more">
                <a href="javascript:void(0);" id="add-employment" type="button"><i class="fa fa-plus-circle"></i>
                    {{ __('Add More') }}</a>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Employment Details</button>
            </div>
        </form>
    </div>
@endsection

@push('page-scripts')
    <script>
        $(document).ready(function () {
            // Clone template for new employment
            $('#add-employment').click(function () {
                let clone = $('.employment-item:first').clone();
                clone.find('input[type=text], input[type=date], textarea').val('');
                clone.find('input[type=file]').val('');
                clone.find('input[type=hidden]').val('');
                clone.find('a').remove(); // remove old file links
                clone.find('.delete-icon')
                    .removeAttr('data-route data-title data-question href')
                    .show();
                $('#employment-wrapper').append(clone);
            });

            // Remove employment block
            $(document).on('click', '.remove-employment', function () {
                $(this).closest('.employment-item').remove();
            });

            $(document).on('click', '.filled-delete', function () {
                let isConfirm = confirm('Are you sure you want to delete?');
                if (!isConfirm) return;

                let btn = $(this);  
                let url = btn.data('route'); 

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {},
                    success: function (res) {
                        btn.closest('.employment-item').remove();
                    }
                })
            });

            // Ajax submit
            $('#employmentForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        alert(res.message + ' ✅')
                        toastr.success(res.message);
                        window.location.href = "{{ route('employees.index') }}";
                    },
                    error: function (xhr) {
                        toastr.error('Something went wrong');
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush