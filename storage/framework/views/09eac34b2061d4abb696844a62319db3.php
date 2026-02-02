

<?php $__env->startSection('page-content'); ?>

<style>
    #tasks-table th:nth-child(3),
    #tasks-table td:nth-child(3) {
        width: 200px !important;  /* Description column */
    }
</style>
    <div class="content container-fluid">

        <!-- Page Header -->
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['class' => 'col']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col']); ?>
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Employee Work History')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('work-report.index')); ?>"><?php echo e(__('Work Reports')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Employee Work History')); ?>

                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <a href="javascript:history.back()" class="btn btn-dark btn-sm rounded me-3">
                        <i class="fa-solid fa-circle-left"></i> <?php echo e(__('Go Back')); ?>

                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-tasks')): ?>
                        <a href="javascript:void(0)" data-url="<?php echo e(route('clockout-modal')); ?>" class="btn add-btn" data-ajax-modal="true"
                            data-size="lg" data-title="<?php echo e(__('Add Ticket')); ?>">
                            <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Work Reports')); ?>

                        </a>
                    <?php endif; ?>
                </div>
             <?php $__env->endSlot(); ?>
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
        

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <?php echo $dataTable->table(['class' => 'table table-striped custom-table w-100']); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('page-scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')([
    "resources/js/datatables.js",
    "resources/assets/css/ckeditor.css",
    "resources/js/ckeditor.js"
]); ?>
<?php echo $dataTable->scripts(attributes: ['type' => 'module']); ?>


<script>
    $('#tasks-table').DataTable({
        autoWidth: false,
        columnDefs: [
            { width: "200px", targets: 2 }, 
        ]
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/work-report/employee-task-list.blade.php ENDPATH**/ ?>