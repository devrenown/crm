<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h3 class="account-title mb-2"><?php echo e(__('Forgot Password?')); ?></h3>
                        <p class="account-subtitle text-muted mb-4"><?php echo e(__('Enter your email to get a password reset link')); ?></p>
                    </div>

                    <!-- Forgot Password Form -->
                    <form action="<?php echo e(route('password.request')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo e(__('Email Address')); ?></label>
                            <input type="email" class="form-control" id="email" name="email" tabindex="1"
                                   value="<?php echo e(old('email')); ?>" placeholder="example@smarthr.com" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary"><?php echo e(__('Reset Password')); ?></button>
                        </div>

                        <!-- Back to Login -->
                        <?php if(Route::has('login')): ?>
                            <div class="text-center">
                                <p class="mb-0">
                                    <?php echo e(__('Remember your password?')); ?> 
                                    <a href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </form>
                    <!-- /Forgot Password Form -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.front.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>