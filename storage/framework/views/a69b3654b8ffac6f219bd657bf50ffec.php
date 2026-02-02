

<?php $__env->startSection('page-content'); ?>
<div class="content container-fluid">

    <h4 class="mb-3">
        Edit Leave Balances – <?php echo e($employee->name); ?> (<?php echo e($year); ?>)
    </h4>

    <form method="POST" action="<?php echo e(route('leave-balances.update', $employee->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <input type="hidden" name="year" value="<?php echo e($year); ?>">

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Leave Type</th>
                    <th>Opening</th>
                    <th>Accrued</th>
                    <th>Carry Forward</th>
                    <th>Manual Adjustment</th>
                    <th>Used</th>
                    <th>Remaining</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $leaveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $balance = $balances[$type->id] ?? null;
                    ?>

                    <tr>
                        <td>
                            <strong><?php echo e($type->name); ?></strong>
                            <?php if($type->monthly_accrual): ?>
                                <div class="text-muted small">Monthly Accrual</div>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[<?php echo e($type->id); ?>][opening_balance]"
                                value="<?php echo e($balance->opening_balance ?? 0); ?>"
                                class="form-control"
                                <?php echo e($type->monthly_accrual ? 'readonly' : ''); ?>>
                        </td>

                        
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[<?php echo e($type->id); ?>][accrued_leaves]"
                                value="<?php echo e($balance->accrued_leaves ?? 0); ?>"
                                class="form-control"
                                <?php echo e(!$type->monthly_accrual ? 'readonly' : ''); ?>>
                            <?php if($type->monthly_accrual): ?>
                                <small class="text-muted">
                                    Rate: <?php echo e($type->accrual_rate); ?>/month
                                </small>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[<?php echo e($type->id); ?>][carry_forwarded]"
                                value="<?php echo e($balance->carry_forwarded ?? 0); ?>"
                                class="form-control"
                                <?php echo e(!$type->carry_forward ? 'readonly' : ''); ?>>
                            <?php if($type->carry_forward && $type->max_carry_forward): ?>
                                <small class="text-muted">
                                    Max: <?php echo e($type->max_carry_forward); ?>

                                </small>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <input type="number" step="0.01"
                                name="leave_types[<?php echo e($type->id); ?>][manual_adjustment]"
                                value="<?php echo e($balance->manual_adjustment ?? 0); ?>"
                                class="form-control">
                        </td>

                        
                        <td class="text-center">
                            <?php echo e($balance->used_leaves ?? 0); ?>

                        </td>

                        
                        <td class="text-center fw-bold">
                            <?php echo e($balance->remaining_leaves ?? 0); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="mt-3">
            <button class="btn btn-success">
                <i class="fa fa-save"></i> Update Leave Balances
            </button>

            <a href="<?php echo e(route('leave-balances.index')); ?>" class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/leaves/leave-balances/edit.blade.php ENDPATH**/ ?>