

<?php $__env->startSection('page-content'); ?>
<div class="content container-fluid">

    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="mb-1">Leave Balances</h3>
            <p class="text-muted mb-0">
                Manage yearly leave allocations for employees
            </p>
        </div>

        <a href="<?php echo e(route('leave-balances.create')); ?>" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add Leave Balance
        </a>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Year</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $leaveBalances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">
                                <?php echo e($row->user?->name); ?>

                            </div>
                            <small class="text-muted">
                                <?php echo e($row->leave_types_count); ?> leave types
                            </small>
                        </td>

                        <td>
                            <span class="badge bg-info">
                                <?php echo e($row->year); ?>

                            </span>
                        </td>

                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('leave-balances.edit', $row->user_id)); ?>?year=<?php echo e($row->year); ?>"
                            class="btn btn-sm btn-outline-warning me-1"
                            title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>

                            <form action="<?php echo e(route('leave-balances.destroy', $row->user_id)); ?>?year=<?php echo e($row->year); ?>"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Delete all leave balances for this employee and year?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                No leave balances found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

        
        <?php if($leaveBalances->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($leaveBalances->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/leaves/leave-balances/index.blade.php ENDPATH**/ ?>