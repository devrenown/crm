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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\uploadFile;

class OnboardController extends Controller
{
    use UploadFile;

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

        $data['pageTitle']                  = __('Onboarding');
        $data['user']                       = Auth::user();
        $data['companies']                  = Company::all();
        $data['userDetails']                = Auth::user()->employeeDetail;
        $data['employeeIdList']             = Auth::user()->employeeIdList;
        $data['employeeEducationList']      = EmployeeDetail::with('education')->where('user_id', Auth::user()->id)->first();
        $data['employeeEmployementList']    = EmployeeDetail::with('workExperience')->where('user_id', Auth::user()->id)->first();
        $data['companySettings']            = $companySettings;

        // dd($data['employeeEducationList']);
        return view("auth.onboard", $data);
    }

    public function acceptTermCondition (Request $request) {
        $user = User::findOrFail(Auth::user()->id);

        if (!$user) {
            return response()->json([
                'status'    => 400,
                'message'   => 'User Not Found!'
            ]);
        }

        $user->update(['is_term_accepted' => $request->boolean('is_term_accepted')]);
        return response()->json([
            'status'    => 200,
            'message'   => 'Terms & Conditions Accepted Successfully'
        ]);
    }

    public function savePersonalDetails(Request $request)
    {
        
        // dd($request->all());
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
            'designation'           => 'required',
            'joining_date'          => 'required',
            'photo'                 => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (!$validate) {
            return response()->json([
                'status'    => 400,
                'message'   => 'Please Fill Required Fields'
            ]);
        }

        if ($request->hasFile('photo')) {
            $validate['photo'] = self::upload($request->file('photo'), 'storage/users/', $authUser->avatar ?? '');
        } else {
            $validate['photo'] = $request->old_image ?? null;
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
        ];

        $personalUserDetailsData = [
            'dob'                   => $request->dob                        ?? null,
            'marital_status'        => $request->marital_status             ?? null,
            'no_of_children'        => $request->no_of_children             ?? null,
            'date_joined'           => $request->joining_date               ?? null,
            'designation_id'        => $request->designation                ?? null,
            'total_exp'             => $request->total_exp                  ?? null,
            'blood_group'           => $request->blood_group                ?? null,
            'know_about'            => $request->how_know                   ?? null,
            'major_illness'         => $request->specification              ?? null,
            'ref_emp_name'          => $request->ref_emp_name               ?? null,
            'ref_emp_id'            => $request->ref_emp_id                 ?? null,
            'bank'                  => $request->bank_name                  ?? null,
            'branch'                => $request->branch_address             ?? null,
            'account'               => encrypt($request->account_number)    ?? null,
            'ifsc'                  => $request->ifsc_code                  ?? null,
            'marital_status'        => $request->marital_status             ?? null,
            'no_of_children'        => $request->no_of_children             ?? null,
        ];

        $user       = User::updateOrCreate(['id' => $authUser->id], $personalUserData);
        $userDetals = EmployeeDetail::updateOrCreate(['user_id' => $authUser->id], $personalUserDetailsData);

        if (!$user || !$userDetals) {
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
        // dd($request->all());

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

        if (count($idTypes) > 0) {
            foreach ($idTypes as $index => $idType) {
                $number     = encrypt($idNumbers[$index])   ?? null;
                $type       = $idTypes[$index]              ?? null;
                $file       = $idImages[$index]             ?? null;
                $oldFile    = $oldIds[$index]               ?? null;

                $fileName   = null;
                $idName     = null;

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

                if ($file) {
                    $fileName = self::upload($file, 'upload/employee_ids/', $oldFile);
                } else {
                    $fileName = $oldFile;
                }

                $empId = EmployeeIdentityProof::updateOrCreate(
                    [
                        'id'   => $identy_ids[$index] ?? null,
                    ],
                    ['user_id' => $userId, 'id_type' => $type, 'id_name' => $idName, 'id_number' => $number, 'image' => $fileName]
                );
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
                'status'    => 404,
                'message'   => 'Data not found !'
            ]);
        }

        $identityId = EmployeeIdentityProof::find($id);

        $deleteFile = self::delete($identityId->image, 'upload/employee_ids/');

        if (!$deleteFile) {
            return response()->json([
                'status'    => 500,
                'message'   => 'something went wrong'
            ]);
        }

        $identityId->delete();
    }

    public function saveEducationalDetails(Request $request)
    {
        // dd($request->all());

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

                $fileName   = null;
                $oldFile    = $oldFiles[$index] ?? null;

                if (isset($files[$index])) {
                    $fileName = self::upload($files[$index], 'storage/employees/education/', $oldFile);
                } else {
                    $fileName = $oldFile;
                }

                $data = [
                    'employee_detail_id'    => $employeeDetailId,
                    'institution'           => $institution,
                    'subject'               => $subjects[$index] ?? null,
                    'course'                => $courses[$index] ?? null,
                    'grade'                 => $grades[$index] ?? null,
                    'file'                  => $fileName,
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
                'status'    => 404,
                'message'   => 'Data not found !'
            ]);
        }

        $education = EmployeeEducation::find($id);
        $filePath = public_path('storage/employees/education/' . $education->file);

        if (!empty($education->file) && file_exists($filePath)) {
            $deleteFile = self::delete($education->file, 'storage/employees/education/');
            
            if (!$deleteFile) {
                return response()->json([
                    'status'    => 500,
                    'message'   => 'something went wrong'
                ]);
            }
        }

        $education->delete();
    }

    public function saveEmployementDetails(Request $request)
    {
        // dd($request->all());

        $validate = $request->validate([
            'positions.*'           => 'required',
            'companies.*'           => 'required',
            'locations.*'           => 'required',
            'person_names.*'        => 'required',
            'person_contacts.*'     => 'required',
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

        if (!$validate) {
            return response()->json([
                'status'    => 400,
                'message'   => 'Please Fill Required Fields !'
            ]);
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

        $uploadPath = 'storage/employees/work-experience/';


        if (count($companies) > 0) {

            foreach ($companies as $index => $company) {

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


                if (isset($offer_letters[$index])) {
                    $offerLatterName = self::upload($offer_letters[$index], $uploadPath, $oldOfferLetterName);
                } else {
                    $offerLatterName = $oldOfferLetterName;
                }

                if (isset($appointment_letters[$index])) {
                    $appointmentLatterName = self::upload($appointment_letters[$index], $uploadPath, $oldAppointmentLatterName);
                } else {
                    $appointmentLatterName = $oldAppointmentLatterName;
                }

                if (isset($experience_letters[$index])) {
                    $experienceLatterName = self::upload($experience_letters[$index], $uploadPath, $oldExperienceLatterName);
                } else {
                    $experienceLatterName = $oldExperienceLatterName;
                }

                if (isset($relieving_letters[$index])) {
                    $relieving_letterName = self::upload($relieving_letters[$index], $uploadPath, $oldrelieving_letterName);
                } else {
                    $relieving_letterName = $oldrelieving_letterName;
                }

                if (isset($increment_letters[$index])) {
                    $incrementLatterName = self::upload($increment_letters[$index], $uploadPath, $oldIncrementLatterName);
                } else {
                    $incrementLatterName = $oldIncrementLatterName;
                }

                if (isset($salary_slips[$index])) {
                    $salarySlipName = self::upload($salary_slips[$index], $uploadPath, $oldSalarySlipName);
                } else {
                    $salarySlipName = $oldSalarySlipName;
                }

                if (isset($bank_statements[$index])) {
                    $bankStatementName = self::upload($bank_statements[$index], $uploadPath, $oldBankStatementName);
                } else {
                    $bankStatementName = $oldBankStatementName;
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
                    'appointment_letter'    => $appointmentLatterName,
                    'experience_letter'     => $experienceLatterName,
                    'relieving_letter'      => $relieving_letterName,
                    'increment_letter'      => $incrementLatterName,
                    'salary_slip'           => $salarySlipName,
                    'bank_statement'        => $bankStatementName,
                ];

                EmployeeWorkExperience::updateOrCreate(
                    [
                        'id' => $exp_ids[$index] ?? null,   // look up by id if available
                    ],
                    $data
                );

            }

        }

        return response()->json(['status' => 200, 'message' => 'Employement details saved']);
    }

    public function deleteEmployement(Request $request)
    {

        if ($request->employement_id) {
            $id = $request->employement_id;
        }else {
            $id = $request->input('employement_id');
        }

        if (empty($id)) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Employment record not found!'
            ]);
        }

        $employment = EmployeeWorkExperience::find($id);

        if (!$employment) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Employment record not found!'
            ]);
        }

        // All file fields you store
        $files = [
            $employment->offer_letter,
            $employment->appointment_letter,
            $employment->experience_letter,
            $employment->relieving_letter,
            $employment->increment_letter,
            $employment->salary_slip,
            $employment->bank_statement,
        ];

        // Delete each file safely
        foreach ($files as $file) {
            if (!empty($file)) {
                self::delete($file, 'storage/employees/work-experience/');
            }
        }

        // Delete DB record
        $employment->delete();

        return response()->json([
            'status'    => 200,
            'message'   => 'Employment record deleted successfully!'
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