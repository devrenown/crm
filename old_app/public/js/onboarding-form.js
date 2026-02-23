csrfToken = $('meta[name="csrf-token"]').attr('content');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
});

const fileValidationRules = {
    photo: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
    'id_image[]': ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf'],
    'education_files[]': ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf'],
    'offer_letters[]': ['application/pdf'],
    'appointment_letters[]': ['application/pdf'],
    'experience_letters[]': ['application/pdf'],
    'relieving_letters[]': ['application/pdf'],
    'increment_letters[]': ['application/pdf'],
    'salary_slips[]': ['application/pdf'],
    'bank_statements[]': ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf'],
};

const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB


function validateFileInput($fileInput) {
    const nameAttr = $fileInput.attr('name');
    const file = $fileInput[0]?.files?.[0];
    $fileInput.removeClass('is-invalid');
    $fileInput.siblings('.file-error-message').remove();

    if (!file) return true;

    // Use specific rule, or fallback to all types if not found
    const allowedTypes = fileValidationRules[nameAttr] || Object.values(fileValidationRules).flat();

    if (!allowedTypes.includes(file.type)) {
        $fileInput.addClass('is-invalid');
        const allowedList = allowedTypes.map(t => t.split('/')[1]).join(', ');
        $fileInput.after(`<div class="file-error-message text-danger">❌ Invalid type. Allowed: ${allowedList}</div>`);
        return false;
    }

    if (file.size > MAX_FILE_SIZE) {
        $fileInput.addClass('is-invalid');
        $fileInput.after(`<div class="file-error-message text-danger">❌ File too large. Max 2 MB</div>`);
        return false;
    }

    return true;
}


function showUploadSpinner($input, show = true, percent = null) {
    let $wrap = $input.siblings('.upload-spinner-wrap');
    if (show) {
        if ($wrap.length === 0) {
            $input.after(`
                <div class="upload-spinner-wrap" style="display:inline-flex;align-items:center;gap:6px;">
                    <div class="upload-spinner"></div>
                    <span class="upload-percent"></span>
                </div>
            `);
        }
        if (percent !== null) {
            $wrap.find('.upload-percent').text(`${percent}%`);
        }
    } else {
        $wrap.remove();
    }
}


// Spinner CSS (include once)
$(`<style>
.upload-spinner {
  display:inline-block;
  width:20px; height:20px;
  border:2px solid rgba(0,0,255,0.2);
  border-top-color:#007bff;
  border-radius:50%;
  animation: spin 0.8s linear infinite;
  margin-left:6px;
  vertical-align:middle;
}
@keyframes spin {to {transform: rotate(360deg);}}
</style>`).appendTo('head');


$(document).on('change', 'input[type="file"]', function () {
    const $input = $(this);
    $input.siblings('.file-error-message, .upload-spinner').remove();

    const valid = validateFileInput($input);
    if (!valid) return;

    // Simulate upload spinner (or later tie to AJAX progress)
    showUploadSpinner($input, true);
    setTimeout(() => showUploadSpinner($input, false), 1000);
});


function initDatepicker(container = $(document)) {
    container.find('input.datepicker').each(function () {
        $(this).datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
        });
    });
}

function initRepeater() {
    $('.repeater').each(function () {
        if (!$(this).data('initialized')) {
            $(this).repeater({
                show: function () {
                    $(this).slideDown();
                    initDatepicker($(this));
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });

            $(this).data('initialized', true);
        }
    });
}

// delegate delete click (works for dynamically added elements)
$(document).on('click', '.deletable-item .delete-icon', function () {
    $(this).closest('.deletable-item').slideUp(300, function () {
        $(this).remove();
    });
});

// function getFormData() {
//     return JSON.parse(localStorage.getItem('formData')) || {};
// }

// function showSavedFormData() {
//     const formData = getFormData();
//     console.log(formData);
//     if (formData.terms) {
//         formData.terms.accepted ? $('#acceptTerms').prop('checked', true) : $('#acceptTerms').prop('checked', false);
//     }

// }

async function saveTermData() {
    let isAccepted = $('#acceptTerms').is(':checked') || false;
    let formData = new FormData();

    formData.append('is_term_accepted', isAccepted);

    const res = await $.ajax({
        url: window.routes.onboardAcceptTerms,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
    });

    if (res.status == 200) {
        Toastify({ text: res.message || "Terms saved!", className: "success" }).showToast();
        return true;
    }

    return false;
}

async function savePersonalInfoData() {
    let major_illness = $('#major_illness').is(':checked')  || false;
    let refrence = $('#refrence').is(':checked')            || false;

    let formData = new FormData();


    formData.append('first_name', $('#f_name').val()                || '');
    formData.append('middle_name', $('#m_name').val()               || '');
    formData.append('last_name', $('#l_name').val()                 || '');
    formData.append('email', $('#email').val()                      || null);
    formData.append('gender', $('#gender').val()                    || null);
    formData.append('contact', $('#contact').val()                  || null);
    formData.append('dob', $('#dob').val()                          || null);
    formData.append('marital_status', $('#marital_status').val()    || null);
    formData.append('no_of_children', $('#no_of_children').val()    || null);
    formData.append('joining_date', $('#joining_date').val()        || null);
    formData.append('company', $('#company').val()                  || null);
    formData.append('designation', $('#designation').val()          || null);
    formData.append('total_exp', $('#total_exp').val()              || null);
    formData.append('blood_group', $('#blood_group').val()          || null);
    formData.append('how_know', $('#how_know').val()                || null);
    formData.append('major_illness', major_illness);
    formData.append('refrence', refrence);

    formData.append('bank_name', $('#bank_name').val()              || null);
    formData.append('branch_address', $('#branch_address').val()    || null);
    formData.append('account_number', $('#account_number').val()    || null);
    formData.append('account_number_confirmation', $('#account_number_confirmation').val() || null);
    formData.append('ifsc_code', $('#ifsc_code').val()              || null);

    if ($('#photo')[0].files.length > 0) {
        formData.append('photo', $('#photo')[0].files[0]);
    } else {
        formData.append('old_image', $('#old_image').val());
    }

    if (major_illness) {
        formData.append('specification', $('#major_illness_specification').val() || null)
    }

    if (refrence) {
        formData.append('ref_emp_name', $('#emp_name').val()    || null);
        formData.append('ref_emp_id', $('#emp_id').val()        || null);
    }

    const res = await $.ajax({
        url: window.routes.onboardSavePersonalData,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
    });

    if (res.status == 200) {
        Toastify({ text: res.message || "Personal Information saved!", className: "success" }).showToast();
        return true;
    }

    return false;

}

// Full-page overlay while uploading/saving
function showGlobalOverlay(show = true, text = "Uploading... Please wait.") {
    let $overlay = $('#upload-overlay');
    if (show) {
        if ($overlay.length === 0) {
            $('body').append(`
                <div id="upload-overlay" style="
                    position: fixed; inset: 0;
                    background: rgba(0,0,0,0.65);
                    display: flex; justify-content: center; align-items: center;
                    z-index: 9999; color: white; font-weight: bold; font-size: 18px;
                ">${text}</div>
            `);
        } else {
            $overlay.text(text);
        }
    } else {
        $overlay.remove();
    }
}



async function saveIdentityData() {
    return new Promise(async (resolve) => {
        let sameAs = $('#same_address').is(':checked') || false;
        let formData = new FormData();

        const cCity = $('#city').val(), cState = $('#state').val(), cAddress = $('#c_address').val();
        const pCity = $('#p_city').val(), pState = $('#p_state').val(), pAddress = $('#p_address').val();

        formData.append('c_city', cCity || '');
        formData.append('c_state', cState || '');
        formData.append('c_address', cAddress || '');

        if (sameAs) {
            formData.append('p_city', cCity || '');
            formData.append('p_state', cState || '');
            formData.append('p_address', cAddress || '');
        } else {
            formData.append('p_city', pCity || '');
            formData.append('p_state', pState || '');
            formData.append('p_address', pAddress || '');
        }

        let isValid = true;
        $('.file-error-message, .upload-indicator').remove();

        $('#id-container .id-item').each(function (index) {
            const $item = $(this);
            const $fileInput = $item.find('.id_image');
            const file = $fileInput[0]?.files?.[0];

            if (!validateFileInput($fileInput)) {
                isValid = false;
                $('html, body').animate({ scrollTop: $fileInput.offset().top - 100 }, 400);
                return false; // break .each
            }

            formData.append(`id_type[${index}]`, $item.find('.id_type').val() || '');
            formData.append(`id_number[${index}]`, $item.find('.id_number').val()?.trim() || '');
            formData.append(`old_ids[${index}]`, $item.find('.old_ids').val() || '');
            formData.append(`identy_ids[${index}]`, $item.find('.identy_ids').val() || '');
            if (file) formData.append(`id_image[${index}]`, file);
        });

            if (!isValid) {
                Toastify({
                    text: "⚠️ Please fix file errors before continuing.",
                    className: "error",
                    duration: 3000
                }).showToast();
                return resolve(false); // stops execution completely
            }
            
            showGlobalOverlay(true);

        try {
            const res = await $.ajax({
                url: window.routes.onboardSaveIdentityData,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
            });

            showGlobalOverlay(false);

            if (res.status === 200) {
                Toastify({ text: res.message || "Identity saved!", className: "success" }).showToast();
                resolve(true);
            } else {
                Toastify({ text: res.message || "Validation failed", className: "error" }).showToast();
                resolve(false);
            }
        } catch (xhr) {
            showGlobalOverlay(false);

            let msg = "❌ Upload failed";
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
            }

            Toastify({ text: msg, className: "error" }).showToast();
            resolve(false);
        }
    });
}


/*
async function saveIdentityData() {

    let sameAs      = $('#same_address').is(':checked') || false;
    let cCity       = $('#city').val();
    let cState      = $('#state').val();
    let cAddress    = $('#c_address').val();
    let pCity       = $('#p_city').val();
    let pState      = $('#p_state').val();
    let pAddress    = $('#p_address').val();


    let formData = new FormData();

    formData.append('c_city', cCity         || null);
    formData.append('c_state', cState       || null);
    formData.append('c_address', cAddress   || null);

    if (sameAs) {
        formData.append('p_city', cCity         || null);
        formData.append('p_state', cState       || null);
        formData.append('p_address', cAddress   || null);
    } else {
        formData.append('p_city', pCity         || null);
        formData.append('p_state', pState       || null);
        formData.append('p_address', pAddress   || null);
    }

    let isValid = true;
    $('#id-container .id-item').each(function (index) {
        let identy_id   = $(this).find('.identy_ids').val()     || null;
        let type        = $(this).find('.id_type').val()        || null;
        let number      = $(this).find('.id_number').val()      || null;
        let fileInput   = $(this).find('.id_image')[0];
        let oldIds      = $(this).find('.old_ids').val()        || null;
        const idNumber  = $(this).find('.id_number').val().trim().toUpperCase();

        // validateFileInput(fileInput);

        // let regex, message;

        // switch(type) {
        //     case '1': regex = /^\d{12}$/; message = 'Aadhar number must be 12 digits.'; break;
        //     case '2': regex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/; message = 'PAN format invalid (ABCDE1234F).'; break;
        //     case '3': regex = /^[A-Z0-9]{10,16}$/; message = 'Voter ID format invalid.'; break;
        //     case '4': regex = /^[A-Z0-9-]{8,20}$/; message = 'Driving Licence format invalid.'; break;
        //     case '5': regex = /^[A-Z0-9]{8,9}$/; message = 'Passport format invalid.'; break;
        // }

        // if (regex && !regex.test(idNumber)) {
        //     alert(`❌ Error in ID #${index + 1}: ${message}`);
        //     isValid = false;
        //     return false;
        // }

        if (identy_id) formData.append(`identy_ids[${index}]`, identy_id);
        if (type) formData.append(`id_type[${index}]`, type);
        if (number) formData.append(`id_number[${index}]`, number);
        if (oldIds) formData.append(`old_ids[${index}]`, oldIds);

        if (fileInput && fileInput.files.length > 0) formData.append(`id_image[${index}]`, fileInput.files[0]);

    });

    if (!isValid) return false;

    const res = await $.ajax({
        url: window.routes.onboardSaveIdentityData,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
    });

    if (res.status == 200) {
        return true;
    }

    return false;

}
*/
function deleteIdentityId(id) {
    let isConfirm = confirm('Are you sure? You want to delete it?');
    if (!isConfirm) return;

    $.ajax({
        url: window.routes.onboardDeleteIdentityId,
        type: "POST",
        data: { 'identity_id': id },
        success: function (res) {
            console.log(res);
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    })
}

async function saveEducationData(empDetailId) {

    let formData = new FormData();

    $('#education-container .education-item').each(function (index) {
        let edu_id      = $(this).find('.edu_ids').val()               || null;
        let course      = $(this).find('.courses').val()               || null;
        let inst        = $(this).find('.institutions').val()          || null;
        let subject     = $(this).find('.subjects').val()              || null;
        let grade       = $(this).find('.grades').val()                || null;
        let stDate      = $(this).find('.ed_start_dates').val()        || null;
        let endDate     = $(this).find('.ed_end_dates').val()          || null;
        let edFiles     = $(this).find('.education-files')[0];
        let oldFiles    = $(this).find('.old-education-files').val()   || null;

        formData.append('emp_detail_id', empDetailId);
        if (edu_id) formData.append(`edu_ids[${index}]`, edu_id);
        if (course) formData.append(`courses[${index}]`, course);
        if (inst) formData.append(`institutions[${index}]`, inst);
        if (subject) formData.append(`subjects[${index}]`, subject);
        if (grade) formData.append(`grades[${index}]`, grade);
        if (stDate) formData.append(`ed_start_dates[${index}]`, stDate);
        if (endDate) formData.append(`ed_end_dates[${index}]`, endDate);
        if (oldFiles) formData.append(`old_education_files[${index}]`, oldFiles);

        if (edFiles && edFiles.files.length > 0) formData.append(`education_files[${index}]`, edFiles.files[0]);

    });

    const res = await $.ajax({
        url: window.routes.onboardSaveEducations,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
    });

    if (res.status == 200) {
        Toastify({ text: res.message || "Education saved!", className: "success" }).showToast();
        return true;
    }

    return false;
}

function deleteEducation(id) {
    let isConfirm = confirm('Are you sure? You want to delete it?');
    if (!isConfirm) return;

    $.ajax({
        url: window.routes.onboardDeleteEducation,
        type: "POST",
        data: { 'education_id': id },
        success: function (res) {
            console.log(res);
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    })
}

function saveExperienceData(empDetailId) {

    let formData = new FormData();
    $('#experience-container .experience-item').each(function (index) {

        let exp_id              = $(this).find('.exp_ids').val()            || null;
        let position            = $(this).find('.positions').val()          || null;
        let company             = $(this).find('.companies').val()          || null;
        let location            = $(this).find('.locations').val()          || null;
        let person_contact      = $(this).find('.person_contact').val()     || null;
        let person_name         = $(this).find('.person_name').val()        || null;
        let emp_ids             = $(this).find('.emp_ids').val()            || null;
        let leaving_reasons     = $(this).find('.leaving_reasons').val()    || null;
        let exp_start_date      = $(this).find('.exp_start_dates').val()    || null;
        let exp_end_date        = $(this).find('.exp_end_dates').val()      || null;
        let offer_letter        = $(this).find('.offer_letters')[0];
        let appointment_letter  = $(this).find('.appointment_letters')[0];
        let experience_letter   = $(this).find('.experience_letters')[0];
        let relieving_letter    = $(this).find('.relieving_letters')[0];
        let increment_letter    = $(this).find('.increment_letters')[0];
        let salary_slip         = $(this).find('.salary_slips')[0];
        let bank_statement      = $(this).find('.bank_statements')[0];

        let old_offer_letter        = $(this).find('.old_offer_letters').val()          || null;
        let old_appointment_letter  = $(this).find('.old_appointment_letters').val()    || null;
        let old_experience_letter   = $(this).find('.old_experience_letters').val()     || null;
        let old_relieving_letter    = $(this).find('.old_relieving_letters').val()      || null;
        let old_increment_letter    = $(this).find('.old_increment_letters').val()      || null;
        let old_salary_slip         = $(this).find('.old_salary_slips').val()           || null;
        let old_bank_statement      = $(this).find('.old_bank_statements').val()        || null;


        formData.append('emp_detail_id', empDetailId);
        if (exp_id) formData.append(`exp_ids[${index}]`, exp_id);
        if (position) formData.append(`positions[${index}]`, position);
        if (company) formData.append(`companies[${index}]`, company);
        if (location) formData.append(`locations[${index}]`, location);
        if (person_contact) formData.append(`person_contacts[${index}]`, person_contact);
        if (person_name) formData.append(`person_names[${index}]`, person_name);
        if (emp_ids) formData.append(`emp_ids[${index}]`, emp_ids);
        if (leaving_reasons) formData.append(`leaving_reasons[${index}]`, leaving_reasons);
        if (exp_start_date) formData.append(`exp_start_dates[${index}]`, exp_start_date);
        if (exp_end_date) formData.append(`exp_end_dates[${index}]`, exp_end_date);

        if (offer_letter && offer_letter.files.length > 0) formData.append(`offer_letters[${index}]`, offer_letter.files[0]);
        if (appointment_letter && appointment_letter.files.length > 0) formData.append(`appointment_letters[${index}]`, appointment_letter.files[0]);
        if (experience_letter && experience_letter.files.length > 0) formData.append(`experience_letters[${index}]`, experience_letter.files[0]);
        if (relieving_letter && relieving_letter.files.length > 0) formData.append(`relieving_letters[${index}]`, relieving_letter.files[0]);
        if (increment_letter && increment_letter.files.length > 0) formData.append(`increment_letters[${index}]`, increment_letter.files[0]);
        if (salary_slip && salary_slip.files.length > 0) formData.append(`salary_slips[${index}]`, salary_slip.files[0]);
        if (bank_statement && bank_statement.files.length > 0) formData.append(`bank_statements[${index}]`, bank_statement.files[0]);

        if (old_offer_letter) formData.append(`old_offer_letters[${index}]`, old_offer_letter);
        if (old_appointment_letter) formData.append(`old_appointment_letters[${index}]`, old_appointment_letter);
        if (old_experience_letter) formData.append(`old_experience_letters[${index}]`, old_experience_letter);
        if (old_relieving_letter) formData.append(`old_relieving_letters[${index}]`, old_relieving_letter);
        if (old_increment_letter) formData.append(`old_increment_letters[${index}]`, old_increment_letter);
        if (old_salary_slip) formData.append(`old_salary_slips[${index}]`, old_salary_slip);
        if (old_bank_statement) formData.append(`old_bank_statements[${index}]`, old_bank_statement);

    });

    return $.ajax({
        url: window.routes.onboardSaveEmployement,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false
    });
}

function deleteEmployement(id) {
    let isConfirm = confirm('Are you sure? You want to delete it?');
    if (!isConfirm) return;

    $.ajax({
        url: window.routes.onboardDeleteEmployement,
        type: "POST",
        data: { 'employement_id': id },
        success: function (res) {
            console.log(res);
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    })
}

function updateFormData(section, data) {
    let formData        = getFormData();
    formData[section]   = data;
    localStorage.setItem('formData', JSON.stringify(formData));
}

window.getProgress = function () {
    let progress = localStorage.getItem('progress') ?? 0;
    $('#onboarding-progress')
        .css('width', progress + '%')
        .text(progress + "% Completed")
        .attr('aria-valuenow', progress);
}

/*
function validateFileInput($fileInput) {
    const allowedTypes = ['image/jpeg','image/jpg','image/png','image/webp','application/pdf'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    const file = $fileInput[0].files[0];
    $fileInput.removeClass('is-invalid');
    $fileInput.siblings('.file-error-message').remove();

    if (!file) return true; // no file selected, optional

    if (!allowedTypes.includes(file.type)) {
        $fileInput.addClass('is-invalid');
        $fileInput.after(`<div class="file-error-message text-danger">Invalid file type. Allowed: jpg,jpeg,png,webp,pdf</div>`);
        return false;
    }

    if (file.size > maxSize) {
        $fileInput.addClass('is-invalid');
        $fileInput.after(`<div class="file-error-message text-danger">File too large. Max size 2MB</div>`);
        return false;
    }

    return true;
}*/