

<?php $__env->startSection('page-content'); ?>

    <!-- Pricing Section -->
    <section class="p-5 bg-light">

        <div class="text-end">
            <button onclick="window.history.back()" class="btn btn-dark btn-sm">Back</button>
        </div>

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Pricing Plans</h2>
            <p class="text-muted">Choose the plan that best fits your business needs.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php 
                $features = json_decode($plan->features, true) ?? [];
            ?>

            <?php if($plan->name != 'FREE TRIAL'): ?>
            <div class="col-md-4 col-lg-4">
                <div class="pricing-card card shadow-sm pb-3 text-center position-relative <?php echo e($current_plan->id == $plan->id ? 'border border-success' : ''); ?>">

                    <!-- Highlight Popular Plan -->
                    <?php if($plan->name == 'PRO' ?? false): ?>
                        <span class="badge bg-warning text-dark position-absolute top-0 start-50 translate-middle rounded-pill px-3 py-2 shadow-sm">
                            ⭐ Popular
                        </span>
                    <?php endif; ?>

                    <div class="card-body p-4">
                        <h5 class="fw-bold"><?php echo e($plan->name); ?></h5>

                        <p class="fs-5 fw-bold mt-2 mb-0 text-primary">
                            <?php echo e($currency->symbol); ?> <?php echo e(number_format($plan->display_price, 2)); ?>/month
                        </p>

                        <p class="text-muted small mb-4">Billed quarterly</p>

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

                        <?php
                          $isCurrentPlan = $current_plan->id == $plan->id;
                          $isExpired     = $subscription && \Carbon\Carbon::parse($subscription->end_date)->isPast();
                        ?>

                        <?php if($isCurrentPlan && ! $isExpired): ?>
                            <a href="javascript:void(0)" class="btn btn-success btn-sm mt-3">
                                Current Plan
                            </a>

                        <?php elseif($isCurrentPlan && $isExpired): ?>
                            <button
                                class="btn btn-info btn-sm mt-3 pay-button"
                                data-plan-id="<?php echo e($plan->id); ?>"
                                data-plan-name="<?php echo e($plan->name); ?>"
                                data-subscription-id="<?php echo e($subscription->id); ?>"
                                data-plan-price="<?php echo e($plan->display_price); ?>">
                                Renew
                            </button>

                        <?php else: ?>
                            <button
                                class="btn btn-primary btn-sm mt-3 pay-button"
                                data-plan-id="<?php echo e($plan->id); ?>"
                                data-plan-name="<?php echo e($plan->name); ?>"
                                data-subscription-id="<?php echo e($subscription->id); ?>"
                                data-plan-price="<?php echo e($plan->display_price); ?>">
                                Upgrade
                            </button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <?php $__env->startPush('page-styles'); ?>

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

    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('page-scripts'); ?>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

        <script>
            $(document).on("click", ".pay-button", function () {

                let planId          = $(this).data("plan-id");
                let planName        = $(this).data("plan-name");
                let amount          = $(this).data("plan-price") * 3 * 100;
                let subscription    = $(this).data('subscription-id');
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
                                subscription_id: subscription,
                                plan_id: planId,
                            }, function (res) {
                                console.log(res);

                                if (res.success) {
                                    alert(res.message ?? 'Payment Successfull');
                                    location.reload();
                                }else {
                                    alert(res.message ?? 'Something went wrong!');
                                }
                            });
                        },
                        "prefill": {
                            "name": "<?php echo e(auth()->user()->fullname); ?>",
                            "email": "<?php echo e(auth()->user()->email); ?>"
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();

                });
            });
        </script>

    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/subscription/upgrade.blade.php ENDPATH**/ ?>