<!-- Pricing Section -->
<section class="p-5 bg-light" id="pricing">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">Pricing Plans</h2>
        <p class="text-muted">Choose the plan that best fits your business needs.</p>

        <form method="POST" class="row justify-content-center" action="/currency-switch">
            <?php echo csrf_field(); ?>

            <?php
              $currencies = config('country_currency');
            ?>

            <div class="col-1">
                <select name="currency" class="form-control form-select" onchange="this.form.submit()">
                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cur); ?>" <?php echo e($currency->code == $cur ? 'selected' : ''); ?>>
                            <?php echo e($cur); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </form>
    </div>

    <div class="row g-4 justify-content-center">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php 
            $features = json_decode($plan->features, true) ?? [];
        ?>

        <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
            <div class="pricing-card card border-0 shadow-sm pb-2 text-center position-relative">

                <!-- Highlight Popular Plan -->
                <?php if($plan->name == 'PRO' ?? false): ?>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-50 translate-middle rounded-pill px-3 py-2 shadow-sm">
                        ⭐ Popular
                    </span>
                <?php endif; ?>

                <div class="card-body p-4">
                    <h5 class="fw-bold"><?php echo e($plan->name); ?></h5>

                    <p class="fs-4 fw-bold mt-2 mb-0 text-primary">
                        <?php echo e($currency->symbol); ?> <?php echo e(number_format($plan->display_price, 2)); ?>/month
                    </p>

                    <p class="text-muted small mb-4">Billed quarterly</p>

                    <?php if($plan->name == 'FREE TRIAL'): ?>
                     <strong>No Credit Card Required</strong>
                    <?php endif; ?>

                    <ul class="list-unstyled text-start mx-auto" style="max-width: 260px;">

                        
                        <?php if(isset($features['limits'])): ?>
                            <li class="fw-bold mt-2 mb-1 text-primary">Limits</li>
                            <?php $__currentLoopData = $features['limits']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo e($value); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        
                        <?php if(isset($features['modules'])): ?>
                            <li class="fw-bold mt-3 mb-1 text-primary">Modules</li>
                            <?php $__currentLoopData = $features['modules']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-<?php echo e($value ? 'check-circle-fill text-success' : 'x-circle-fill text-danger'); ?> me-2"></i>
                                    <span><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo e($value ? 'Enabled' : 'Disabled'); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        
                        <?php if(isset($features['features'])): ?>
                            <li class="fw-bold mt-3 mb-1 text-primary">Features</li>
                            <?php $__currentLoopData = $features['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-<?php echo e($value ? 'check-circle-fill text-success' : 'x-circle-fill text-danger'); ?> me-2"></i>
                                    
                                    <?php if(is_bool($value)): ?>
                                        <span><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo e($value ? 'Yes' : 'No'); ?></span>
                                    <?php else: ?>
                                        <span><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo e($value); ?></span>
                                    <?php endif; ?>
                                    
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </ul>


                    <a href="<?php echo e(route('organization.signup', encrypt($plan->name))); ?>" class="btn btn-primary mt-3 px-4 py-2 fw-semibold">Choose Plan</a>
                </div>

            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<?php $__env->startPush('styles'); ?>

<style>
    /* Modern Card Hover Effect */
    .pricing-card {
        transition: transform .3s ease, box-shadow .3s ease;
        border-radius: 15px;
    }

    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
    }
</style>

<?php $__env->stopPush(); ?><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/pages/front/blocks/pricing.blade.php ENDPATH**/ ?>