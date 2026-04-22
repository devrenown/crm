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
            <input type="hidden" name="user_id" value="{{ $employeeDetail->user_id }}">

            <div id="employment-wrapper">

                @forelse($experiences as $index => $exp)

                    <div class="employment-item border rounded p-3 mb-3">

                        <input type="hidden" name="exp_ids[]" value="{{ $exp->id }}">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <h5>Employment</h5>
                            <span class="delete-icon filled-delete"
                                data-route="{{ route('onboard.deleteEmployement', ['employement_id' => $exp->id, 'user_id' => $employeeDetail->user_id]) }}" style="cursor: pointer;">
                                <i class="fa-regular fa-trash-can"></i>
                            </span>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Job Position <span class="text-danger">*</span> </label>
                                <input type="text" name="positions[]" class="form-control" value="{{ $exp->position }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Name <span class="text-danger">*</span> </label>
                                <input type="text" name="companies[]" class="form-control" value="{{ $exp->company }}" required>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <x-form.input-block class="mb-2">
                                    <x-form.label class="mb-0">
                                        {{ __('Reporting Manager / HR Name') }} </x-form.label>
                                    <x-form.input class="floating person_name" type="text"
                                        name="person_names[]" value="{{ $exp->person_name ?? '' }}" />
                                </x-form.input-block>
                            </div>

                            <div class="col-md-6">

                                <x-form.input-block class="mb-2">
                                    <x-form.label class="mb-0">
                                        {{ __('Reporting Manager / HR Contact Number') }}</x-form.label>
                                    <input type="number" name="person_contacts[]" class="form-control"
                                    value="{{ $exp->person_contact }}" maxlength="10">
                                </x-form.input-block>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Employee ID</label>
                                <input type="text" name="emp_ids[]" class="form-control text-uppercase" value="{{ $exp->employee_id }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location <span class="text-danger">*</span> </label>
                                <input type="text" name="locations[]" class="form-control" value="{{ $exp->location }}" required>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Start Date <span class="text-danger">*</span> </label>

                                <input id="dob" name="exp_start_dates[]" value="{{ old('dob', optional($exp->start_date)->format('Y-m-d') ) }}" type="date"
                                class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">

                                <label>End Date <span class="text-danger">*</span> </label>
                                <input id="dob" name="exp_end_dates[]" value="{{ old('dob', optional($exp->end_date)->format('Y-m-d') ) }}" type="date"
                                class="form-control" required>
                            </div>
                        </div>

                        <h6 class="mt-3">Documents</h6>

                        <div class="row">

                            @if(!empty($docFields))
                                @foreach($docFields as $field => $label)

                                    <div class="col-md-6 mb-3 document-block">
                                        <label>{{ $label }}</label>
                                        <input type="file" name="{{ $field }}s[]" class="form-control" accept="image/*,application/pdf">
                                        <input type="hidden" name="old_{{ $field }}s[]" value="{{ $exp->$field }}">

                                        @if($exp->$field)
                                            @php
                                                $signedUrl = URL::signedRoute(
                                                    'secure.document.view',
                                                    [
                                                        'path'     => encrypt($exp->$field),
                                                        'mime'     => $exp->{$field . '_mime'} ?? 'application/pdf',
                                                        'filename' => basename($exp->$field),
                                                        'mode'     => 'watermark'
                                                    ],

                                                    now()->addMinutes(5)
                                                );

                                                $firstWordOfDocument = strtolower(strtok($label, ' '));
                                                $statusField         = $firstWordOfDocument . '_status';
                                                $remarkField         = $firstWordOfDocument . '_remarks';

                                                $docAction = $exp->documentActions
                                                    ->where('document_type', $field)
                                                    ->first();

                                                $statusValue = $docAction?->status;
                                                $remarkValue = $docAction?->remark;

                                                $actionBy = optional($docAction?->actionBy)->name ?? null;
                                                
                                            @endphp

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <a href="javascript:void(0);"
                                                        onclick="openSecureDocument('{{ $signedUrl }}')"
                                                        class="d-block mt-1">
                                                        {!! \App\Helpers\DocumentStatus::statusBadge($statusValue) !!}
                                                        View {{ $label }}
                                                    </a>
                                                </div>

                                                <div class="col-md-6 mt-1">
                                                    <select class="form-control form-select status-dropdown" name="{{ $statusField }}[]">
                                                        <option value="0" {{ $statusValue == 0 ? 'selected' : '' }}>Pending</option>
                                                        <option value="1" {{ $statusValue == 1 ? 'selected' : '' }}>Verified</option>
                                                        <option value="2" {{ $statusValue == 2 ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </div>

                                                <div class="col-12 remarks-container" style="{{ $statusValue == 2 ? '' : 'display:none;' }}">
                                                    <span>Remarks</span> 
                                                    <input type="text" value="{{ $remarkValue }}" name="{{ $remarkField }}[]" class="form-control">
                                                </div>

                                                @if ($docAction?->actionBy)
                                                    <small class="text-muted">
                                                        By {{ $docAction->actionBy->fullname }}
                                                        ({{ tz($docAction->action_at, 'd M Y') }})
                                                    </small>
                                                @endif
                                            </div>
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

                            <span class="delete-icon remove-employment" style="cursor: pointer;">
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
                                    <x-form.label>Reporting Manager / HR Name</x-form.label>
                                    <x-form.input type="text" name="person_names[]" />
                                </x-form.input-block>
                            </div>

                            <div class="col-md-6">
                                <x-form.input-block class="mb-2">
                                    <x-form.label>Reporting Manager / HR Contact Number</x-form.label>
                                    <input type="number" name="person_contacts[]" class="form-control" maxlength="10">
                                </x-form.input-block>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Employee ID</label>
                                <input type="text" name="emp_ids[]" class="form-control text-uppercase">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Location</label>
                                <input type="text" name="locations[]" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Start Date</label>
                                <input name="exp_start_dates[]" type="date" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>End Date</label>
                                <input name="exp_end_dates[]" type="date" class="form-control">
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
                <button type="submit" id="saveBtn" class="btn btn-primary">
                    <span class="btn-text">Save Employment Details</span>
                    <span class="btn-loader" style="display:none;">
                        <i class="fa fa-spinner fa-spin"></i> Saving...
                    </span>
                </button>
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

                let btn = $('#saveBtn');

                // show loader
                btn.prop('disabled', true);
                btn.find('.btn-text').hide();
                btn.find('.btn-loader').show();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function (res) {
                        alert(res.message);
                        window.location.href = "{{ route('employees.index') }}";
                    },

                    error: function (xhr) {
                        alert('Something went wrong');
                        console.log(xhr.responseText);
                    },

                    complete: function () {
                        // reset button
                        btn.prop('disabled', false);
                        btn.find('.btn-text').show();
                        btn.find('.btn-loader').hide();
                    }
                });
            });
        });

    </script>

@endpush