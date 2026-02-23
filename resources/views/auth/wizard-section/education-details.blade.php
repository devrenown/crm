@php
    $path = public_path('storage/employees/education/');
    $url = asset('storage/employees/education/');
@endphp

<h3>Education Details</h3>
<section style="overflow-y: scroll; overflow-x: hidden;">
    <form action="#" method="post" enctype="multipart/form-data" id="repeater-education">
        @csrf
        <div class="form-scroll">
            @if (count($employeeEducationList->education ?? []) > 0)

                <div id="education-container">
                    @foreach ($employeeEducationList->education as $education)

                        <div class="card education-item deletable-item education-first-card">

                            <input type="hidden" name="edu_ids[]" class="edu_ids" value="{{ $education->id }}">
                            <div class="card-body">
                                <h3 class="card-title">
                                    Education Information
                                    <span onclick="deleteEducation('{{ $education->id }}')" class="delete-icon" style="cursor: pointer;">
                                        <i class="fa-regular fa-trash-can"></i></span>
                                </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form.label class="mb-0"> {{ __('Course/Certification*') }}</x-form.label>
                                        <select class="form-select form-control courses required necessary mb-0" name="course[]">
                                            <option value="10th" {{ $education->course == '10th' ? 'selected' : '' }}>10th</option>
                                            <option value="12th" {{ $education->course == '12th' ? 'selected' : '' }}>12th</option>
                                            <option value="diploma" {{ $education->course == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="graduation" {{ $education->course == 'graduation' ? 'selected' : '' }}>Graduation</option>
                                            <option value="post graduation" {{ $education->course == 'post graduation' ? 'selected' : '' }}>Post Graduation</option>
                                            <option value="other" {{ $education->course == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0"> {{ __('University/Institution/Board*') }}</x-form.label>
                                            <x-form.input type="text" class="institutions required necessary" name="institution[]"
                                                value="{{ $education->institution ?? '' }}" />
                                        </x-form.input-block>
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0"> {{ __('Subject/Branch/Specialization*') }}</x-form.label>
                                            <x-form.input type="text" class="subjects required necessary" name="subject[]"
                                                value="{{  $education->subject ?? '' }}" />
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0"> {{ __('Grade/Percentage*') }}</x-form.label>
                                            <x-form.input type="text" class="grades required necessary" name="grade[]" placeholder="e.g. 82%, 8.2 CGPA, or A+" value="{{  $education->grade ?? '' }}" />
                                        </x-form.input-block>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" class="focus-label">
                                                {{ __('Starting Date*') }}</x-form.label>
                                            <div class="cal-icon">
                                                <x-form.input type="text" class="datepicker ed_start_dates required necessary" name="start_date[]"
                                                    value="{{ $education->start_date ?? '' }}" />
                                            </div>
                                        </x-form.input-block>
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" class="focus-label">
                                                {{ __('Date Completed*') }}</x-form.label>
                                            <div class="cal-icon">
                                                <x-form.input type="text" class="datepicker ed_end_dates required necessary" name="end_date[]"
                                                    value="{{ $education->end_date ?? '' }}" />
                                            </div>
                                        </x-form.input-block>
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input-block class="mb-2">
                                            <x-form.label class="mb-0" class="focus-label"> {{ __('File*') }}</x-form.label>
                                            <x-form.input type="file" class="education-files {{ empty($education->file) ? 'required' : '' }} necessary" data-file="{{ $education->file ?? '' }}" name="file[]" />
                                            <x-form.input type="hidden" class="old-education-files"
                                                value="{{ $education->file ?? '' }}" name="old_education_files[]" />

                                            @php
                                                $filePath = $path . '/' . $education->file;
                                                $fileUrl = $url . '/' . $education->file;
                                            @endphp

                                            @if ($education->file && file_exists($filePath))
                                               <i class="fa-solid fa-check-circle text-success me-1"></i> <a href="{{ $fileUrl }}" target="_blank">View File</a>
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

                </div>

            @else
                <div id="education-container">

                    <div class="card education-item deletable-item education-first-card">
                        <div class="card-body">
                            <h3 class="card-title">
                                Education Information
                                <span class="delete-icon"><i
                                        class="fa-regular fa-trash-can"></i></span>
                            </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.label class="mb-0"> {{ __('Course/Certification*') }}</x-form.label>
                                    <select class="form-select form-control courses required necessary" name="course[]">
                                        <option value="10th">10th</option>
                                        <option value="12th">12th</option>
                                        <option value="diploma">Diploma</option>
                                        <option value="graduation">Graduation</option>
                                        <option value="post graduation">Post Graduation</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0"> {{ __('University/Institution/Board*') }}</x-form.label>
                                        <x-form.input type="text" class="institutions required necessary" name="institution[]"
                                            value="{{ old('institution') }}" />
                                    </x-form.input-block>
                                </div>
                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0"> {{ __('Subject/Branch/Specialization*') }}</x-form.label>
                                        <x-form.input type="text" class="subjects required necessary" name="subject[]"
                                            value="{{ old('subject') }}" />
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0"> {{ __('Grade/Percentage*') }}</x-form.label>
                                        <x-form.input type="text" class="grades required necessary" name="grade[]" placeholder="e.g. 82%, 8.2 CGPA, or A+" value="{{ old('grade') }}" />
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" class="focus-label">
                                            {{ __('Starting Date*') }}</x-form.label>
                                        <div class="cal-icon">
                                            <x-form.input type="text" class="datepicker ed_start_dates required necessary" name="start_date[]"
                                                value="{{ old('start_date') }}" />
                                        </div>
                                    </x-form.input-block>
                                </div>
                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" class="focus-label">
                                            {{ __('Date Completed*') }}</x-form.label>
                                        <div class="cal-icon">
                                            <x-form.input type="text" class="datepicker ed_end_dates required necessary" name="end_date[]"
                                                value="{{ old('end_date') }}" />
                                        </div>
                                    </x-form.input-block>
                                </div>
                                <div class="col-md-6">
                                    <x-form.input-block class="mb-2">
                                        <x-form.label class="mb-0" class="focus-label"> {{ __('File*') }}</x-form.label>
                                        <x-form.input type="file" class="education-files required necessary" name="file[]" />

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                                        jpg,jpeg,png,webp,pdf
                                        &nbsp; max: 2MB</small>
                                    </x-form.input-block>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="add-more">

            <a onclick="educationRepeater()" type="button"><i class="fa fa-plus-circle"></i>
                {{ __('Add More') }}</a>

        </div>
    </form>
</section>

@push('page-scripts')

    <script>

        function educationRepeater() {
            // Clone first education-item
            let newCard = $('.education-item').first().clone();

            // Reset all inputs
            newCard.find('input[type="text"], input[type="hidden"], select').each(function () {
                $(this).val('');
                $(this).addClass('required necessary');
            });

            newCard.find('input[type="file"]').each(function () {
                $(this).val('');
                $(this).removeAttr('data-file');
                $(this).removeClass('is-valid'); 
                $(this).addClass('required necessary'); 
            });

            newCard.find('i.fa-check-circle, a[href*="storage/employees"]').remove();
            newCard.find('img, a').remove();

            // Reset validation states
            newCard.find('.invalid-feedback').remove();
            newCard.find('.is-invalid').removeClass('is-invalid');

            // Fix delete icon → remove onclick/id and make it just remove card
            newCard.find('.delete-icon')
                .removeAttr('onclick')   // remove deleteEducation(id)
                .off('click')            // clear old click
                .on('click', function () {
                    newCard.slideUp(300, function () { $(this).remove(); });
                });

            // Append to container
            $('#education-container').append(newCard);
            newCard.hide().slideDown(400).animate({ opacity: 1 }, { queue: false, duration: 400 });

            // Re-init datepicker only for new card
            initDatepicker(newCard);
        }


    </script>


@endpush