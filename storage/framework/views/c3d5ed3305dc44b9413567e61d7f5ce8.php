<style>
    .profile-widget {
        padding: 20px 20px 0px 20px !important;
    }
</style>

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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Employees')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Employees')); ?>

                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-employee')): ?>
                    <a href="javascript:void(0)" data-url="<?php echo e(route('employees.create')); ?>" class="btn add-btn"
                        data-ajax-modal="true" data-size="lg" data-title="Add Employee">
                        <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Employee')); ?>

                    </a>
                    <?php endif; ?>
                    <div class="view-icons">
                        <a href="<?php echo e(route('employees.index')); ?>" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                        <a href="<?php echo e(route('employees.list')); ?>" class="list-view btn btn-link"><i class="fa-solid fa-bars"></i></a>
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
        <!-- /Page Header -->


        <div class="row staff-grid-row">
            <?php if(!empty($employees)): ?>
                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                    <div class="profile-widget">
                        <div class="profile-img">
                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-employees')): ?>
                                <a href="<?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($employee->id)])); ?>" class="avatar">
                                    <img src="<?php echo e(!empty($employee->avatar) ? uploadedAsset($employee->avatar,'users') : asset('images/user.jpg')); ?>" alt="User Image">
                                </a>
                            <?php else: ?>
                                <span class="avatar">
                                    <img src="<?php echo e(!empty($employee->avatar) ? uploadedAsset($employee->avatar,'users') : asset('images/user.jpg')); ?>" alt="User Image">
                                </span>
                            <?php endif; ?>

                        </div>
                        <div class="dropdown profile-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-employee', 'delete-employee'])): ?>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                                <a class="dropdown-item" href="javascript:void(0)" data-url="<?php echo e(route('employees.edit', ['employee' => \Crypt::encrypt($employee->id)])); ?>" data-ajax-modal="true"
                                    data-title="Edit Employee" data-size="lg">
                                    <i class="fa-solid fa-pencil m-r-5"></i>
                                    <?php echo e(__('Edit')); ?>

                                </a>
                                <?php endif; ?>
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-employee')): ?>
                                <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('employees.destroy', $employee->id)); ?>" data-title="Delete Employee"
                                    data-question="Are you sure you want to delete?" href="javascript:void(0)">
                                    <i class="fa-regular fa-trash-can m-r-5"></i>
                                    <?php echo e(__('Delete')); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-employees')): ?>
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($employee->id)])); ?>"><?php echo e($employee->fullname); ?></a></h4>
                        <?php else: ?>
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><?php echo e($employee->fullname); ?></h4>
                        <?php endif; ?>

                        <?php if(!empty($employee->employeeDetail) && !empty($employee->employeeDetail->designation)): ?>
                        <div class="small text-muted"><?php echo e($employee->employeeDetail->designation->name); ?></div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-item-center">
                            <div>
                                <span class="badge bg-inverse-<?php echo e($employee->is_active == 1 ? 'success' : 'danger'); ?>"><?php echo e($employee->is_active == 1 ? 'Active' : 'Deactive'); ?></span>
                            </div>

                            <p class="text-sm fw-bold"><?php echo e($employee->shift?->shift?->name ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/employees/index.blade.php ENDPATH**/ ?>