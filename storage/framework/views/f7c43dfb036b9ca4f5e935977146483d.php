<?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-department', 'delete-department'])): ?>
    <?php if (isset($component)) { $__componentOriginal3cb096f2e62c7df2672a776d39e07de4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3cb096f2e62c7df2672a776d39e07de4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-action','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-department')): ?>
        <a class="dropdown-item" href="javascript:void(0)" data-url="<?php echo e(route('departments.edit', $id)); ?>"
            data-ajax-modal="true" data-title="<?php echo e(__('Edit Department')); ?>" data-bs-toggle="tooltip"
            data-bs-title="<?php echo e(__('Edit Department')); ?>" data-size="md"><i class="fa-solid fa-pencil m-r-5"></i>
            <?php echo e(__('Edit')); ?>

        </a>
        <?php endif; ?>
        <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-department')): ?>
        <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('departments.destroy', $id)); ?>"
            data-title="<?php echo e(__('Delete Department')); ?>" data-question="<?php echo e(__('Are you sure you want to delete?')); ?>"
            href="javascript:void(0)">
            <i class="fa-regular fa-trash-can m-r-5"></i>
            <?php echo e(__('Delete')); ?>

        </a>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3cb096f2e62c7df2672a776d39e07de4)): ?>
<?php $attributes = $__attributesOriginal3cb096f2e62c7df2672a776d39e07de4; ?>
<?php unset($__attributesOriginal3cb096f2e62c7df2672a776d39e07de4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3cb096f2e62c7df2672a776d39e07de4)): ?>
<?php $component = $__componentOriginal3cb096f2e62c7df2672a776d39e07de4; ?>
<?php unset($__componentOriginal3cb096f2e62c7df2672a776d39e07de4); ?>
<?php endif; ?>
<?php endif; ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/departments/actions.blade.php ENDPATH**/ ?>