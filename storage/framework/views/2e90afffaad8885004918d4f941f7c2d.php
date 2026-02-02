

<?php $__env->startSection('page-content'); ?>
<div class="content container-fluid">

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
         <?php $__env->slot('title', null, []); ?> Shifts <?php $__env->endSlot(); ?>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Shifts</li>
        </ul>
         <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-shift')): ?>
                        <a href="javascript:void(0)" data-url="<?php echo e(route('shift.create')); ?>" class="btn add-btn" data-ajax-modal="true"
                            data-size="lg" data-title="<?php echo e(__('Add Shift')); ?>">
                            <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Shift')); ?>

                        </a>
                    <?php endif; ?>

                    <div class="view-icons">
                        <a href="<?php echo e(route('shift.index')); ?>" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                        <a href="<?php echo e(route('shift.list')); ?>" class="list-view btn btn-link"><i class="fa-solid fa-bars"></i></a>
                    </div>
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

    <div class="row">
        <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 col-lg-3">
            <div class="card shift-card shadow-sm">
                <div class="card-body position-relative">

                    <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-shift','delete-shift'])): ?>
                    <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                        <a href="#" class="text-muted" data-bs-toggle="dropdown">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-shift')): ?>
                            <a class="dropdown-item"
                               href="javascript:void(0)"
                               data-url="<?php echo e(route('shift.edit', $shift->id)); ?>"
                               data-ajax-modal="true"
                               data-size="lg"
                               data-title="Edit Shift">
                                <i class="fa fa-pencil me-2"></i> Edit
                            </a>
                            <?php endif; ?>

                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-shift')): ?>
                            <a class="dropdown-item deleteBtn"
                               href="javascript:void(0)"
                               data-route="<?php echo e(route('shift.destroy', $shift->id)); ?>"
                               data-title="Delete Shift"
                               data-question="Are you sure?">
                                <i class="fa fa-trash me-2"></i> Delete
                            </a>
                            <?php endif; ?>

                            <a class="dropdown-item"
                               href="javascript:void(0)"
                               data-url="<?php echo e(route('shift.add-remove-employee-view', $shift->id)); ?>"
                               data-ajax-modal="true"
                               data-size="lg"
                               data-title="Add/Remove Employees From This Shift">
                               <i class="fa-solid fa-users-gear me-2"></i> Add/Remove Employees
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-truncate"><?php echo e($shift->name); ?></h5>
                        <span class="badge bg-inverse-<?php echo e($shift->status == 1 ? 'success' : 'danger'); ?> me-3">
                            <?php echo e($shift->status == 1 ? 'Active' : 'Inactive'); ?>

                        </span>
                    </div>

                    <p class="text-muted mb-3 mt-2">
                        <i class="fa fa-clock me-1"></i>
                        <?php echo e(\Carbon\Carbon::parse($shift->start_time)->format('h:i A')); ?>

                        -
                        <?php echo e(\Carbon\Carbon::parse($shift->end_time)->format('h:i A')); ?>

                    </p>

                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>
                            Break: <?php echo e($shift->break_minutes ?? 0); ?> min
                        </span>
                        <span>
                            Grace: <?php echo e($shift->grace_minutes ?? 0); ?> min
                        </span>
                    </div>

                    <div class="shift-stats mb-3">
                        <h3 class="mb-0"><?php echo e($shift->employees_count); ?></h3>
                        <small class="text-muted">Employees Assigned</small>
                    </div>

                    <a href="<?php echo e(route('shifts.employees', $shift->id)); ?>"
                       class="btn btn-sm btn-outline-primary w-100">
                        View Employees
                    </a>

                </div>
            </div>
        </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/shift/index.blade.php ENDPATH**/ ?>