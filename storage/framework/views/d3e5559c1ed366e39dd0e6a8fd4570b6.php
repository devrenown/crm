<?php $__env->startPush('page-styles'); ?>
    <!-- Page Css -->
    <!-- /Page Css -->
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-content'); ?>
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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Task Board')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Task Boards')); ?>

                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-taskboard')): ?>
                        <a data-url="<?php echo e(route('task-boards.create')); ?>" href="javascript:void(0)" class="btn add-btn"
                            data-ajax-modal="true" data-size="md" data-title="Add Task Board">
                            <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Board')); ?>

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
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped custom-table w-100">
                            <thead>
                                <tr class="text-center">
                                    <th><i class="fa-solid fa-crosshairs"></i></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Color')); ?></th>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-taskboard', 'delete-taskboard'])): ?>
                                        <th><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $boards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-center">
                                        <td><i class="fa-solid fa-crosshairs"></i></td>
                                        <td><?php echo e($item->name); ?></td>
                                        <td><input type="color" value="<?php echo e($item->color); ?>" disabled></td>
                                        <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-taskboard', 'delete-taskboard'])): ?>
                                            <td>
                                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-taskboard')): ?>
                                                    <a href="javascript:void(0)" data-url="<?php echo e(route('task-boards.edit', $item->id)); ?>"
                                                        data-ajax-modal="true" data-title="<?php echo e(__('Edit Task Board')); ?>"
                                                        data-size="md"><i class="fa-solid fa-pencil"></i>
                                                        <?php echo e(__('Edit')); ?>

                                                    </a>
                                                <?php endif; ?>
                                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-taskboard')): ?>
                                                    <a class="deleteBtn ms-2" data-route="<?php echo e(route('task-boards.destroy', $item->id)); ?>"
                                                        data-title="<?php echo e(__('Delete Task Board')); ?>"
                                                        data-question="<?php echo e(__('Are you sure you want to delete taskboard?')); ?>"
                                                        href="javascript:void(0)">
                                                        <i class="fa-regular fa-trash-can"></i><?php echo e(__('Delete')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('page-scripts'); ?>
    <!-- Page Js -->

    <!-- /Page Js -->
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/Modules/Project/resources/views/task-board/index.blade.php ENDPATH**/ ?>