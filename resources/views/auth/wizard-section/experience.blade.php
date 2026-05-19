@php
$employmentRejectedCount = 0;

foreach (($employeeEmployementList->workExperience ?? []) as $exp) {
    foreach ($exp->documentActions as $doc) {
        if ($doc->status == 2) {
            $employmentRejectedCount++;
        }
    }
}
@endphp

<h3>Employment
    @if ($employmentRejectedCount > 0)
    <span class="position-absolute end-0 top-0 bg-danger text-white rounded-circle reject-count">{{ $employmentRejectedCount }}</span>
    @endif
</h3>
<section style="overflow-y: scroll; overflow-x: hidden;">
    <form action="#" method="post" enctype="multipart/form-data" id="experience-form">
        @csrf
        <div class="form-scroll">
            <div id="experience-container">

                {{-- Existing Employment Records --}}
                @if (count($employeeEmployementList->workExperience ?? []) > 0)
                 
                    @foreach ($employeeEmployementList->workExperience as $employement)

                        <div class="card experience-item deletable-item">

                            <input type="hidden" name="exp_ids[]" class="exp_ids" value="{{ $employement->id }}">
                            <div class="card-body">
                                <h3 class="card-title">
                                    {{ __('Experience') }}
                                    <span onclick="deleteEmployement('{{ $employement->id }}')" class="delete-icon" style="cursor: pointer;"><i
                                            class="fa-regular fa-trash-can"></i></span>

                                </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0"> {{ __('Job Position*') }}</x-form.label>
                                            <x-form.input class="floating positions required necessary" type="text" name="position[]"
                                                value="{{ $employement->position ?? '' }}" />
                                        </x-form.input-block>
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0"> {{ __('Company Name*') }}</x-form.label>
                                            <x-form.input class="floating companies required necessary" type="text" name="company[]"
                                                value="{{ $employement->company ?? '' }}" />
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0">
                                                {{ __('Reporting Manager / HR Name*') }}</x-form.label>
                                            <x-form.input class="floating person_name required necessary" type="text"
                                                name="person_name[]" value="{{ $employement->person_name ?? '' }}" />
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0">
                                                {{ __('Reporting Manager / HR Contact Number*') }}</x-form.label>
                                            <x-form.input class="floating person_contact number required necessary" type="text"
                                                name="person_contact[]" value="{{ $employement->person_contact ?? '' }}" maxlength="10" />
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0"> {{ __('Employee ID (if assigned)') }}</x-form.label>
                                            <x-form.input class="floating emp_ids text-uppercase" type="text" name="emp_ids[]"
                                                value="{{ $employement->employee_id ?? '' }}" />
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0"> {{ __('Location*') }}</x-form.label>
                                            <x-form.input class="floating locations required necessary" type="text" name="location[]"
                                                value="{{ $employement->location ?? '' }}" />
                                        </x-form.input-block>
                                    </div>


                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label"> {{ __('From*') }}</x-form.label>
                                            <div class="cal-icon">
                                                <x-form.input type="text" class="datepicker floating exp_start_dates required necessary"
                                                    name="start_date[]" value="{{ $employement->start_date ?? '' }}" />
                                            </div>
                                        </x-form.input-block>
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label"> {{ __('To*') }}</x-form.label>
                                            <div class="cal-icon">
                                                <x-form.input type="text" class="datepicker floating exp_end_dates required necessary"
                                                    name="end_date" value="{{ $employement->end_date ?? '' }}" />
                                            </div>
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Reason For Leaving*') }}</x-form.label>

                                            <x-form.input type="text" class="floating leaving_reasons required necessary" name="leaving_reasons"
                                                value="{{ $employement->leaving_reason ?? '' }}" />
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Offer Letter*') }}</x-form.label>
                                            <x-form.input type="file" class="floating offer_letters {{ empty($employement->offer_letter) ? 'required' : '' }} necessary" data-file="{{ $employement->offer_letter ?? '' }}" name="offer_letters[]" accept="application/pdf" />
                                            <x-form.input type="hidden" class="old_offer_letters" value="{{ $employement->offer_letter ?? '' }}"
                                                name="old_offer_letters[]" />

                                            @php
                                                $files = [
                                                    'offer_letter'       => $employement->offer_letter,
                                                    'appointment_letter' => $employement->appointment_letter,
                                                    'experience_letter'  => $employement->experience_letter,
                                                    'relieving_letter'   => $employement->relieving_letter,
                                                    'increment_letter'   => $employement->increment_letter,
                                                    'salary_slip'        => $employement->salary_slip,
                                                    'bank_statement'     => $employement->bank_statement,
                                                ];
                                                
                                            @endphp

                                            @if($employement->offer_letter)

                                                @php
                                                    $signedUrl = URL::signedRoute(
                                                        'secure.document.view',
                                                        [
                                                            'path'     => encrypt($employement->offer_letter),
                                                            'mime'     => $employement->offer_letter_mime ?? 'application/pdf',
                                                            'filename' => basename($employement->offer_letter),
                                                            'mode'     => 'clean'
                                                        ],
                                                        now()->addMinutes(5)
                                                    );
                                                    
                                                    $doc = $employement->getDocument('offer_letter');
                                                @endphp

                                                {!! \App\Helpers\DocumentStatus::statusBadge(@$doc->status) !!}
                                                <a href="javascript:void(0);" 
                                                onclick="openSecureDocument('{{ $signedUrl }}')">
                                                    View Offer Letter
                                                </a>

                                                @if (@$doc->status == 2)
                                                    {!! \App\Helpers\DocumentStatus::remarks(@$doc->remark ?? '') !!}
                                                @endif

                                            @endif
                                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Appointment Letter') }}</x-form.label>
                                            <x-form.input type="file" class="floating appointment_letters" data-file="{{ $employement->appointment_letter ?? '' }}"
                                                name="appointment_letters[]" accept="application/pdf" />
                                            <x-form.input type="hidden" class="old_appointment_letters" value="{{ $employement->appointment_letter ?? '' }}"
                                                name="old_appointment_letters[]" />

                                            
                                            @if($employement->appointment_letter)

                                                @php
                                                    $signedUrl = URL::signedRoute(
                                                        'secure.document.view',
                                                        [
                                                            'path'     => encrypt($employement->appointment_letter),
                                                            'mime'     => $employement->appointment_letter_mime ?? 'application/pdf',
                                                            'filename' => basename($employement->appointment_letter),
                                                            'mode'     => 'clean'
                                                        ],
                                                        now()->addMinutes(5)
                                                    );
                                                    
                                                    $doc = $employement->getDocument('appointment_letter');
                                                @endphp

                                                {!! \App\Helpers\DocumentStatus::statusBadge(@$doc->status) !!}
                                                <a href="javascript:void(0);" 
                                                onclick="openSecureDocument('{{ $signedUrl }}')">
                                                    View Appointment Letter
                                                </a>

                                                @if (@$doc->status == 2)
                                                    {!! \App\Helpers\DocumentStatus::remarks(@$doc->remark ?? '') !!}
                                                @endif
                                            @endif
                                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Experience Letter') }}</x-form.label>
                                            <x-form.input type="file" class="floating experience_letters" data-file="{{ $employement->experience_letter ?? '' }}"
                                                name="experience_letters[]" accept="application/pdf" />
                                            <x-form.input type="hidden" class="old_experience_letters" value="{{ $employement->experience_letter ?? '' }}"
                                                name="old_experience_letters[]" />


                                            @if($employement->experience_letter)

                                                @php
                                                    $signedUrl = URL::signedRoute(
                                                        'secure.document.view',
                                                        [
                                                            'path'     => encrypt($employement->experience_letter),
                                                            'mime'     => $employement->experience_letter_mime ?? 'application/pdf',
                                                            'filename' => basename($employement->experience_letter),
                                                            'mode'     => 'clean'
                                                        ],
                                                        now()->addMinutes(5)
                                                    );
                                                    
                                                    $doc = $employement->getDocument('experience_letter');
                                                @endphp

                                                {!! \App\Helpers\DocumentStatus::statusBadge(@$doc->status) !!}
                                                <a href="javascript:void(0);" 
                                                onclick="openSecureDocument('{{ $signedUrl }}')">
                                                    View Experience Letter
                                                </a>

                                                @if (@$doc->status == 2)
                                                    {!! \App\Helpers\DocumentStatus::remarks(@$doc->remark ?? '') !!}
                                                @endif
                                            @endif

                                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Relieving Letter') }}</x-form.label>
                                            <x-form.input type="file" class="floating relieving_letters" data-file="{{ $employement->relieving_letter ?? '' }}"
                                                name="relieving_letters[]" accept="application/pdf" />
                                            <x-form.input type="hidden" class="old_relieving_letters" value="{{ $employement->relieving_letter ?? '' }}"
                                                name="old_relieving_letters[]" />
                                            
                                            @if($employement->relieving_letter)

                                                @php
                                                    $signedUrl = URL::signedRoute(
                                                        'secure.document.view',
                                                        [
                                                            'path'     => encrypt($employement->relieving_letter),
                                                            'mime'     => $employement->relieving_letter_mime ?? 'application/pdf',
                                                            'filename' => basename($employement->relieving_letter),
                                                            'mode'     => 'clean'
                                                        ],
                                                        now()->addMinutes(5)
                                                    );
                                                    
                                                    $doc = $employement->getDocument('relieving_letter');
                                                @endphp

                                                {!! \App\Helpers\DocumentStatus::statusBadge(@$doc->status) !!}
                                                <a href="javascript:void(0);" 
                                                onclick="openSecureDocument('{{ $signedUrl }}')">
                                                    View Relieving Letter
                                                </a>

                                                @if (@$doc->status == 2)
                                                    {!! \App\Helpers\DocumentStatus::remarks(@$doc->remark ?? '') !!}
                                                @endif
                                            @endif

                                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Increment Letter') }}</x-form.label>
                                            <x-form.input type="file" class="floating increment_letters" data-file="{{ $employement->increment_letter ?? '' }}"
                                                name="increment_letters[]" accept="application/pdf" />
                                            <x-form.input type="hidden" class="old_increment_letters" value="{{ $employement->increment_letter ?? '' }}"
                                                name="old_increment_letters[]" />

                            
                                             @if($employement->increment_letter)

                                                @php
                                                    $signedUrl = URL::signedRoute(
                                                        'secure.document.view',
                                                        [
                                                            'path'     => encrypt($employement->increment_letter),
                                                            'mime'     => $employement->increment_letter_mime ?? 'application/pdf',
                                                            'filename' => basename($employement->increment_letter),
                                                            'mode'     => 'clean'
                                                        ],
                                                        now()->addMinutes(5)
                                                    );
                                                    
                                                    $doc = $employement->getDocument('increment_letter');
                                                @endphp

                                                {!! \App\Helpers\DocumentStatus::statusBadge(@$doc->status) !!}
                                                <a href="javascript:void(0);" 
                                                onclick="openSecureDocument('{{ $signedUrl }}')">
                                                    View Increment Letter
                                                </a>

                                                @if (@$doc->status == 2)
                                                    {!! \App\Helpers\DocumentStatus::remarks(@$doc->remark ?? '') !!}
                                                @endif
                                            @endif

                                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Salary Slip*') }}</x-form.label>
                                            <x-form.input type="file" class="floating salary_slips {{ empty($employement->salary_slip) ? 'required' : '' }} necessary" data-file="{{ $employement->salary_slip ?? '' }}" name="salary_slips[]" accept="application/pdf" />
                                            <x-form.input type="hidden" class="old_salary_slips" value="{{ $employement->salary_slip ?? '' }}"
                                                name="old_salary_slips[]" />

                                    
                                            @if($employement->salary_slip)

                                                @php
                                                    $signedUrl = URL::signedRoute(
                                                        'secure.document.view',
                                                        [
                                                            'path'     => encrypt($employement->salary_slip),
                                                            'mime'     => $employement->salary_slip_mime ?? 'application/pdf',
                                                            'filename' => basename($employement->salary_slip),
                                                            'mode'     => 'clean'
                                                        ],
                                                        now()->addMinutes(5)
                                                    );
                                                    
                                                    $doc = $employement->getDocument('salary_slip');
                                                @endphp

                                                {!! \App\Helpers\DocumentStatus::statusBadge(@$doc->status) !!}
                                                <a href="javascript:void(0);" 
                                                onclick="openSecureDocument('{{ $signedUrl }}')">
                                                    View Salary Slip
                                                </a>

                                                @if (@$doc->status == 2)
                                                    {!! \App\Helpers\DocumentStatus::remarks(@$doc->remark ?? '') !!}
                                                @endif
                                            @endif

                                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" calss="focus-label">
                                                {{ __('Bank Statement*') }}</x-form.label>
                                            <x-form.input type="file" class="floating bank_statements {{ empty($employement->bank_statement) ? 'required' : '' }} necessary" data-file="{{ $employement->bank_statement ?? '' }}"
                                                name="bank_statements[]" />
                                            <x-form.input type="hidden" class="old_bank_statements" value="{{ $employement->bank_statement ?? '' }}"
                                                name="old_bank_statements[]" />

                                            @if($employement->bank_statement)

                                                @php
                                                    $signedUrl = URL::signedRoute(
                                                        'secure.document.view',
                                                        [
                                                            'path'     => encrypt($employement->bank_statement),
                                                            'mime'     => $employement->bank_statement_mime ?? 'application/pdf',
                                                            'filename' => basename($employement->bank_statement),
                                                            'mode'     => 'clean'
                                                        ],
                                                        now()->addMinutes(5)
                                                    );
                                                    
                                                    $doc = $employement->getDocument('bank_statement');
                                                @endphp

                                                {!! \App\Helpers\DocumentStatus::statusBadge(@$doc->status) !!}
                                                <a href="javascript:void(0);" 
                                                onclick="openSecureDocument('{{ $signedUrl }}')">
                                                    View Bank Statement
                                                </a>

                                                @if (@$doc->status == 2)
                                                    {!! \App\Helpers\DocumentStatus::remarks(@$doc->remark ?? '') !!}
                                                @endif
                                            @endif

                                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                                            jpg,jpeg,png,webp,pdf
                                            &nbsp; max: 2MB</small>
                                        </x-form.input-block>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Blank Template --}}
                    <div class="card experience-item deletable-item">
                        <div class="card-body">
                            <h3 class="card-title">
                                {{ __('Experience') }}
                                <span href="javascript:void(0);" class="delete-icon" style="cursor: pointer;"><i
                                        class="fa-regular fa-trash-can"></i></span>

                            </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0"> {{ __('Job Position*') }}</x-form.label>
                                        <x-form.input class="floating positions required necessary" type="text" name="position[]"
                                            value="{{ old('position') }}" />
                                    </x-form.input-block>
                                </div>
                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0"> {{ __('Company Name*') }}</x-form.label>
                                        <x-form.input class="floating companies required necessary" type="text" name="company[]"
                                            value="{{ old('company') }}" />
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0">
                                            {{ __('Reporting Manager / HR Name*') }}</x-form.label>
                                        <x-form.input class="floating person_name required necessary" type="text"
                                            name="person_name[]" value="{{ old('person_name') }}" />
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0">
                                            {{ __('Reporting Manager / HR Contact Number*') }}</x-form.label>
                                        <x-form.input class="floating person_contact number required necessary" type="text"
                                            name="person_contact[]" value="{{ old('person_contact') }}" maxlength="10" />
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0"> {{ __('Employee ID (if assigned)') }}</x-form.label>
                                        <x-form.input class="floating emp_ids text-uppercase" type="text" name="emp_ids[]"
                                            value="{{ old('emp_ids') }}" />
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0"> {{ __('Location*') }}</x-form.label>
                                        <x-form.input class="floating locations required necessary" type="text" name="location[]"
                                            value="{{ old('location') }}" />
                                    </x-form.input-block>
                                </div>


                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label"> {{ __('From*') }}</x-form.label>
                                        <div class="cal-icon">
                                            <x-form.input type="text" class="datepicker floating exp_start_dates required necessary"
                                                name="start_date[]" value="{{ old('start_date') }}" />
                                        </div>
                                    </x-form.input-block>
                                </div>
                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label"> {{ __('To*') }}</x-form.label>
                                        <div class="cal-icon">
                                            <x-form.input type="text" class="datepicker floating exp_end_dates required necessary"
                                                name="end_date" value="{{ old('end_date') }}" />
                                        </div>
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Reason For Leaving*') }}</x-form.label>

                                        <x-form.input type="text" class="floating leaving_reasons required necessary" name="leaving_reasons"
                                            value="{{ old('leaving_reasons') }}" />
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Offer Letter*') }}</x-form.label>
                                        <x-form.input type="file" class="floating offer_letters required necessary" name="offer_letters[]" accept="application/pdf" />
                                        <x-form.input type="hidden" class="old_offer_letters" value=""
                                            name="old_offer_letters[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Appointment Letter') }}</x-form.label>
                                        <x-form.input type="file" class="floating appointment_letters"
                                            name="appointment_letters[]" accept="application/pdf" />
                                        <x-form.input type="hidden" class="old_appointment_letters" value=""
                                            name="old_appointment_letters[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Experience Letter') }}</x-form.label>
                                        <x-form.input type="file" class="floating experience_letters"
                                            name="experience_letters[]" accept="application/pdf" />
                                        <x-form.input type="hidden" class="old_experience_letters" value=""
                                            name="old_experience_letters[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Relieving Letter') }}</x-form.label>
                                        <x-form.input type="file" class="floating relieving_letters"
                                            name="relieving_letters[]" accept="application/pdf"  />
                                        <x-form.input type="hidden" class="old_relieving_letters" value=""
                                            name="old_relieving_letters[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Increment Letter') }}</x-form.label>
                                        <x-form.input type="file" class="floating increment_letters"
                                            name="increment_letters[]" accept="application/pdf"  />
                                        <x-form.input type="hidden" class="old_increment_letters" value=""
                                            name="old_increment_letters[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Salary Slip*') }}</x-form.label>
                                        <x-form.input type="file" class="floating salary_slips required necessary" name="salary_slips[]" accept="application/pdf"  />
                                        <x-form.input type="hidden" class="old_salary_slips" value=""
                                            name="old_salary_slips[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed pdf
                                            &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" calss="focus-label">
                                            {{ __('Bank Statement*') }}</x-form.label>
                                        <x-form.input type="file" class="floating bank_statements required necessary"
                                            name="bank_statements[]" />
                                        <x-form.input type="hidden" class="old_bank_statements" value=""
                                            name="old_bank_statements[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                                            jpg,jpeg,png,webp,pdf
                                            &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div class="add-more">
            <a onclick="employementRepeater()" id="add-experience"><i class="fa fa-plus-circle"></i>
                {{ __('Add More') }}</a>
        </div>
    </form>
</section>

@push('page-scripts')

    <script>

        function employementRepeater() {
            let $container = $('#experience-container');
            let $firstCard = $container.find('.experience-item').first();
            let $clone = $firstCard.clone();

            // Reset all inputs
            $clone.find('input[type="text"], input[type="hidden"]').val('');

            $clone.find('input[type="file"]').not('.increment_letters, .appointment_letters, .relieving_letters, .experience_letters').each(function () {
                $(this).val('');
                $(this).removeAttr('data-file');
                $(this).removeClass('is-valid'); // remove success visual
                $(this).addClass('required necessary'); // enforce validation again
            });

            $clone.find('i.status-mark, a[href*="storage/employees"]').remove();
            $clone.find('.remarks-message').remove();

            $clone.find('img, a').remove();

            // Reset validation states
            $clone.find('.invalid-feedback').remove();
            $clone.find('.is-invalid').removeClass('is-invalid');

            // Fix delete icon → remove onclick/id and make it just remove card
            $clone.find('.delete-icon')
                .removeAttr('onclick')   
                .off('click')           
                .on('click', function () {
                    $clone.slideUp(300, function () { $(this).remove(); });
                });

            // Append to container
            $container.append($clone);
            $clone.hide().slideDown(400).animate({ opacity: 1 }, { queue: false, duration: 400 });

            // Re-init datepicker only for new card
            initDatepicker($clone);
        };

    </script>

@endpush