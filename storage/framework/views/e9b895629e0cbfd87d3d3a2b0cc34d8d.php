<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

   <?php echo $__env->make('partials.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <style>

        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .login-card {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 12px;
        }

        .tenant-logo {
            height: 50px;
            width: auto;
        }

        .input-email-icon,
        .input-password-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            color: #6c757d;
            pointer-events: none;
            z-index: 99;
        }

        .input-email-icon {
            transform: translate(50%, 50%);
            left: 2%;
        }

        .input-password-icon {
            transform: translate(-4%, -50%);
            left: 4%;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 20px;
            }

            .tenant-logo {
                height: 40px;
            }

            h4 {
                font-size: 1.25rem;
            }
        }

    </style>

</head>

<body>

    <div class="container">
        <div class="card shadow login-card mx-auto">

            <div class="card-body text-center">

                <!-- Tenant Logo -->

                <?php
                    $theme = app(\App\Settings\ThemeSettings::class);
                ?>

                <?php if($theme->logo_dark ?? false): ?>
                    <img src="<?php echo e(asset('storage/settings/theme/'.$theme->logo_dark)); ?>" class="tenant-logo mb-3" alt="Tenant Logo">
                <?php else: ?>
                    <img src="<?php echo e(asset('images/company-placeholder.png')); ?>" class="tenant-logo mb-3" alt="Logo">
                <?php endif; ?>

                <!-- Tenant Name -->

                <h4 class="mb-4 fs-4 text-capitalize"><?php echo e($tenant->name ?? 'Welcome'); ?></h4>

                <!-- Login Form -->

                <form action="<?php echo e(route('login.submit')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3 text-start position-relative">

                        <label class="form-label">Email</label>
                        <i class="fa-solid fa-envelope input-email-icon"></i>

                        <input type="email" name="email" class="form-control ps-5" placeholder="example@gmail.com" value="<?php echo e(old('email')); ?>">

                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                          <small class="text-danger"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    <!-- Password -->

                    <div class="mb-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label"><?php echo e(__('Password')); ?></label>
                            
                        </div>

                        <div class="input-group position-relative">

                            <i class="fa-solid fa-lock input-password-icon"></i>
                            <input type="password" class="form-control ps-5" id="password" name="password"
                            placeholder="******" tabindex="2">

                            <span class="input-group-text" id="toggle-password" style="cursor:pointer;">
                                <i class="fa-solid fa-eye-slash"></i>
                            </span>

                        </div>

                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                            <div class="text-start">
                                <small class="text-danger"><?php echo e($message); ?></small>
                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    <button class="btn bg-<?php echo e($theme->color_scheme); ?> w-100 mt-2">Login</button>
                </form>
            </div>
        </div>
    </div>


    <script>

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

</body>

</html><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/auth/organization-login.blade.php ENDPATH**/ ?>