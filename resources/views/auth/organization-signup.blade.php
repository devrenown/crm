@extends('pages.front.layouts.app')

<style>

    nav {
        background: #ffffff !important;
    }
    .nav-link {
        color: black !important;
    }
    .error {
        color: red !important;
    }

</style>

@section('content')

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="mt-5">

            <div class="card shadow-sm">

                <div class="card-body p-5">

                    <div class="text-center">

                        <h3 class="account-title mb-2">{{ __('Create Your Organization') }}</h3>

                        <p class="account-subtitle text-muted mb-4">{{ __('Create your organization workspace and start collaborating instantly.') }}</p>

                    </div>

                    <!-- Account Form -->

                    <form action="{{ route('organization.store') }}" method="POST" id="orgForm" autocomplete="off">

                        @csrf

                        <input type="hidden" name="plan" value="{{ request('plan') }}">

                        <div class="row">

                            <!-- Full Name -->

                            <div class="col-lg-6 mb-3">

                                <label for="organization_name" class="form-label">{{ __('Organization Name') }}</label>

                                <input type="text" class="form-control required" id="organization_name" name="organization_name" tabindex="1" value="{{ old('organization_name') }}" placeholder="Enter Organization Name">

                                <small class="form-text mt-1 text-muted">
                                    Note: Your tenant domain will be generated based on the organization name and cannot be changed later. Ex: <span id="domain-exp" class="text-black">organization</span>.renownsystem.com 
                                </small>

                            </div>



                            <div class="col-lg-6 mb-3">

                              <label for="organization_size" class="form-label">Organization Size</label>

                              <select name="organization_size" id="organization_size" class="form-select form-control required">

                                <option value="" selected disabled>Select Organization Size</option>

                                <option value="1-50" {{ old('organization_size') == '1-50' ? 'selected' : '' }}>1 - 50</option>

                                <option value="50-100" {{ old('organization_size') == '50-100' ? 'selected' : '' }}>50 - 100</option>

                                <option value="100-200" {{ old('organization_size') == '100-200' ? 'selected' : '' }}>100 - 200</option>

                                <option value="200+" {{ old('organization_size') == '200+' ? 'selected' : '' }}>200+</option>

                              </select>

                            </div>

                            <!-- First Name -->

                            <div class="col-lg-6 mb-3">

                                <label for="f_name" class="form-label">{{ __('First Name') }}</label>

                                <input type="text" class="form-control required" id="f_name" name="f_name" tabindex="1"

                                       value="{{ old('f_name') }}" placeholder="Enter First Name">

                            </div>

                            <!-- First Name -->

                            <div class="col-lg-6 mb-3">

                                <label for="l_name" class="form-label">{{ __('Last Name') }}</label>

                                <input type="text" class="form-control required" id="l_name" name="l_name" tabindex="1"

                                       value="{{ old('l_name') }}" placeholder="Enter First Name">

                            </div>

                            <!-- Email -->

                            <div class="col-lg-6 mb-3">

                                <label for="email" class="form-label">{{ __('Email Address') }}</label>

                                <input type="email" class="form-control required" id="email" name="email" tabindex="1"

                                       value="{{ old('email') }}" placeholder="Enter email" autocomplete="off">

                            </div>

                            <!-- Phone -->

                            <div class="col-lg-6 mb-3">

                                <label for="phone" class="form-label">{{ __('Contact Number') }}</label>

                                <input type="number" class="form-control required" id="phone" name="phone" tabindex="1"

                                       value="{{ old('phone') }}" placeholder="Enter contact" maxlength="10">

                            </div>

                            <!-- Password -->

                            <div class="col-lg-6">

                                <label for="password" class="form-label">{{ __('Password') }}</label>

                                <div class="input-group">

                                    <input type="password" class="form-control" id="password" name="password"

                                           placeholder="******" tabindex="2" required>

                                    <span class="input-group-text" id="toggle-password" style="cursor:pointer;">

                                        <i class="fa-solid fa-eye-slash"></i>

                                    </span>

                                </div>

                            </div>

                            <!-- Confirm Password -->

                            <div class="col-lg-6 mb-3">

                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>

                                <input type="password" class="form-control required" id="password_confirmation"

                                       name="password_confirmation" tabindex="1" placeholder="******" autocomplete="off">

                            </div>

                            <!-- Submit Button -->

                            <div class="mt-3 d-flex justify-content-between align-items-center">

                                <p class="mb-0">

                                    I have already an account ? <a href="{{ route('login') }}">Login</a>

                                </p>

                                {{-- <button type="submit" class="btn btn-primary">{{ __('Register') }}</button> --}}

                                <input type="submit" class="btn btn-primary pay-button" title="Register" 
                                data-plan-id="{{ $plan->id }}"
                                data-plan-name="{{ $plan->name }}"
                                data-plan-price="{{ $plan->price }}">

                            </div>

                        </div>

                    </form>

                    <!-- /Account Form -->

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Thank You Modal -->
<div class="modal fade" id="thankYouModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg text-center p-4">

            <div class="modal-body">

                <!-- Success Icon -->
                <div class="mb-3">
                    <i class="fa-solid fa-circle-check text-success" style="font-size:72px;"></i>
                </div>

                <h4 class="fw-bold mb-2">Thank You!</h4>

                <p class="text-muted mb-3">
                   Your registration is complete and your workspace is being prepared.
                </p>

                <p class="text-muted mb-3">
                    We’ve sent your tenant details to your registered email address.
                    Please check your inbox (and spam folder if needed).
                </p>

                <!-- Loader Icon -->
                <div class="my-3">
                    <i class="fa-solid fa-spinner fa-spin text-primary" style="font-size:28px;"></i>
                </div>

                <p class="text-muted mb-0">
                    Redirecting you securely…
                </p>

            </div>

        </div>
    </div>
</div>

<!-- Payment Failed Modal -->
<div class="modal fade" id="paymentErrorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg text-center p-4">

            <div class="modal-body">

                <!-- Error Icon -->
                <div class="mb-3">
                    <i class="fa-solid fa-circle-xmark text-danger" style="font-size:72px;"></i>
                </div>

                <h4 class="fw-bold mb-2">Payment Failed</h4>

                <p class="text-muted mb-3">
                    We couldn’t process your payment.<br>
                    Please try again or use a different payment method.
                </p>

                <!-- Warning Icon -->
                <div class="my-3">
                    <i class="fa-solid fa-triangle-exclamation text-warning" style="font-size:26px;"></i>
                </div>

                <p class="text-muted mb-0">
                    No amount has been deducted from your account.
                </p>

            </div>

        </div>
    </div>
</div>

@endsection



@push('scripts')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

@if(session('tenant_status') == 'success')
<script>
    $(document).ready(function () {
        let successModal = new bootstrap.Modal(
            document.getElementById('thankYouModal'),
            { backdrop: 'static', keyboard: false }
        );
        successModal.show();

        setTimeout(() => successModal.hide(), 4000);
    });
</script>
@endif

@if(session('tenant_status') == 'error')
<script>
    $(document).ready(function () {
        let errorModal = new bootstrap.Modal(
            document.getElementById('paymentErrorModal'),
            { backdrop: 'static', keyboard: false }
        );
        errorModal.show();

        setTimeout(() => errorModal.hide(), 4000);
    });
</script>
@endif

<script>

    $(document).ready(function () {

        const $orgInput = $('#organization_name');
        const $domainExp = $('#domain-exp');

        $("#orgForm").validate({

            errorClass: "text-danger",
            errorElement: "span",

            rules: {
                organization_name: {
                    required: true,
                    minlength: 3
                },
                organization_size: {
                    required: true
                },
                f_name: {
                    required: true,
                    minlength: 2
                },
                l_name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },

            messages: {

                organization_name: {
                    required: "Please enter organization name",
                    minlength: "Name must be at least 3 characters"
                },

                organization_size: {
                    required: "Please select organization size"
                },

                f_name: {
                    required: "First name is required",
                    minlength: "First name must be at least 2 characters"
                },

                l_name: {
                    required: "Last name is required",
                    minlength: "Last name must be at least 2 characters"
                },

                email: {
                    required: "Email is required",
                    email: "Enter a valid email address"
                },

                phone: {
                    required: "Contact number is required",
                    digits: "Contact must contain only numbers",
                    minlength: "Contact must be 10 digits",
                    maxlength: "Contact must be 10 digits"
                },

                password: {
                    required: "Password is required",
                    minlength: "Password must be at least 6 characters"
                },

                password_confirmation: {
                    required: "Confirm your password",
                    equalTo: "Passwords do not match"
                }
            },

        });

        $("#orgForm").on("submit", function(e) {
            e.preventDefault();
        });


        $(document).on("click", ".pay-button", function (e) {

            e.preventDefault();

            if (!$("#orgForm").valid()) {
                return;
            }

            let plan = "{{ decrypt(request('plan')) }}";

            if (plan == 'FREE TRIAL') {
                $("#orgForm")[0].submit();
                return;
            }

            const thankYouModalEl   = $('#thankYouModal');
            let email               = $('#email').val();
            let organization        = $('#organization_name').val();
            let planId              = $(this).data("plan-id");
            let planName            = $(this).data("plan-name");
            let amount              = $(this).data("plan-price") * 3 * 100;
            amount = amount == 0 ? 1 * 100 : amount;

            $.post("{{ route('razorpay.order.create') }}", {
                _token: "{{ csrf_token() }}",
                amount: amount
            }, function (order) {

                var options = {
                    "key": "{{ env('RAZORPAY_API_KEY') }}",
                    "amount": order.amount,
                    "currency": order.currency,
                    "name": "Renown System",
                    "description": "Upgrade to " + planName + " Plan",
                    "order_id": order.id,
                    "handler": function (response) {

                        $.post("{{ route('razorpay.payment.store') }}", {
                            _token: "{{ csrf_token() }}",
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                            razorpay_payment_amount: amount,
                            organization: organization,
                            subscription_id: null,
                            email: email,
                            plan_id: planId,
                        }, function (res) {

                            if (res.success) {
                                // Show thank you modal
                                let thankYouModal = new bootstrap.Modal(thankYouModalEl, {
                                    backdrop: 'static',  
                                    keyboard: false      
                                });

                                thankYouModal.show();

                                setTimeout(function () {
                                    thankYouModal.hide();

                                    // Submit form after modal hides
                                    setTimeout(function () {
                                        $("#orgForm")[0].submit();
                                    }, 300);

                                }, 5000);
                            } else {
                                let errorModal = new bootstrap.Modal(
                                    document.getElementById('paymentErrorModal'),
                                    { backdrop: 'static', keyboard: false }
                                );

                                errorModal.show();

                                setTimeout(function () {
                                    errorModal.hide();
                                }, 4000);
                            }

                            // location.reload();
                        });
                    },
                    "prefill": {
                        "name": "{{ auth()->user()->fullname ?? '' }}",
                        "email": "{{ auth()->user()->email ?? '' }}"
                    }
                };

                var rzp1 = new Razorpay(options);
                rzp1.open();
            });
        });

        function generateTenantName(value) {
            if (!$.trim(value)) {
                return 'organization';
            }

            // Take first word only
            let firstWord = $.trim(value).split(/\s+/)[0];

            // Clean special characters and lowercase
            return firstWord
                .toLowerCase()
                .replace(/[^a-z0-9]/g, '');
        }

        $orgInput.on('keyup input', function () {
            $domainExp.text(generateTenantName($(this).val()));
        });

        // Handle old value (validation error / edit case)
        if ($orgInput.val()) {
            $domainExp.text(generateTenantName($orgInput.val()));
        }

    });


    // ---------------------------
    // Toggle Password Visibility
    // ---------------------------
    document.getElementById('toggle-password').addEventListener('click', function () {

        let passwordInput = document.getElementById('password');
        let icon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }

    });
</script>

@endpush

