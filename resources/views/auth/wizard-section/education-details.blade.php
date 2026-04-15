@php
    $educationRejectedCount = collect($employeeEducationList->education)->where('status', 2)->count();
@endphp

<h3>Education Details
    @if ($educationRejectedCount > 0)
    <span class="position-absolute end-0 top-0 bg-danger text-white rounded-circle reject-count">{{ $educationRejectedCount }}</span>
    @endif
</h3>
<section style="overflow-y: scroll; overflow-x: hidden;">
    <form action="#" method="post" enctype="multipart/form-data" id="repeater-education">
        @csrf
        <div class="form-scroll">
            <div id="education-container">

                @if(!empty($employeeEducationList->education) && count($employeeEducationList->education) > 0)
                    @foreach($employeeEducationList->education as $education)
                     @php
                        $relativePath = $education->file;
                        $fullPath = storage_path('app/private/' . $relativePath);
                    @endphp
                        <div class="card education-item deletable-item">
                            <input type="hidden" name="edu_ids[]" value="{{ $education->id }}">
                            <div class="card-body">
                                <h3 class="card-title">
                                    Education Information
                                    <span data-id="{{ $education->id ?? '' }}" class="delete-icon" style="cursor:pointer;">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </span>
                                </h3>
                                <div class="row">

                                    <div class="col-md-6">
                                        <x-form.label>Course/Certification*</x-form.label>
                                        <select class="form-select courses required necessary" name="course[]">
                                            @php
                                                $courses = ['10th','12th','diploma','graduation','post graduation','other'];
                                            @endphp
                                            @foreach($courses as $course)
                                                <option value="{{ $course }}" {{ $education->course == $course ? 'selected' : '' }}>{{ ucfirst($course) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.label>University/Institution/Board*</x-form.label>
                                        <x-form.input type="text" name="institution[]" class="institutions required necessary" value="{{ $education->institution ?? '' }}" />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.label>Subject/Branch/Specialization*</x-form.label>
                                        <x-form.input type="text" name="subject[]" class="subjects required necessary" value="{{ $education->subject ?? '' }}" />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.label>Grade/Percentage*</x-form.label>
                                        <x-form.input type="text" name="grade[]" class="grades required necessary" value="{{ $education->grade ?? '' }}" placeholder="e.g. 82%, 8.2 CGPA, or A+" />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.label>Starting Date*</x-form.label>
                                        <div class="cal-icon">
                                            <x-form.input type="text" name="start_date[]" class="datepicker ed_start_dates required necessary" value="{{ $education->start_date ?? '' }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.label>Date Completed*</x-form.label>
                                        <div class="cal-icon">
                                            <x-form.input type="text" name="end_date[]" class="datepicker ed_end_dates required necessary" value="{{ $education->end_date ?? '' }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.label>File*</x-form.label>
                                        <x-form.input type="file" name="file[]" class="education-files {{ empty($education->file) ? 'required' : '' }} necessary" data-file="{{ $education->file ?? '' }}" />
                                        <input type="hidden" name="old_education_files[]" value="{{ $education->file ?? '' }}" class="old-education-files" />

                                        @if($education->file && file_exists($fullPath))
                                            @php
                                            $mime = $education->document_mime ?? mime_content_type($fullPath);
                                                $signedUrl = URL::signedRoute(
                                                    'secure.document.view',
                                                    [
                                                        'path' => encrypt($relativePath),
                                                         'mime' => $mime,
                                                        'filename' => basename($education->file),
                                                        'mode' => 'clean'
                                                    ],
                                                    now()->addMinutes(5)
                                                );
                                                
                                            @endphp
                                            {!! \App\Helpers\DocumentStatus::statusBadge($education->status) !!}
                                            <a href="javascript:void(0);" onclick="openSecureDocument('{{ $signedUrl }}')">View File</a>

                                            @if ($education->status == 2)
                                                {!! \App\Helpers\DocumentStatus::remarks($education->remarks) !!}
                                            @endif
                                        @endif

                                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed jpg,jpeg,png,webp,pdf &nbsp; max: 2MB</small>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    
                    <div class="card education-item deletable-item">
                        <div class="card-body">
                            <h3 class="card-title">Education Information <span class="delete-icon"><i class="fa-regular fa-trash-can"></i></span></h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.label>Course/Certification*</x-form.label>
                                    <select class="form-select courses required necessary" name="course[]">
                                        @foreach(['10th','12th','diploma','graduation','post graduation','other'] as $course)
                                            <option value="{{ $course }}">{{ ucfirst($course) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <x-form.label>University/Institution/Board*</x-form.label>
                                    <x-form.input type="text" name="institution[]" class="institutions required necessary" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.label>Subject/Branch/Specialization*</x-form.label>
                                    <x-form.input type="text" name="subject[]" class="subjects required necessary" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.label>Grade/Percentage*</x-form.label>
                                    <x-form.input type="text" name="grade[]" class="grades required necessary" placeholder="e.g. 82%, 8.2 CGPA, or A+" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.label>Starting Date*</x-form.label>
                                    <div class="cal-icon">
                                        <x-form.input type="text" name="start_date[]" class="datepicker ed_start_dates required necessary" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <x-form.label>Date Completed*</x-form.label>
                                    <div class="cal-icon">
                                        <x-form.input type="text" name="end_date[]" class="datepicker ed_end_dates required necessary" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <x-form.label>File*</x-form.label>
                                    <x-form.input type="file" name="file[]" class="education-files required necessary" />
                                    <small class="text-muted d-block"><span class="text-danger">*</span>Allowed jpg,jpeg,png,webp,pdf &nbsp; max: 2MB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div class="add-more mt-2">
            <a onclick="educationRepeater()" type="button"><i class="fa fa-plus-circle"></i> Add More</a>
        </div>
    </form>
</section>

<div class="modal fade" id="secureDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Preview</h5>
                <button type="button" class="close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height:80vh;">
                <iframe id="secureDocumentFrame" src="" style="width:100%; height:100%; border:none;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    function educationRepeater() {
        let newCard = $('.education-item').first().clone();

        // Reset all inputs
        newCard.find('input[type="text"], input[type="hidden"], select').val('').addClass('required necessary');
        newCard.find('input[type="file"]').val('').removeAttr('data-file').removeClass('is-valid').addClass('required necessary');

        newCard.find('i.status-mark, a[href*="storage/employees"]').remove();
        newCard.find('.remarks-message').remove();
        newCard.find('img, a').remove();
        newCard.find('.delete-icon').removeAttr('data-id');

        // Reset validation states
        newCard.find('.invalid-feedback').remove();
        newCard.find('.is-invalid').removeClass('is-invalid');

        $('#education-container').append(newCard.hide().slideDown(400));

        // Initialize datepicker for new inputs
        initDatepicker(newCard);
    }

    $(document).on('click', '.education-item .delete-icon', function () {
        let card = $(this).closest('.education-item');

        let eduId = card.find('input[name="edu_ids[]"]').val();

        if (eduId) {
            if (!confirm('Are you sure you want to delete this record?')) return;

            deleteEducation(eduId);
            card.slideUp(300, function () { $(this).remove(); });
        } else {
            card.slideUp(300, function () { $(this).remove(); });
        }
    });

    function openSecureDocument(url) {
        $('#secureDocumentFrame').attr('src', url);
        $('#secureDocumentModal').modal('show');
    }

    $('#secureDocumentModal').on('hidden.bs.modal', function () {
        $('#secureDocumentFrame').attr('src', '');
    });
</script>
@endpush