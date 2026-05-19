<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetail;
use App\Models\EmployeeEducation;
use App\Models\EmployeeIdentityProof;
use App\Models\EmployeeWorkExperience;
use App\Models\User;
use App\Models\OnboardingInvitation;
use App\Models\Company;
use App\Models\DocumentAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\SecureFileUpload;
use App\Traits\uploadFile;
use App\Services\SaveDocumentAction;
use App\Models\UserOnboarding;

class OnboardController extends Controller
{
    use SecureFileUpload, uploadFile;

    public $tenant;

    public function __construct ()
    {
        $this->tenant = app()->bound('tenant') ? app('tenant') : null;
    }

    public function verifyOnboarding(Request $request) {

        $userId = decrypt($request->user_id);
        $code   = decrypt($request->code);
        $user   = User::find($userId);

        $invitation  = OnboardingInvitation::where(['user_id' => $userId, 'verification_code' => $code])->first();
        $currentDate = now();

        if (!$invitation) {
            $html = '
                <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
                    <h2 style="color: #e74c3c;">❌ Invalid Invitation</h2>
                    <p style="color: #555; font-size: 16px;">
                        Sorry, no onboarding invitation was found for this link. <br>
                        Please contact your admin to request a new invitation.
                    </p>
                </div>
            ';

            return $html;
        }

        if ($currentDate > $invitation->expired_at) {
            $html = '
                <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
                    <h2 style="color: #e74c3c;">⏰ Link Expired</h2>
                    <p style="color: #555; font-size: 16px;">
                        Sorry, this onboarding link has expired. <br>
                        Please contact your admin to get a new invitation.
                    </p>
                </div>
            ';

            return $html;
        }

        $onboarding_url = route('onboard.start', ['user_id' => encrypt($userId), 'code' => encrypt($code)]);
        session()->put('onboarding_redirect', $onboarding_url);

        return redirect()->route('login');
    }

    public function onboardingStart (Request $request)
    {
        $userId     = decrypt($request->user_id);
        $code       = decrypt($request->code);
        $authUser   = Auth::user();

        $invitation  = OnboardingInvitation::where(['user_id' => $authUser->id, 'verification_code' => $code])->first();

        if (!$invitation) {
            $notification = notify(__('Invitation Link is Invalid !'));
            return back()->with($notification);
        }

        $data = [
            'pageTitle'    => 'Welcome to Onboard',
            'user'         => $authUser,
        ];

        return view('auth.onboarding-start', $data);
    }

    public function index()
    {
        $companySettings = app(\App\Settings\CompanySettings::class);
        $companies       = Company::all();
        $companies       = count($companies) > 0 ?  $companies : $companySettings->name;

        $data['pageTitle']                  = __('Onboarding');
        $data['user']                       = Auth::user();
        $data['companies']                  = $companies;
        $data['userDetails']                = Auth::user()->employeeDetail;
        $data['employeeIdList']             = Auth::user()->employeeIdList;
        $data['employeeEducationList']      = EmployeeDetail::with('education')->where('user_id', Auth::user()->id)->first();
        $data['employeeEmployementList']    = EmployeeDetail::with('workExperience')->where('user_id', Auth::user()->id)->first();
        $data['companySettings']            = $companySettings;

        // dd($data['employeeEducationList']);
        return view("auth.onboard", $data);
    }

    public function acceptTermCondition (Request $request) {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status'    => 400,
                'message'   => 'User Not Found!'
            ]);
        }

        // $user->update(['is_term_accepted' => $request->boolean('is_term_accepted')]);

        UserOnboarding::updateOrCreate(
            ['user_id' => $user->id],
            [
                'started_at' => now(),
                'term_accepted_at' => now()
            ]
        );

        return response()->json([
            'status'    => 200,
            'message'   => 'Terms & Conditions Accepted Successfully'
        ]);
    }

    public function savePersonalDetails(Request $request)
    {
        
        if ($request->employee_id && $request->employee_detail_id) {
            $authUser = User::find($request->employee_id);
        }else {
            $authUser = Auth::user();
        }
        

        $validate = $request->validate([
            // personal details
            'first_name'            => 'required|string|min:3',
            'last_name'             => 'required|string',
            'middle_name'           => 'nullable|string',
            'email'                 => 'required|email|string',
            'contact'               => 'required|numeric',
            'dob'                   => 'required',
            'company'               => 'required',
            'photo'                 => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'bank_document'         => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        if (!$validate) {
            return response()->json([
                'status'    => 400,
                'message'   => 'Please Fill Required Fields'
            ]);
        }

        if ($request->hasFile('photo')) {
            $path = $this->tenant->id . '/'. $authUser->id . '/';
            $validate['photo'] = self::upload($request->file('photo'), $path, $authUser->avatar ?? '');
        } else {
            $validate['photo'] = $request->old_image ?? null;
        }

        if ($request->hasFile('bank_document')) {
            $oldBankDocument = $authUser->employeeDetail?->bank_document ?? null;
            $path = $this->tenant->id . '/'. $authUser->id . '/';
            $upload = self::uploadEncrypted($request->file('bank_document'), $path, $oldBankDocument);

            $bankDocumentPath = $upload['path'] ?? $oldBankDocument;
            $bankDocumentMime = $upload['mime'] ?? null;
        } else {
            $bankDocumentPath = $authUser->employeeDetail?->bank_document ?? null;
            $bankDocumentMime = $authUser->employeeDetail?->bank_document_mime ?? null; 
        }

        $personalUserData = [
            'firstname'         => $validate['first_name']  ?? null,
            'middlename'        => $validate['middle_name'] ?? '',
            'lastname'          => $validate['last_name']   ?? null,
            'email'             => $validate['email']       ?? null,
            'gender'            => $request->gender         ?? null,
            'dob'               => $request->dob            ?? null,
            'phone'             => $validate['contact']     ?? null,
            'company'           => $validate['company']     ?? null,
            'avatar'            => $validate['photo']       ?? null,
            'avatar'            => $validate['photo']       ?? null,
        ];

        $personalUserDetailsData = [
            'dob'                   => $request->dob                        ?? null,
            'marital_status'        => $request->marital_status             ?? null,
            'no_of_children'        => $request->no_of_children             ?? null,
            'total_exp'             => $request->total_exp                  ?? null,
            'blood_group'           => $request->blood_group                ?? null,
            'know_about'            => $request->how_know                   ?? null,
            'major_illness'         => $request->specification              ?? null,
            'ref_emp_name'          => $request->ref_emp_name               ?? null,
            'ref_emp_id'            => $request->ref_emp_id                 ?? null,
            'bank'                  => $request->bank_name                  ?? null,
            'branch'                => $request->branch_address             ?? null,
            'account'               => $request->account_number ? encrypt($request->account_number) : null,
            'ifsc'                  => $request->ifsc_code                  ?? null,
            'bank_document'         => $bankDocumentPath,
            'bank_document_mime'    => $bankDocumentMime,     
        ];

        $user       = User::updateOrCreate(['id' => $authUser->id], $personalUserData);
        $userDetails = EmployeeDetail::updateOrCreate(['user_id' => $authUser->id], $personalUserDetailsData);

        if ($request->filled('bank_document_status')) {
            SaveDocumentAction::save(
                $userDetails,
                'bank_document',
                $request->bank_document_status,
                $request->bank_document_remarks ?? null,
                $authUser->id
            );
        }

        if (!$user || !$userDetails) {
            return response()->json([
                'status'    => 500,
                'message'   => 'Something went wrong !'
            ]);
        }

        return response()->json([
            'status'    => 200,
            'message'   => 'Personal Data Saved Successfully'
        ]);
    }

    public function saveIdentityDetails(Request $request)
    {

        if ($request->user_id) {
            $userId = $request->user_id;
        }else {
            $userId = Auth::user()->id;
        }

        $validate = $request->validate([
            'c_city'        => 'required',
            'c_state'       => 'required',
            'c_address'     => 'required',
            'p_city'        => 'required',
            'p_state'       => 'required',
            'p_address'     => 'required',
            'id_type.*'     => 'required',
            'id_number.*'   => 'required',
            'id_image.*'    => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:2048',

        ]);

        if (!$validate) {
            return response()->json([
                'status'    => 400,
                'message'   => 'Please Fill Required Details !',
            ]);
        }

        $userDetailsData = [
            'current_city'      => $validate['c_city']      ?? null,
            'current_state'     => $validate['c_state']     ?? null,
            'current_address'   => $validate['c_address']   ?? null,
            'permanent_city'    => $validate['p_city']      ?? null,
            'permanent_state'   => $validate['p_state']     ?? null,
            'permanent_address' => $validate['p_address']   ?? null,
        ];

        $idTypes    = $request->input('id_type',    []);
        $idNumbers  = $request->input('id_number',  []);
        $idImages   = $request->file('id_image',    []);
        $oldIds     = $request->input('old_ids',    []);
        $identy_ids = $request->input('identy_ids', []);
        $statuses   = $request->input('status',     []);
        $remarks    = $request->input('remarks',    []);

        if (count($idTypes) > 0) {
            foreach ($idTypes as $index => $idType) {
                $number     = encrypt($idNumbers[$index])   ?? null;
                $type       = $idTypes[$index]              ?? null;
                $file       = $idImages[$index]             ?? null;
                $oldFile    = $oldIds[$index]               ?? null;
                $status     = $statuses[$index]             ?? null;
                $remark     = $remarks[$index]              ?? null;

                $fileName   = null;
                $idName     = null;
                $filePath = $oldFile ?? null;
                $fileMime = null;

                switch ($type) {
                    case '1':
                        $idName = 'Adhar Card';
                        break;
                    case '2':
                        $idName = 'PAN Card';
                        break;
                    case '3':
                        $idName = 'Voter ID Card';
                        break;
                    case '4':
                        $idName = 'Driving Licence';
                        break;
                    case '5':
                        $idName = 'Passport';
                        break;
                }

                $existing = EmployeeIdentityProof::find($identy_ids[$index] ?? null);

                if ($file) {
                    $path = $this->tenant->id . '/' . $userId . '/employee_ids/';
                    $upload = self::uploadEncrypted($file, $path, $oldFile);

                    $filePath = $upload['path'] ?? $oldFile;
                    $fileMime = $upload['mime'] ?? null;
                } else {
                    $filePath = $oldFile;
                    $fileMime = $existing->document_mime ?? null; 
                }

                $empId = EmployeeIdentityProof::updateOrCreate(
                    [
                        'id'   => $identy_ids[$index] ?? null,
                    ],
                    [
                        'user_id'       => $userId, 
                        'id_type'       => $type, 
                        'id_name'       => $idName, 
                        'id_number'     => $number, 
                        'image'         => $filePath,  
                        'document_mime' => $fileMime,
                    ]
                );

                if (!is_null($status)) {
                    SaveDocumentAction::save(
                        $empId,
                        $idName,
                        $status,
                        $remark,
                        $userId
                    );
                }
            }
        }

        $userDetails = EmployeeDetail::updateOrCreate(['user_id' => $userId], $userDetailsData);

        if (!$userDetails) {
            return response()->json([
                'status'    => 500,
                'message'   => 'Something went wrong !'
            ]);
        }

        return response()->json([
            'status'    => 200,
            'message'   => 'employee IDs saved successfully'
        ]);
    }

    public function deleteIdentityId(Request $request)
    {
        $id = $request->input('identity_id');

        if (empty($id)) {
            return response()->json([
                'status'  => 404,
                'message' => 'Data not found!'
            ]);
        }

        $identityId = EmployeeIdentityProof::find($id);

        if (!$identityId) {
            return response()->json([
                'status'  => 404,
                'message' => 'Identity record not found!'
            ]);
        }

        if (!empty($identityId->image)) {
            self::deleteEncrypted($identityId->image);
        }

        $identityId->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Identity record deleted successfully'
        ]);
    }


    public function saveEducationalDetails(Request $request)
    {
        $validate = $request->validate([
            'courses.*'         => 'required',
            'institutions.*'    => 'required',
            'subjects.*'        => 'required',
            'grades.*'          => 'required',
            'ed_start_dates.*'  => 'required|date',
            'ed_end_dates.*'    => 'required|date',
            'education_files.*' => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        if (!$validate) {
            return response()->json([
                'status'    => 400,
                'message'   => 'Please Fill Required Fields'
            ]);
        }
        $userId = auth()->id();

        $edu_ids            = $request->input('edu_ids',                []);
        $institutions       = $request->input('institutions',           []);
        $subjects           = $request->input('subjects',               []);
        $courses            = $request->input('courses',                []);
        $grades             = $request->input('grades',                 []);
        $startDates         = $request->input('ed_start_dates',         []);
        $endDates           = $request->input('ed_end_dates',           []);
        $files              = $request->file('education_files',         []);
        $oldFiles           = $request->input('old_education_files',    []);
        $employeeDetailId   = $request->input('emp_detail_id');

        if (count($institutions) > 0) {
            foreach ($institutions as $index => $institution) {

                $existing = EmployeeEducation::find($edu_ids[$index] ?? null);

                $oldFile = $oldFiles[$index] ?? null;

                $filePath = $oldFile;
                $fileMime = $existing->document_mime ?? null;

                if (isset($files[$index])) {

                    $path = $this->tenant->id . '/' . $userId . '/education/';

                    $upload = self::uploadEncrypted(
                        $files[$index],
                        $path,
                        $oldFile
                    );

                    $filePath = $upload['path'] ?? $oldFile;
                    $fileMime = $upload['mime'] ?? $fileMime;
                }

                $data = [
                    'employee_detail_id'    => $employeeDetailId,
                    'institution'           => $institution,
                    'subject'               => $subjects[$index] ?? null,
                    'course'                => $courses[$index] ?? null,
                    'grade'                 => $grades[$index] ?? null,
                    'file'                  => $filePath,
                    'document_mime'         => $fileMime,
                    'start_date'            => $startDates[$index] ?? null,
                    'end_date'              => $endDates[$index] ?? null,
                ];

                $where = [];

                if (!empty($edu_ids[$index])) {
                    $where = ['id' => $edu_ids[$index]];
                } else {
                    $where = [
                        'employee_detail_id' => $employeeDetailId,
                        'course' => $courses[$index] ?? null,
                    ];
                }

                EmployeeEducation::updateOrCreate($where, $data);
            }
        }

        return response()->json(['status' => 200, 'message' => 'Educational details saved']);

    }

   public function deleteEducation(Request $request)
    {
        $id = $request->input('education_id');

        if (empty($id)) {
            return response()->json([
                'status'  => 404,
                'message' => 'Data not found!'
            ]);
        }

        $education = EmployeeEducation::where('id', $id)
            ->first();

        if (!$education) {
            return response()->json([
                'status'  => 404,
                'message' => 'Education record not found!'
            ]);
        }

        if (!empty($education->file)) {
            self::deleteEncrypted($education->file);
        }

        $education->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Education deleted successfully'
        ]);
    }


    public function saveEmployementDetails(Request $request)
    {
        $validate = $request->validate([
            'positions.*'           => 'required',
            'companies.*'           => 'required',
            'locations.*'           => 'required',
            'exp_start_dates.*'     => 'required',
            'exp_end_dates.*'       => 'required',
            'offer_letters.*'       => 'nullable|mimes:pdf|max:2048',
            'appointment_letters.*' => 'nullable|mimes:pdf|max:2048',
            'experience_letters.*'  => 'nullable|mimes:pdf|max:2048',
            'relieving_letters.*'   => 'nullable|mimes:pdf|max:2048',
            'increment_letters.*'   => 'nullable|mimes:pdf|max:2048',
            'salary_slips.*'        => 'nullable|mimes:pdf|max:2048',
            'bank_statements.*'     => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:2048',

        ]);

        // dd($request->all());

        if (!$validate) {
            return response()->json([
                'status'    => 400,
                'message'   => 'Please Fill Required Fields !'
            ]);
        }

        if ($request->input('user_id')) {
            $userId = $request->input('user_id');
        }else {
            $userId = auth()->id();
        }

        $exp_ids                    = $request->input('exp_ids',            []);
        $positions                  = $request->input('positions',          []);
        $companies                  = $request->input('companies',          []);
        $locations                  = $request->input('locations',          []);
        $exp_start_dates            = $request->input('exp_start_dates',    []);
        $exp_end_dates              = $request->input('exp_end_dates',      []);
        $person_names               = $request->input('person_names',       []);
        $person_contacts            = $request->input('person_contacts',    []);
        $emp_ids                    = $request->input('emp_ids',            []);
        $leaving_reasons            = $request->input('leaving_reasons',    []);

        $offer_letters              = $request->file('offer_letters',       []);
        $appointment_letters        = $request->file('appointment_letters', []);
        $experience_letters         = $request->file('experience_letters',  []);
        $relieving_letters          = $request->file('relieving_letters',   []);
        $increment_letters          = $request->file('increment_letters',   []);
        $salary_slips               = $request->file('salary_slips',        []);
        $bank_statements            = $request->file('bank_statements',     []);

        $old_offer_letters          = $request->input('old_offer_letters',          []);
        $old_appointment_letters    = $request->input('old_appointment_letters',    []);
        $old_experience_letters     = $request->input('old_experience_letters',     []);
        $old_relieving_letters      = $request->input('old_relieving_letters',      []);
        $old_increment_letters      = $request->input('old_increment_letters',      []);
        $old_salary_slips           = $request->input('old_salary_slips',           []);
        $old_bank_statements        = $request->input('old_bank_statements',        []);

        $offer_statuses             = $request->input('offer_status',               []);
        $offer_remarks              = $request->input('offer_remarks',              []);
        $appointment_statuses       = $request->input('appointment_status',         []);
        $appointment_remarks        = $request->input('appointment_remarks',        []);
        $experience_statuses        = $request->input('experience_status',          []);
        $experience_remarks         = $request->input('experience_remarks',         []);
        $relieving_statuses         = $request->input('relieving_status',           []);
        $relieving_remarks          = $request->input('relieving_remarks',          []);
        $increment_statuses         = $request->input('increment_status',           []);
        $increment_remarks          = $request->input('increment_remarks',          []);
        $salary_statuses            = $request->input('salary_status',              []);
        $salary_remarks             = $request->input('salary_remarks',             []);
        $bank_statuses              = $request->input('bank_status',                []);
        $bank_remarks               = $request->input('bank_remarks',               []);

        $uploadPath = $this->tenant->id . '/'. $userId . '/work-experience/';

        if (count($companies) > 0) {

            foreach ($companies as $index => $company) {
                $existing = EmployeeWorkExperience::find($exp_ids[$index] ?? null);

                $offerLatterName        = null;
                $appointmentLatterName  = null;
                $experienceLatterName   = null;
                $relieving_letterName   = null;
                $incrementLatterName    = null;
                $salarySlipName         = null;
                $bankStatementName      = null;

                $oldOfferLetterName         = $old_offer_letters[$index]        ?? null;
                $oldAppointmentLatterName   = $old_appointment_letters[$index]  ?? null;
                $oldExperienceLatterName    = $old_experience_letters[$index]   ?? null;
                $oldrelieving_letterName    = $old_relieving_letters[$index]    ?? null;
                $oldIncrementLatterName     = $old_increment_letters[$index]    ?? null;
                $oldSalarySlipName          = $old_salary_slips[$index]         ?? null;
                $oldBankStatementName       = $old_bank_statements[$index]      ?? null;


               // ===== OFFER LETTER =====
                if (isset($offer_letters[$index])) {

                    $upload = self::uploadEncrypted(
                        $offer_letters[$index],
                        $uploadPath,
                        $oldOfferLetterName
                    );

                    $offerLatterName = $upload['path'] ?? $oldOfferLetterName;
                    $offerLetterMime = $upload['mime'] ?? null;

                } else {
                    $offerLatterName = $oldOfferLetterName;
                    $offerLetterMime = $existing->offer_letter_mime ?? null;
                }


                // ===== APPOINTMENT LETTER =====
                if (isset($appointment_letters[$index])) {

                    $upload = self::uploadEncrypted(
                        $appointment_letters[$index],
                        $uploadPath,
                        $oldAppointmentLatterName
                    );

                    $appointmentLatterName = $upload['path'] ?? $oldAppointmentLatterName;
                    $appointmentLetterMime = $upload['mime'] ?? null;

                } else {
                    $appointmentLatterName = $oldAppointmentLatterName;
                    $appointmentLetterMime = $existing->appointment_letter_mime ?? null;
                }


                // ===== EXPERIENCE LETTER =====
                if (isset($experience_letters[$index])) {

                    $upload = self::uploadEncrypted(
                        $experience_letters[$index],
                        $uploadPath,
                        $oldExperienceLatterName
                    );

                    $experienceLatterName = $upload['path'] ?? $oldExperienceLatterName;
                    $experienceLetterMime = $upload['mime'] ?? null;

                } else {
                    $experienceLatterName = $oldExperienceLatterName;
                    $experienceLetterMime = $existing->experience_letter_mime ?? null;
                }


                // ===== RELIEVING LETTER =====
                if (isset($relieving_letters[$index])) {

                    $upload = self::uploadEncrypted(
                        $relieving_letters[$index],
                        $uploadPath,
                        $oldrelieving_letterName
                    );

                    $relieving_letterName = $upload['path'] ?? $oldrelieving_letterName;
                    $relievingLetterMime  = $upload['mime'] ?? null;

                } else {
                    $relieving_letterName = $oldrelieving_letterName;
                    $relievingLetterMime = $existing->relieving_letter_mime ?? null;
                }


                // ===== INCREMENT LETTER =====
                if (isset($increment_letters[$index])) {

                    $upload = self::uploadEncrypted(
                        $increment_letters[$index],
                        $uploadPath,
                        $oldIncrementLatterName
                    );

                    $incrementLatterName = $upload['path'] ?? $oldIncrementLatterName;
                    $incrementLetterMime = $upload['mime'] ?? null;

                } else {
                    $incrementLatterName = $oldIncrementLatterName;
                    $incrementLetterMime = $existing->increment_letter_mime ?? null;
                }


                // ===== SALARY SLIP =====
                if (isset($salary_slips[$index])) {

                    $upload = self::uploadEncrypted(
                        $salary_slips[$index],
                        $uploadPath,
                        $oldSalarySlipName
                    );

                    $salarySlipName = $upload['path'] ?? $oldSalarySlipName;
                    $salarySlipMime = $upload['mime'] ?? null;

                } else {
                    $salarySlipName = $oldSalarySlipName;
                    $salarySlipMime = $existing->salary_slip_mime ?? null;
                }


                // ===== BANK STATEMENT =====
                if (isset($bank_statements[$index])) {

                    $upload = self::uploadEncrypted(
                        $bank_statements[$index],
                        $uploadPath,
                        $oldBankStatementName
                    );

                    $bankStatementName = $upload['path'] ?? $oldBankStatementName;
                    $bankStatementMime = $upload['mime'] ?? null;

                } else {
                    $bankStatementName = $oldBankStatementName;
                    $bankStatementMime = $existing->bank_statement_mime ?? null;
                }

                $data = [
                    'employee_detail_id'    => $request->input('emp_detail_id'),
                    'company'               => $companies[$index]       ?? null,
                    'location'              => $locations[$index]       ?? null,
                    'position'              => $positions[$index]       ?? null,
                    'start_date'            => $exp_start_dates[$index] ?? null,
                    'end_date'              => $exp_end_dates[$index]   ?? null,
                    'person_name'           => $person_names[$index]    ?? null,
                    'person_contact'        => $person_contacts[$index] ?? null,
                    'employee_id'           => $emp_ids[$index]         ?? null,
                    'leaving_reason'        => $leaving_reasons[$index] ?? null,

                    'offer_letter'          => $offerLatterName,
                    'offer_letter_mime'     => $offerLetterMime ?? null,

                    'appointment_letter'       => $appointmentLatterName,
                    'appointment_letter_mime'  => $appointmentLetterMime ?? null,

                    'experience_letter'        => $experienceLatterName,
                    'experience_letter_mime'   => $experienceLetterMime ?? null,

                    'relieving_letter'      => $relieving_letterName,
                    'relieving_letter_mime' => $relievingLetterMime ?? null,

                    'increment_letter'      => $incrementLatterName,
                    'increment_letter_mime' => $incrementLetterMime ?? null,

                    'salary_slip'           => $salarySlipName,
                    'salary_slip_mime'      => $salarySlipMime ?? null,

                    'bank_statement'        => $bankStatementName,
                    'bank_statement_mime'   => $bankStatementMime ?? null,
                ];

                $exp = EmployeeWorkExperience::updateOrCreate(
                    [
                        'id' => $exp_ids[$index] ?? null, 
                    ],
                    $data
                );

                $documents = [
                    'offer_letter' => [$offer_statuses[$index] ?? null, $offer_remarks[$index] ?? null],
                    'appointment_letter' => [$appointment_statuses[$index] ?? null, $appointment_remarks[$index] ?? null],
                    'experience_letter' => [$experience_statuses[$index] ?? null, $experience_remarks[$index] ?? null],
                    'relieving_letter' => [$relieving_statuses[$index] ?? null, $relieving_remarks[$index] ?? null],
                    'increment_letter' => [$increment_statuses[$index] ?? null, $increment_remarks[$index] ?? null],
                    'salary_slip' => [$salary_statuses[$index] ?? null, $salary_remarks[$index] ?? null],
                    'bank_statement' => [$bank_statuses[$index] ?? null, $bank_remarks[$index] ?? null],
                ];

                $hasStatusUpdate =
                $request->has('offer_status') ||
                $request->has('appointment_status') ||
                $request->has('experience_status') ||
                $request->has('relieving_status') ||
                $request->has('increment_status') ||
                $request->has('salary_status') ||
                $request->has('bank_status');

                if ($hasStatusUpdate) {
                    foreach ($documents as $type => [$status, $remark]) {

                        $existingDoc = $exp->documentActions()
                            ->where('document_type', $type)
                            ->first();

                        if ($existingDoc &&
                            $existingDoc->status == $status &&
                            $existingDoc->remark == $remark) {
                            continue;
                        }

                        if (!is_null($status)) {
                            SaveDocumentAction::save($exp, $type, $status, $remark, $userId);
                        }
                    }
                }

            }

        }

        if (!$request->input('user_id')) {
            UserOnboarding::updateOrCreate(
                ['user_id' => $userId],
                [
                    'completed_at' => now(),
                ]
            );
        }
        
        return response()->json(['status' => 200, 'message' => 'Employement details saved']);
    }

   public function deleteEmployement(Request $request)
    {
        $id = $request->employement_id;

        if (empty($id)) {
            return response()->json([
                'status'  => 404,
                'message' => 'Employment record not found!'
            ]);
        }

        $employment = EmployeeWorkExperience::find($id);

        if (!$employment) {
            return response()->json([
                'status'  => 404,
                'message' => 'Employment record not found!'
            ]);
        }

        // All stored file columns
        $files = [
            $employment->offer_letter,
            $employment->appointment_letter,
            $employment->experience_letter,
            $employment->relieving_letter,
            $employment->increment_letter,
            $employment->salary_slip,
            $employment->bank_statement,
        ];

        foreach ($files as $file) {
            if (!empty($file)) {
                self::deleteEncrypted($file);
            }
        }

        $employment->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Employment record deleted successfully!'
        ]);
    }


    public function onboardingWelcome (Request $request)
    {
        $companySettings = app(\App\Settings\CompanySettings::class);
        $data = [
            'pageTitle'         => 'Welcome Aboard',
            'user'              => User::findOrFail(decrypt($request->user_id)),
            'companySettings'   => $companySettings,
        ];

        return view('auth.onboarding-complete', $data);
    }

}