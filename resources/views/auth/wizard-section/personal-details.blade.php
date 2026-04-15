@php
    $personalInfoRejectedCount = $userDetails?->bank_document_status == 2 ? 1 : null;
@endphp

<h3>
    Personal Details
    @if ($personalInfoRejectedCount)
    <span class="position-absolute end-0 top-0 bg-danger text-white rounded-circle reject-count">{{ $personalInfoRejectedCount }}</span>
    @endif
</h3>
<section style="overflow-y:scroll; overflow-x: hidden;">
    <div class="row">
        {{-- {{ dd($user) }} --}}
        <div class="col-lg-4 mb-2">
            <label for="f_name">First name *</label>
            <input id="f_name" name="f_name" value="{{ $user->firstname ?? '' }}" type="text"
                class="form-control required necessary">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="m_name">Middle name </label>
            <input id="m_name" name="m_name" value="{{ @$user->middlename }}" type="text"
                class="form-control">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="l_name">Last name *</label>
            <input id="l_name" name="l_name" value="{{ $user->lastname ?? '' }}" type="text"
                class="form-control required necessary">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="email">Email *</label>
            <input id="email" name="email" value="{{ $user->email ?? '' }}" type="email"
                class="form-control required necessary email" readonly>
        </div>

        <div class="col-lg-4 mb-2">
            <label for="gender">Gender *</label>
            <select name="gender" id="gender" class="form-select form-control required necessary">
                <option selected disabled>--Select Gender--</option>
                <option value="1" {{ $user->gender == 1 ? 'selected' : '' }}>Male</option>
                <option value="2" {{ $user->gender == 2 ? 'selected' : '' }}>Female</option>
                <option value="3" {{ $user->gender == 3 ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="col-lg-4 mb-2">
            <label for="contact">Contact *</label>
            <input id="contact" name="contact" type="text" value="{{ $user->phone ?? '' }}" maxlength="10"
                class="form-control required necessary number">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="dob">DOB *</label>
            <div class="cal-icon">
                <input id="dob" name="dob" value="{{ $userDetails->dob ?? '' }}" type="text"
                    class="form-control datepicker required necessary">
            </div>
        </div>

        <div class="col-lg-4 mb-2">
            <label for="marital_status">Marital Status *</label>
            <select name="marital_status" id="marital_status" class="form-select form-control required necessary" onchange="showChildColumn(this)">
                @php
                    use App\Enums\MaritalStatus;
                @endphp

                <option selected disabled>--Select Marital Status--  {{ $userDetails->marital_status }}</option>
                <option value="Single" {{ @$userDetails->marital_status === MaritalStatus::SINGLE ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ @$userDetails->marital_status === MaritalStatus::MARRIED ? 'selected' : '' }}>Married</option>
            </select>
        </div>

        <div class="col-lg-4 mb-2 d-none" id="child_column">
            <label for="no_of_children">Number of Child *</label>
            <input id="no_of_children" name="no_of_children" type="number" value="{{ $userDetails->no_of_children ?? '' }}"
                class="form-control required necessary number">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="joining_date">Joining Date *</label>
            <div class="cal-icon">
                <input id="joining_date" name="joining_date" value="{{ $userDetails->date_joined ?? '' }}" type="text"
                    class="form-control datepicker required necessary">
            </div>
        </div>

        <div class="col-lg-4 mb-2">
            <label for="company">Company *</label>
            @if (is_array($companies) && count($companies > 0))
            <select name="company" id="company" class="form-select form-control required necessary">
                <option selected disabled>--Select Company--</option>
                
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $company->id == @$user->company ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
            </select>
            @else

            <input id="company" name="company" type="text" value="{{ $companies }}"
                class="form-control required necessary" readonly>
            @endif
        </div>

        <div class="col-lg-4 mb-2">
            <label for="designation">Designation *</label>
            <select name="designation" id="designation" class="form-select form-control required necessary">
                @php
                    $designationList = App\Models\Designation::list();
                @endphp
                @foreach ($designationList as $designation)
                    <option value="{{ $designation->id }}" {{ @$userDetails->designation_id == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                @endforeach

            </select>
        </div>

        <div class="col-lg-4 mb-2">
            <label for="total_exp">Total Experience (In Year) *</label>
            <input id="total_exp" name="total_exp" value="{{ $userDetails->total_exp ?? '' }}" type="number"
                class="form-control required necessary" placeholder="3.5">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="blood_group">Blood Group</label>
            <select name="blood_group" id="blood_group" class="form-select form-control">
                <option value="" selected disabled>--Select Blood Group--</option>
                <option value="A+" {{ @$userDetails->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                <option value="A-" {{ @$userDetails->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                <option value="B+" {{ @$userDetails->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                <option value="B-" {{ @$userDetails->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                <option value="AB+" {{ @$userDetails->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                <option value="AB-" {{ @$userDetails->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                <option value="O+" {{ @$userDetails->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                <option value="O-" {{ @$userDetails->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
            </select>
        </div>

        <div class="col-lg-4 mb-2">
            <label for="how_know">How did you know about the opening ? *</label>
            <select name="how_know" id="how_know" class="form-select form-control required necessary">
                <option selected disabled>--Select--</option>
                <option value="1" {{ @$userDetails->know_about == 1 ? 'selected' : '' }}>Company Website</option>
                <option value="2" {{ @$userDetails->know_about == 2 ? 'selected' : '' }}>Friends or Reference</option>
                <option value="3" {{ @$userDetails->know_about == 3 ? 'selected' : '' }}>Job Portal</option>
                <option value="4" {{ @$userDetails->know_about == 4 ? 'selected' : '' }}>Social Media</option>
            </select>
        </div>

        <div class="col-lg-4 mb-2 d-flex align-items-center">
            <label for="major_illness">Do you have a history of any major illness</label>
            <input onchange="toggleIllness()" id="major_illness" name="major_illness" type="checkbox" class="form-check ms-1"
                @checked(!empty($userDetails->major_illness))>

        </div>

        <div class="col-lg-4 mb-2 d-flex align-items-center">
            <label for="refrence">Do you have any refrence ? </label>
            <input onchange="toggleReference()" id="refrence" name="refrence" type="checkbox" class="form-check ms-1"
                @checked(!empty($userDetails->ref_emp_name) || !empty($userDetails->ref_emp_id))>

        </div>

        <div class="col-lg-4 mb-2 d-none" id="specify">
            <label for="major_illness_specification">Please Specify * </label>
            <input id="major_illness_specification" name="major_illness_specification"
                value="{{ $userDetails->major_illness ?? '' }}" type="text" class="form-control required">
        </div>

        <div class="col-lg-4 mb-2 refrence-container d-none">
            <label for="emp_name">Employee Name *</label>
            <input id="emp_name" name="emp_name" value="{{ $userDetails->ref_emp_name ?? '' }}" type="text"
                class="form-control required">
        </div>

        <div class="col-lg-4 mb-2 refrence-container d-none">
            <label for="emp_id">Employee ID *</label>
            <input id="emp_id" name="emp_id" value="{{ $userDetails->ref_emp_id ?? '' }}" type="text"
                class="form-control text-uppercase required">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="photo">Passport size photo * </label>
            <input id="photo" name="photo" type="file" class="form-control {{ empty($user->avatar) ? 'required' : '' }} necessary" data-file="{{ @$user->avatar ?? '' }}" accept="image/*">

            <input type="hidden" name="old_image" id="old_image" value="{{ $user->avatar ?? '' }}">

            <div class="">
                @if ($user->avatar && file_exists(public_path('storage/' . $user->avatar)))
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Profile"
                        style="height: 100px; width: 100px; object-fit: cover;">
                @endif

                <small class="text-muted d-block mb-1"><span class="text-danger">*</span>Allowed jpg,jpeg,png,webp
                    &nbsp; max: 2MB</small>
            </div>
        </div>


    </div>

    <h4 class="mt-4">Bank Details</h4>

    <div class="row">
        <div class="col-lg-4 mb-2">
            <label for="bank_name">Bank Name *</label>
            <input id="bank_name" name="bank_name" value="{{ $userDetails->bank ?? '' }}" type="text"
                class="form-control required necessary">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="branch_address">Bank Branch Address *</label>
            <input id="branch_address" name="branch_address" value="{{ $userDetails->branch ?? '' }}" type="text"
                class="form-control required necessary">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="account_number">Account Number *</label>
            <div class="input-group">
                <input id="account_number" name="account_number" value="{{ old('account_number', !empty($userDetails->account) ? decrypt($userDetails->account) : '') }}" type="password"
                    class="form-control required necessary">
                <span class="input-group-text" id="toggle-account" style="cursor:pointer;" onclick="toggleAccount(this)">
                    <i class="fa-solid fa-eye-slash"></i>
                </span>
            </div>
        </div>

        <div class="col-lg-4 mb-2">
            <label for="account_number_confirmation">Re Enter Account Number *</label>
            <input id="account_number_confirmation" name="account_number_confirmation"
                value="{{ old('account_number', !empty($userDetails->account) ? decrypt($userDetails->account) : '') }}" type="password"
                class="form-control account_number_confirmation required necessary">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="ifsc_code">IFSC Code *</label>
            <input id="ifsc_code" name="ifsc_code" type="text" value="{{ $userDetails->ifsc ?? '' }}"
                class="form-control text-uppercase required necessary">
        </div>

        <div class="col-lg-4 mb-2">
            <label for="bank_document">Bank Document (Passbook or Cancelled Cheque) *</label>
            <input id="bank_document" name="bank_document" type="file" accept="image/*,application/pdf"
                class="form-control {{ empty($userDetails->bank_document) ? 'required' : '' }} necessary">

            <input type="hidden" name="old_bank_document" id="old_bank_document" value="{{ $userDetails->bank_document ?? '' }}">

            <div class="">
                @if(!empty($userDetails->bank_document))
                    @php
                        $signedUrl = URL::signedRoute(
                            'secure.document.view',
                            [
                                'path'     => encrypt($userDetails->bank_document),
                                'mime'     => $userDetails->bank_document_mime,
                                'filename' => basename($userDetails->bank_document),
                                'mode'     => 'clean'
                            ],
                            now()->addMinutes(5)
                        );
                    @endphp
                    {!! \App\Helpers\DocumentStatus::statusBadge($userDetails->bank_document_status) !!}
                    <a href="javascript:void(0);"
                    onclick="openSecureDocument('{{ $signedUrl }}')">
                    View Document
                    </a>

                    @if ($userDetails->bank_document_status == 2)
                        {!! \App\Helpers\DocumentStatus::remarks($userDetails->bank_document_remarks) !!}
                    @endif
                @endif
                 <small class="text-muted d-block">
                    <span class="text-danger">*</span>
                    Allowed jpg,jpeg,png,webp,pdf &nbsp; max: 2MB
                </small>
            </div>
        </div>
    </div>

</section>

@push('page-scripts')

    <script>
        function toggleIllness() {
            if ($('#major_illness').is(':checked')) {
                $('#specify').removeClass('d-none');
            } else {
                $('#specify').addClass('d-none');
            }
        }

        // For Reference
        function toggleReference() {
            if ($('#refrence').is(':checked')) {
                $('.refrence-container').removeClass('d-none');
            } else {
                $('.refrence-container').addClass('d-none');
            }
        }

        function showChildColumn(el) {
            if ($(el).val() == 'Married') {
                $('#child_column').removeClass('d-none');
            } else {
                $('#child_column').addClass('d-none');
            }
        }

        function toggleAccount(el) {
            const $input = $('#account_number');
            const $icon = $(el).find('i');

            const isHidden = $input.attr('type') === 'password';
            $input.attr('type', isHidden ? 'text' : 'password');
            $icon.toggleClass('fa-eye fa-eye-slash');
        }

        showChildColumn($('#marital_status'));

        // Run on page load
        toggleIllness();
        toggleReference();

    </script>

@endpush