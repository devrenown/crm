

<?php $__env->startSection('page-content'); ?>
<?php
    $isL1ApprovalStage = $leave->approval_stage === 'L1';
    $isL2ApprovalStage = $leave->approval_stage === 'L2';

    $canApprove = auth()->user()->can('approve', $leave);
?>

<div class="content container-fluid">

<?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Approve Leave <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>

<div class="card">
    <div class="card-body">

        
        <p><strong>Employee:</strong> <?php echo e($leave->user->name); ?></p>
        
        <p>
            <strong>Dates:</strong>
            <?php echo e($leave->start_date->format('d M Y')); ?>

            →
            <?php echo e($leave->end_date?->format('d M Y')); ?>

        </p>
        <p><strong>Days:</strong> <?php echo e($leave->days); ?></p>
        <p><strong>Leave Type:</strong> <?php echo e($leave->leaveType?->name ?? 'N/A'); ?></p>
        <p><strong>Status:</strong> <?php echo e($leave->status); ?></p>
        <p><strong>Reason:</strong> <?php echo e($leave->reason); ?></p>

        
        <?php if($canApprove): ?>
            <form method="POST" action="<?php echo e(route('leaves.approve', $leave)); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="Approved">Approve</option>
                        <option value="Rejected">Reject</option>
                    </select>
                </div>

                
                <?php if($isL1ApprovalStage): ?>
                    <?php if($leave->approved_level_1_id): ?>
                        <div class="alert alert-info">
                            <strong>L1 Approved By:</strong> <?php echo e($leave->approvedLevel1?->name ?? 'N/A'); ?><br>
                            <strong>Remark:</strong> <?php echo e($leave->approved_level_1_remark ?? '-'); ?><br>
                            <strong>On:</strong> <?php echo e($leave->approved_level_1_on); ?>

                        </div>
                    <?php else: ?>
                        <textarea name="approved_level_1_remark" class="form-control mb-3" placeholder="Remark"></textarea>
                    <?php endif; ?>
                <?php endif; ?>

                
                <?php if($isL2ApprovalStage): ?>
                    <?php if($leave->approved_level_2_id): ?>
                        <div class="alert alert-info">
                            <strong>L2 Approved By:</strong> <?php echo e($leave->approvedLevel2?->name ?? 'N/A'); ?><br>
                            <strong>Remark:</strong> <?php echo e($leave->approved_level_2_remark ?? '-'); ?><br>
                            <strong>On:</strong> <?php echo e($leave->approved_level_2_on); ?>

                        </div>
                    <?php else: ?>
                        <textarea name="approved_level_2_remark" class="form-control mb-3" placeholder="Remark"></textarea>
                    <?php endif; ?>
                <?php endif; ?>

                <button class="btn btn-success">Submit</button>
                <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-secondary">Cancel</a>
            </form>
        <?php else: ?>
            <div class="alert alert-secondary">
                You do not have permission to approve this leave at this stage.
            </div>
        <?php endif; ?>

        
        <?php if(session('allow_manual_adjust')): ?>
            <hr>
            <div class="alert alert-warning">
                <?php echo e(session('message')); ?>


                <form method="POST" action="<?php echo e(route('leaves.approve', $leave)); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="status" value="Approved">

                    <label class="mt-2">Manual Adjustment</label>
                    <input type="number"
                           name="manual_adjustment"
                           min="<?php echo e(session('required_adjustment')); ?>"
                           class="form-control"
                           required>

                    <button class="btn btn-success mt-2">Confirm Approval</button>
                </form>
            </div>
        <?php endif; ?>

    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/leaves/approve.blade.php ENDPATH**/ ?>