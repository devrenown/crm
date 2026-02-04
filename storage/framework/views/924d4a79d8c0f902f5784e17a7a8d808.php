<?php $__env->startSection('page-header-section'); ?>
    <!-- Page Header -->
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
         <?php $__env->slot('title', null, []); ?> <?php echo e(__('Salary Settings')); ?> <?php $__env->endSlot(); ?>
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
    <!-- /Page Header -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-section'); ?>
    <form action="<?php echo e(route('settings.salary.update')); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <!-- DA and HRA Settings -->
        <div class="settings-widget">
            <div class="h3 card-title with-switch">
                <?php echo e(__('DA and HRA ')); ?>											
                <div class="onoffswitch">
                    <input type="checkbox" name="enable_da_hra" class="onoffswitch-checkbox" id="switch_hra" <?php echo e(!empty($settings->enable_da_hra) ? 'checked':''); ?>>
                    <label class="onoffswitch-label" for="switch_hra">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('DA')); ?> (%)</label>
                        <input type="text" class="form-control" name="da_percent" value="<?php echo e($settings->da_percent); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('HRA')); ?> (%)</label>
                        <input class="form-control" type="text" name="hra_percent" value="<?php echo e($settings->hra_percent); ?>">
                    </div>
                </div>
            </div>
        </div>
        <!-- /DA and HRA Settings -->
        
        <!-- Provident Fund Settings -->
        <div class="settings-widget">
            <div class="h3 card-title with-switch">
                <?php echo e(__('Provident Fund Settings')); ?> 											
                <div class="onoffswitch">
                    <input type="checkbox" name="enable_pf" class="onoffswitch-checkbox" id="switch_pf" <?php echo e(!empty($settings->enable_provident_fund) ? 'checked': ''); ?>>
                    <label class="onoffswitch-label" for="switch_pf">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Employee Share')); ?> (%)</label>
                        <input class="form-control" type="text" name="emp_pf" value="<?php echo e($settings->emp_pf_percentage); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Organization Share')); ?> (%)</label>
                        <input class="form-control" type="text" name="company_pf" value="<?php echo e($settings->company_pf_percentage); ?>">
                    </div>
                </div>
            </div>
        </div>
        <!-- /Provident Fund Settings -->
        
        <!-- ESI Settings -->
        <div class="settings-widget">
            <div class="h3 card-title with-switch">
                <?php echo e(__('ESI Settings')); ?> 											
                <div class="onoffswitch">
                    <input type="checkbox" name="enable_esi" class="onoffswitch-checkbox" id="switch_esi" <?php echo e(!empty($settings->enable_esi_fund) ? 'checked': ''); ?>>
                    <label class="onoffswitch-label" for="switch_esi">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Employee Share')); ?> (%)</label>
                        <input class="form-control" type="text" name="emp_esi" value="<?php echo e($settings->emp_esi_percentage); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Organization Share')); ?> (%)</label>
                        <input class="form-control" type="text" name="company_esi" value="<?php echo e($settings->company_esi_percentage); ?>">
                    </div>
                </div>
            </div>
        </div>
        <!-- /ESI Settings -->
        <div class="submit-section">
            <button class="btn btn-primary submit-btn"><?php echo e(__('Save')); ?></button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-scripts'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('pages.settings.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/settings/salary.blade.php ENDPATH**/ ?>