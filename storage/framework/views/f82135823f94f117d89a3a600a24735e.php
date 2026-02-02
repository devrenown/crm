

<?php $__env->startSection('page-content'); ?>

<?php
    $features = json_decode($plan->features, true) ?? [];
    $daysRemaining = now()->diffInDays($subscription->end_date, false);
?>

<div class="container py-5">

    <!-- Page Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">Your Subscription</h2>
        <p class="text-muted">Manage your plan, billing, and upgrades.</p>
    </div>

    <div class="row justify-content-center">

        <!-- Current Plan Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <h4 class="fw-bold"><?php echo e(strtoupper($plan->name)); ?></h4>

                <p class="fs-3 fw-bold text-primary mt-3">
                    <?php if($plan->price == 0): ?> Free <?php else: ?> ₨ <?php echo e(number_format($plan->price, 2)); ?> /month <?php endif; ?>
                </p>

                <hr>

                <!-- Remaining Days -->
                <div class="row">
                	<div class="col-lg-4">
                		<p class="mb-2">
	                        <strong><i class="fa-solid fa-hourglass-start text-primary me-1 fs-5"></i> Remaining Days:</strong>
	                        <?php if($daysRemaining > 0): ?>
	                            <span class="text-success"><?php echo e(number_format($daysRemaining)); ?> days left</span>
	                        <?php else: ?>
	                            <span class="text-danger">Expired</span>
	                        <?php endif; ?>
	                    </p>

	                    <!-- Subscription End Date -->
	                    <p class="mb-2">
	                        <strong><i class="fa-regular fa-calendar-days text-primary me-1 fs-5"></i> Renewal / Expiry Date:</strong> 
	                        <?php echo e(date('d-M-Y', strtotime($subscription->end_date))); ?>

	                    </p>

	                    <!-- Status -->
	                    <p class="mb-2">
	                        <strong><i class="fa-solid fa-bell text-primary me-1 fs-5"></i> Status:</strong>
	                        <?php if($subscription->status == '1'): ?>
	                            <span class="badge bg-inverse-success">Active</span>
	                        <?php elseif($subscription->status =='2'): ?>
	                            <span class="badge bg-inverse-danger">Expired</span>
	                        <?php else: ?>
	                            <span class="badge bg-inverse-light text-dark">Canceled</span>
	                        <?php endif; ?>
	                    </p>
                	</div>

                	<div class="col-lg-4">
                		<!-- Limits -->
                        <strong class="fw-semibold">Limits</strong>
                        <ul class="list-unstyled ms-2">
                            <?php $__currentLoopData = $features['limits']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                                    <strong><?php echo e(ucfirst($key)); ?>:</strong> <?php echo e($value); ?>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                	</div>

                	<div class="col-lg-4">
                		<!-- Extra Features -->
                        <strong class="fw-semibold">Extra Features</strong>
                        <ul class="list-unstyled ms-2">
                            <?php $__currentLoopData = $features['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-1">
                                    <i class="bi bi-star-fill text-warning me-2"></i>
                                    <strong><?php echo e(ucwords(str_replace('_',' ', $key))); ?>:</strong>

                                    <?php if(is_bool($value)): ?>
                                        <?php echo e($value ? 'Yes' : 'No'); ?>

                                    <?php else: ?>
                                        <?php echo e($value); ?>

                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                	</div>
                </div>

                <hr>

                <div class="mt-3">

                    <!-- Modules -->
                    <strong class="fw-semibold mt-3">Modules</strong>
                    <ul class="list-unstyled d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $features['modules']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-1 btn bg-inverse-light btn-sm">
                                <?php if($value): ?>
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    <strong><?php echo e(ucwords(str_replace('_',' ', $key))); ?></strong>
                                <?php else: ?>
                                    <i class="bi bi-x-circle-fill text-danger me-1"></i>
                                    <strong><?php echo e(ucwords(str_replace('_',' ', $key))); ?></strong>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                </div>

                <!-- Upgrade Button -->
                <div class="text-end mt-4">
                    <a href="<?php echo e(route('subscription.upgrade')); ?>"
                       class="btn btn-primary btn-sm">
                        Upgrade Plan
                    </a>
                </div>

            </div>
        </div>

    </div>


    <!-- Billing History (Optional) -->
    <div class="row mt-3 justify-content-center">
        <h4 class="fw-bold mb-3"><i class="fa-regular fa-credit-card"></i> Subscription History</h4>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <?php if(count($subscriptions) == 0): ?>
                    <p class="text-muted text-center">No invoices found.</p>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Plan</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(++$key); ?></td>
                                <td><?php echo e($subscription->plan->name); ?></td>
                                <td><?php echo e($subscription->plan->duration); ?> days</td>
                                <td>₨ <?php echo e(number_format($subscription->plan->price, 2)); ?>/30days</td>
                                <td>
                                    <?php if($subscription->status == '1'): ?>
                                        <span class="badge bg-inverse-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-inverse-danger">Expire</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(date('d M Y', strtotime($subscription->start_date))); ?></td>
                                <td><?php echo e(date('d M Y', strtotime($subscription->end_date))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/subscription/index.blade.php ENDPATH**/ ?>