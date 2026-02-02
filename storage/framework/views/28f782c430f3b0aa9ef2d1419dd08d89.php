<?php $__env->startSection('sidebar'); ?>
    <?php if (isset($component)) { $__componentOriginalb6ff329c221cfdecbac1e937a20c09ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb6ff329c221cfdecbac1e937a20c09ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.custom-sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('custom-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <?php echo renderAppSettingsMenu(); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb6ff329c221cfdecbac1e937a20c09ea)): ?>
<?php $attributes = $__attributesOriginalb6ff329c221cfdecbac1e937a20c09ea; ?>
<?php unset($__attributesOriginalb6ff329c221cfdecbac1e937a20c09ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb6ff329c221cfdecbac1e937a20c09ea)): ?>
<?php $component = $__componentOriginalb6ff329c221cfdecbac1e937a20c09ea; ?>
<?php unset($__componentOriginalb6ff329c221cfdecbac1e937a20c09ea); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-content'); ?>
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php echo $__env->yieldContent('page-header-section'); ?>
                <?php echo $__env->yieldContent('page-section'); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/settings/index.blade.php ENDPATH**/ ?>