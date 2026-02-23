@extends('layouts.app')

@push('page-styles')

<style>
    .page-wrapper {
        margin: 0 !important;
    }
    .text-primary {
      color: #6877E0 !important;
    }

    .onboarding-card {
      max-width: 80%;
      margin: 80px auto;
      padding: 40px;
      border-radius: 12px;
      background: #fff;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .steps p {
      margin-bottom: 10px;
    }

    .custom-list {
      list-style: square;
      padding-left: 20px;
    }

    .fa-circle {
      font-size: 8px;
    }

    @media (max-width: 990px) {
        .onboarding-card {
          max-width: 100%;
          padding: 20px;
        }
    }

</style>

@endpush

@section('page-content')

  <div class="content container">
    <div class="onboarding-card text-center position-relative">

      <!-- Company Logo -->
      <img src="{{ $user->avatar ? asset('storage/users/' . $user->avatar) : asset('images/user.jpg') }}" 
          alt="User" 
          class="rounded-circle position-absolute border bg-white" 
          style="height: 5rem; width: 5rem; top: -2.5rem; transform: translate(-50%);">

      <!-- Title -->
      <h3 class="fw-bold mt-5 mt-lg-3">Employee Onboarding Guide</h3>
      <p class="text-muted">
         Welcome to the team! <i class="fa-solid fa-champagne-glasses text-primary"></i>
      </p>

      <div class="mt-4 text-start">

          <p>Before you begin, please take a few minutes to carefully complete your onboarding form. The information you provide will help us set up your employee profile, payroll, and internal records.</p>

          <h5 class="mt-4 fs-4"><i class="fa-solid fa-hand-point-right text-primary me-2"></i> Important Instructions</h5>
          <ul class="mb-3 ms-2">
            <li><i class="fa-solid fa-circle me-2"></i>Fill in all mandatory fields marked with an asterisk (*) accurately.</li>
            <li><i class="fa-solid fa-circle me-2"></i>Upload clear and valid documents only in the supported formats:</li>
            <ul class="ms-3 mb-3">
              <li>i) Images: .jpg, .jpeg, .png, .webp</li>
              <li>ii) Documents: .pdf</li>
              <li>iii) Max File Size: 2MB per file</li>
            </ul>
            <li><i class="fa-solid fa-circle me-2"></i>Review all details before submission — especially your email, contact number, bank account, and IFSC code.</li>
            <li><i class="fa-solid fa-circle me-2"></i>Select appropriate options from dropdowns (e.g., Company, Blood Group, Source of Opening).</li>
            <li><i class="fa-solid fa-circle me-2"></i>Ensure educational and professional experience details are accurate and supported by valid documents.</li>
            <li><i class="fa-solid fa-circle me-2"></i>If you face any issues, contact the HR department before submitting.</li>
          </ul>

          <h5 class="mt-4 fs-4"><i class="fa-solid fa-hand-point-right text-primary me-2"></i> Sections You’ll Complete</h5>
          <ul class="mb-3 ms-2">
            <li><i class="fa-solid fa-circle me-2"></i>Term & Conditions</li>
            <li><i class="fa-solid fa-circle me-2"></i>Personal Details: Basic information, contact, identification and Bank Details.</li>
            <li><i class="fa-solid fa-circle me-2"></i>Identification Details: Address, PAN, and Aadhar proof uploads.</li>
            <li><i class="fa-solid fa-circle me-2"></i>Educational Details: Academic qualifications and related documents.</li>
            <li><i class="fa-solid fa-circle me-2"></i>Experience Details: Previous employment history and supporting letters.</li>
          </ul>

          <h5 class="mt-4 fs-4"><i class="fa-solid fa-hand-point-right text-primary me-2"></i> Before You Proceed</h5>
          <ul class="mb-4 ms-2">
            <li><i class="fa-solid fa-square-check text-success"></i> Keep all your documents ready in digital format.</li>
            <li><i class="fa-solid fa-square-check text-success"></i> Double-check the accuracy of your data.</li>
            <li><i class="fa-solid fa-square-check text-success"></i> Once submitted, the form will be reviewed by HR for verification.</li>
          </ul>

          <p>We appreciate your attention to detail — this helps us process your onboarding quickly and smoothly.</p>
          <p class="fw-semibold">Click “Proceed” when you’re ready to begin your onboarding journey.</p>
      </div>

      <!-- Call to Action -->
      <div class="mt-4 text-center">
        <a href="{{ route('onboard') }}" class="btn btn-primary btn-lg">Proceed</a>
      </div>

  </div>


  </div>
    
@endsection


@push('page-scripts')
 <script>
  $(document).ready(function () {
    getProgress()
  })

 </script>
@endpush
