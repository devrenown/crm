@extends('layouts.app')

@section('page-content')

    <div class="content container-fluid my-2">
        <form id="example-form" action="#">
            <div>
                @include('auth.wizard-section.terms-conditions')
                @include('auth.wizard-section.personal-details')
                @include('auth.wizard-section.identity')
                @include('auth.wizard-section.education-details')
                @include('auth.wizard-section.experience')
            </div>
        </form>
    </div>

@endsection

@push('page-styles')
    <style>
        :root {
            --gradient-blue: linear-gradient(to right, #0d6efd, #6610f2);
        }

        .page-wrapper {
            margin: 0 !important;
        }

        .wizard>.steps>ul>li {
            width: 20% !important;
        }

        .wizard > .content > .body {
            padding: 0.5% !important;
        }

        .wizard>.steps a,
        .wizard>.steps a:hover,
        .wizard>.steps a {
            padding: 0.3em 1em !important;
        }

        .wizard>.content {
            background: #ffffff !important;
        }

        .wizard>.actions a,
        .wizard>.actions a:hover,
        .wizard>.actions a:active {
            background: var(--gradient-blue) !important;
        }

        .wizard>.content>.body label {
            margin-bottom: 0 !important;
            font-size: 14px !important;
        }

        .wizard>.content>.body input {
            font-size: 14px !important;
        }

        .form-control {
            height: 35px !important;
            padding: 0 .75rem !important;
        }

        .form-select {
            padding: 0 2.25rem 0rem .75rem !important;
        }

        input[type=file].form-control {
            height: 34px !important;
            padding-top: 9px !important;
        }

        @media (max-width: 1065px) {
            .wizard>.steps>ul>li {
                width: 25% !important;
            }
        }

        @media (max-width: 860px) {
            .wizard>.steps>ul>li {
                width: 33.333% !important;
            }
        }

        @media (max-width: 655px) {
            .wizard>.steps>ul>li {
                width: 50% !important;
            }
        }

        @media (max-width: 445px) {
            .wizard>.steps a {
                font-size: 0.9em !important;
                padding: 0.3em 0.5em !important;
            }
        }

        @media (max-width: 575px) {
            .page-wrapper .content {
                padding: 10px;
            }
        }


    </style>

@endpush

@push('page-scripts')

    <script>
        $(document).ready(function () {

            let steps = { 0: false, 1: false, 2: false, 3: false, 4: false };

            var form = $("#example-form");

            form.on("change", "section input, section select, section textarea", function () {
                let stepIndex = $(this).closest("section").prevAll("section").length;
                steps[stepIndex] = true;
            });

            form.validate({
                errorPlacement: function errorPlacement(error, element) { element.before(error); },
                rules: {
                    confirm: {
                        equalTo: "#password"
                    },

                    account_number_confirmation: {
                        equalTo: "#account_number"
                    }
                },

                messages: {
                    account_number_confirmation: {
                        required: "Please re-enter your account number",
                        equalTo: "Account numbers do not match"
                    }
                }
            });

            form.children("div").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "fade",
                transitionEffectSpeed: 400,
                onStepChanging: async function (event, currentIndex, newIndex) {
                    if (newIndex < currentIndex) return true;
                
                    form.validate().settings.ignore = ":disabled,:hidden";
                    if (!form.valid()) return false;
                
                    // --- Validate only file inputs inside the current step (section) ---
                    let $currentSection = form.find('section').eq(currentIndex);
                    let invalidFile = false;
                
                    $currentSection.find('input[type="file"]').each(function () {
                        const $inp = $(this);
                        // If the input is not visible or disabled, skip it
                        if ($inp.is(':disabled') || !$inp.is(':visible')) return;
                
                        const ok = validateFileInput($inp);
                        if (!ok) {
                            invalidFile = true;
                            // scroll to first error and stop loop
                            $('html, body').animate({ scrollTop: $inp.offset().top - 100 }, 300);
                            return false; // break .each
                        }
                    });
                
                    if (invalidFile) {
                        console.warn('Step blocked due to invalid file(s) in step', currentIndex);
                        Toastify({ text: "Please fix invalid or missing files.", className: "error", duration: 3000 }).showToast();
                        // event.preventDefault();
                        return false;
                    }

                    if (!steps[currentIndex]) {
                        console.info(`No changes in step ${currentIndex}, skipping save.`);
                        return true;
                    }
                
                    // Save step data
                    let saveFn;
                    switch (currentIndex) {
                        case 0: saveFn = () => saveTermData(); break;
                        case 1: saveFn = () => savePersonalInfoData(); break;
                        case 2: saveFn = () => saveIdentityData(); break;
                        case 3: saveFn = () => saveEducationData('{{ @$userDetails->id }}'); break;
                        default: saveFn = null;
                    }
                
                    if (!saveFn) return true;
                
                    const nextBtn = $('.actions ul li:nth-child(2) a');
                    nextBtn.text('Saving...').css({ pointerEvents: 'none', opacity: '0.6' });
                
                    const result = await saveFn();
                
                    nextBtn.text('Next').css({ pointerEvents: 'auto', opacity: '1' });
                
                    if (result) {
                        steps[currentIndex] = false;
                        progressBar();
                        return true;
                    }
                    return false;
                },


                onFinishing: function (event, currentIndex) {
                    
                    form.validate().settings.ignore = ":disabled";
                    let currentSection = form.find('section').eq(currentIndex);

                    let isValid = currentSection.find("input, select, textarea").valid();

                    if (!isValid) return false;


                    let finishBtn = $('.actions ul li:last-child a'); 

                      finishBtn.text('Saving...').css({
                            pointerEvents: 'none',
                            opacity: '0.6'
                        });

                    
                    if (steps[currentIndex]) {
                        return saveExperienceData('{{ @$userDetails->id }}')
                            .done(function (res) {
                                steps[currentIndex] = false;

                                progressBar();

                                Toastify({
                                    text: 'Submitted!',
                                    className: 'success',
                                }).showToast();

                                window.location.href = "{{ route('onboard.welcome', ['user_id' => encrypt(@$userDetails->user_id)]) }}";
                            })
                            .fail(function (xhr) {
                                Toastify({
                                    text: 'Error saving experience. Please try again.',
                                    className: 'error',
                                }).showToast();

                                finishBtn.text('Finish').css({
                                    pointerEvents: 'auto',
                                    opacity: '1'
                                });

                                return false;
                            });
                    }

                    window.location.href = "{{ route('onboard.welcome', ['user_id' => encrypt(@$userDetails->user_id)]) }}";
                    
                    return true;
                },

                onStepChanged: function (event, currentIndex, priorIndex) {

                }
            });


            // showSavedFormData();
            initRepeater();
            initDatepicker();

            window.routes = {
                onboard: "{{ route('onboard') }}",
                onboardAcceptTerms: "{{ route('onboard.accept-terms') }}",
                onboardSavePersonalData: "{{ route('onboard.personal-details') }}",
                onboardSaveIdentityData: "{{ route('onboard.identity') }}",
                onboardDeleteIdentityId: "{{ route('onboard.deleteIdentityId') }}",
                onboardSaveEducations: "{{ route('onboard.educations') }}",
                onboardDeleteEducation: "{{ route('onboard.deleteEducation') }}",
                onboardSaveEmployement: "{{ route('onboard.employement') }}",
                onboardDeleteEmployement: "{{ route('onboard.deleteEmployement') }}",
            }


            function progressBar () {
                let blankField = [];

                let totalRequiredInput = $(document).find('input.necessary, select.necessary, textarea.necessary').length;
                let totalFilledInput   = $(document).find('input.necessary, select.necessary, textarea.necessary').filter(function () {
                    if ($(this).attr("type") === "file") {
                        // File input check
                        let hasFile = this.files.length > 0 || $(this).data("file") !== "";
                        if (!hasFile) {
                            blankField.push($(this).attr("name")); // or use $(this) for the element itself
                        }
                        return hasFile;
                    } else {
                        // Text, select, textarea
                        let value = $(this).val();
                        let hasValue = (value !== null && value !== undefined) ? value.trim() !== "" : false;

                        if (!hasValue) {
                            blankField.push($(this).attr("name")); 
                        }
                        return hasValue;
                    }
                }).length;

                // console.info('filled input ' + totalFilledInput, 'required input ' + totalRequiredInput);
                // console.warn('blank fields:', blankField);

                let progress = Math.round((totalFilledInput / totalRequiredInput) * 100);
                localStorage.setItem('progress', progress);
                getProgress();
            }


            progressBar();

        })
    </script>

@endpush