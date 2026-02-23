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

<?php $__env->startSection('content'); ?>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="mt-5">

            <?php if(session('success')): ?>

                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    <strong>Success!</strong> <?php echo e(session('success')); ?>


                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                </div>

            <?php endif; ?>

            <div class="card shadow-sm">

                <div class="card-body p-5">

                    <div class="text-center">

                        <h3 class="account-title mb-2"><?php echo e(__('Organization Register')); ?></h3>

                        <p class="account-subtitle text-muted mb-4"><?php echo e(__('For access to your dashboard')); ?></p>

                    </div>

                    <!-- Account Form -->

                    <form action="<?php echo e(route('organization.store')); ?>" method="POST" id="orgForm" autocomplete="off">

                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="plan" value="<?php echo e(request('plan')); ?>">

                        <div class="row">

                            <!-- Full Name -->

                            <div class="col-lg-6 mb-3">

                                <label for="organization_name" class="form-label"><?php echo e(__('Organization Name')); ?></label>

                                <input type="text" class="form-control required" id="organization_name" name="organization_name" tabindex="1"

                                       value="<?php echo e(old('organization_name')); ?>" placeholder="Enter Organization Name">

                            </div>



                            <div class="col-lg-6 mb-3">

                              <label for="organization_size" class="form-label">Organization Size</label>

                              <select name="organization_size" id="organization_size" class="form-select form-control required">

                                <option value="" selected disabled>Select Organization Size</option>

                                <option value="1-50" <?php echo e(old('organization_size') == '1-50' ? 'selected' : ''); ?>>1 - 50</option>

                                <option value="50-100" <?php echo e(old('organization_size') == '50-100' ? 'selected' : ''); ?>>50 - 100</option>

                                <option value="100-200" <?php echo e(old('organization_size') == '100-200' ? 'selected' : ''); ?>>100 - 200</option>

                                <option value="200+" <?php echo e(old('organization_size') == '200+' ? 'selected' : ''); ?>>200+</option>

                              </select>

                            </div>

                            <!-- First Name -->

                            <div class="col-lg-6 mb-3">

                                <label for="f_name" class="form-label"><?php echo e(__('First Name')); ?></label>

                                <input type="text" class="form-control required" id="f_name" name="f_name" tabindex="1"

                                       value="<?php echo e(old('f_name')); ?>" placeholder="Enter First Name">

                            </div>

                            <!-- First Name -->

                            <div class="col-lg-6 mb-3">

                                <label for="l_name" class="form-label"><?php echo e(__('Last Name')); ?></label>

                                <input type="text" class="form-control required" id="l_name" name="l_name" tabindex="1"

                                       value="<?php echo e(old('l_name')); ?>" placeholder="Enter First Name">

                            </div>

                            <!-- Email -->

                            <div class="col-lg-6 mb-3">

                                <label for="email" class="form-label"><?php echo e(__('Email Address')); ?></label>

                                <input type="email" class="form-control required" id="email" name="email" tabindex="1"

                                       value="<?php echo e(old('email')); ?>" placeholder="Enter email" autocomplete="off">

                            </div>

                            <!-- Phone -->

                            <div class="col-lg-6 mb-3">

                                <label for="phone" class="form-label"><?php echo e(__('Contact Number')); ?></label>

                                <input type="number" class="form-control required" id="phone" name="phone" tabindex="1"

                                       value="<?php echo e(old('phone')); ?>" placeholder="Enter contact" maxlength="10">

                            </div>

                            <!-- Password -->

                            <div class="col-lg-6">

                                <label for="password" class="form-label"><?php echo e(__('Password')); ?></label>

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

                                <label for="password_confirmation" class="form-label"><?php echo e(__('Confirm Password')); ?></label>

                                <input type="password" class="form-control required" id="password_confirmation"

                                       name="password_confirmation" tabindex="1" placeholder="******" autocomplete="off">

                            </div>

                            <!-- Submit Button -->

                            <div class="mt-3 d-flex justify-content-between align-items-center">

                                <p class="mb-0">

                                    I have already an account ? <a href="<?php echo e(route('login')); ?>">Login</a>

                                </p>

                                

                                <input type="submit" class="btn btn-primary pay-button" title="Register" 
                                data-plan-id="<?php echo e($plan->id); ?>"
                                data-plan-name="<?php echo e($plan->name); ?>"
                                data-plan-price="<?php echo e($plan->price); ?>">

                            </div>

                        </div>

                    </form>

                    <!-- /Account Form -->

                </div>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>



<?php $__env->startPush('scripts'); ?>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

    $(document).ready(function () {

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

            let plan = "<?php echo e(decrypt(request('plan'))); ?>";

            if (plan == 'FREE TRIAL') {
                $("#orgForm")[0].submit();
                return;
            }

            let email           = $('#email').val();
            let organization    = $('#organization_name').val();
            let planId          = $(this).data("plan-id");
            let planName        = $(this).data("plan-name");
            let amount          = $(this).data("plan-price") * 3 * 100;
            amount = amount == 0 ? 1 * 100 : amount;

            $.post("<?php echo e(route('razorpay.order.create')); ?>", {
                _token: "<?php echo e(csrf_token()); ?>",
                amount: amount
            }, function (order) {

                var options = {
                    "key": "<?php echo e(env('RAZORPAY_API_KEY_TEST')); ?>",
                    "amount": order.amount,
                    "currency": order.currency,
                    "name": "Renown CRM",
                    "description": "Upgrade to " + planName + " Plan",
                    "order_id": order.id,
                    "handler": function (response) {

                        $.post("<?php echo e(route('razorpay.payment.store')); ?>", {
                            _token: "<?php echo e(csrf_token()); ?>",
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
                                $("#orgForm")[0].submit();
                            } else {
                                alert('Something went wrong!');
                            }

                            // location.reload();
                        });
                    },
                    "prefill": {
                        "name": "<?php echo e(auth()->user()->fullname ?? ''); ?>",
                        "email": "<?php echo e(auth()->user()->email ?? ''); ?>"
                    }
                };

                var rzp1 = new Razorpay(options);
                rzp1.open();
            });
        });

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

<?php $__env->stopPush(); ?>


<?php echo $__env->make('pages.front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/auth/organization-signup.blade.php ENDPATH**/ ?>