<?php $__env->startSection('page-content'); ?>
<style>
    .sticky-left {
        position: sticky;
        top: 80px;
        height: fit-content;
    }

    .table-scroll {
        max-height: calc(100vh - 180px);
        overflow-y: auto;
    }

    .custom-table thead th {
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 10;
    }
</style>

    <div class="content container-fluid">

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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Roles')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#"><?php echo e(__('Roles')); ?></a>
                </li>
            </ul>
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
            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3 sticky-left">
                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-role')): ?>
                <a href="javascript:void(0)" data-url="<?php echo e(route('roles.create')); ?>" data-title="<?php echo e(__('Add Role')); ?>" data-ajax-modal="true" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo e(__('Add Roles')); ?></a>
                <?php endif; ?>
                <div class="roles-menu">
                    <ul>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="<?php echo e(!empty($selected_role) && ($role->id == $selected_role->id) ? 'active': ''); ?>">
                            <a href="javascript:void(0)">
                                <span onclick="window.location.href=`<?php echo e(route('roles.index', ['id' => \Crypt::encrypt($role->id)])); ?>`">
                                    <?php echo e($role->name); ?>

                                </span>
                                <span class="role-action">
                                    <span class="action-circle large" data-bs-toggle="tooltip" title="<?php echo e(__("Assign Permissions")); ?>"
                                        onclick="window.location.href=`<?php echo e(route('roles.index', ['id' => \Crypt::encrypt($role->id)])); ?>`">
                                        <i class="fa-solid fa-eye"></i>
                                    </span>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-role')): ?>
                                    <span class="action-circle large" data-bs-toggle="tooltip" title="<?php echo e(__("Edit Role")); ?>"
                                        data-url="<?php echo e(route('roles.edit', $role->id)); ?>" data-title="<?php echo e(__('Edit Role')); ?>" data-ajax-modal="true"
                                    >
                                        <i class="material-icons">edit</i>
                                    </span>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-role')): ?>
                                    <span class="action-circle large delete-btn deleteBtn" data-bs-toggle="tooltip" title="<?php echo e(__('Delete Role')); ?>"
                                        data-route="<?php echo e(route('roles.destroy', $role->id)); ?>" data-title="<?php echo e(__('Delete Role')); ?>"
                                        data-question="<?php echo e(__('Are you sure you want to delete role?')); ?>">
                                        <i class="material-icons">delete</i>
                                    </span>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>

            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9">
                <div class="table-scroll">
                    <form <?php if(!empty($selected_role)): ?> action="<?php echo e(route('permissions.update', $selected_role->id)); ?>" <?php endif; ?> method="post">
                        <?php echo csrf_field(); ?>
                        <table class="table table-striped custom-table">
                            <thead class="">
                                <tr>
                                    <th><?php echo e(__('Module Permission')); ?></th>
                                    <th class="text-center"><?php echo e(__('Create')); ?></th>
                                    <th class="text-center"><?php echo e(__('Read')); ?></th>
                                    <th class="text-center"><?php echo e(__('Edit')); ?></th>
                                    <th class="text-center"><?php echo e(__('Delete')); ?></th>
                                    <th class="text-center"><?php echo e(__('Import')); ?></th>
									<th class="text-center"><?php echo e(__('Export')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="text-center">
                            <td colspan="7" class="fw-bold bg-light"><?php echo e(ucwords($category)); ?></td>
                        </tr>
                        <?php $__currentLoopData = $modulePermissions->groupBy('module'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(ucwords($module)); ?></td>
                            <td class="text-center">
                                <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(str_starts_with($item->name, 'create-')): ?>
                                    <label class="custom_check">
                                        <input type="checkbox" name="permissions[]" value="<?php echo e($item->name); ?>"
                                        <?php if(!empty($selected_role) && $selected_role->hasPermissionTo($item->name)): ?> checked <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td class="text-center">
                                <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(str_starts_with($item->name, 'view-')): ?>
                                    <label class="custom_check">
                                        <input type="checkbox" name="permissions[]" value="<?php echo e($item->name); ?>"
                                        <?php if(!empty($selected_role) && $selected_role->hasPermissionTo($item->name)): ?> checked <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td class="text-center">
                                <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(str_starts_with($item->name, 'edit-')): ?>
                                    <label class="custom_check">
                                        <input type="checkbox" name="permissions[]" value="<?php echo e($item->name); ?>"
                                        <?php if(!empty($selected_role) && $selected_role->hasPermissionTo($item->name)): ?> checked <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td class="text-center">
                                <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(str_starts_with($item->name, 'delete-')): ?>
                                    <label class="custom_check">
                                        <input type="checkbox" name="permissions[]" value="<?php echo e($item->name); ?>"
                                        <?php if(!empty($selected_role) && $selected_role->hasPermissionTo($item->name)): ?> checked <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td class="text-center">
                                <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(str_starts_with($item->name, 'import-')): ?>
                                    <label class="custom_check">
                                        <input type="checkbox" name="permissions[]" value="<?php echo e($item->name); ?>"
                                        <?php if(!empty($selected_role) && $selected_role->hasPermissionTo($item->name)): ?> checked <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td class="text-center">
                                <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(str_starts_with($item->name, 'export-')): ?>
                                    <label class="custom_check">
                                        <input type="checkbox" name="permissions[]" value="<?php echo e($item->name); ?>"
                                        <?php if(!empty($selected_role) && $selected_role->hasPermissionTo($item->name)): ?> checked <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                        </table>
                        <?php if(!empty($selected_role)): ?>
                        <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-permission')): ?>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn" type="submit"><?php echo e(__('Update')); ?></button>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/Modules/Roles/resources/views/index.blade.php ENDPATH**/ ?>