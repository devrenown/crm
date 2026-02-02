

<?php $__env->startPush('page-styles'); ?>
    <style>
    	.card-header-image {
			height: 60px; 
			width: 60px;
			top: 0;
			left: 50%;
			transform: translate(-50%, -50%);
    	}
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-content'); ?>
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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Reporting Managers')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#"><?php echo e(__('Reporting Managers')); ?></a>
                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                   
                    <a href="javascript:void(0)" data-url="<?php echo e(route('reporting-manager.assign.view')); ?>" class="btn add-btn"
                        data-ajax-modal="true" data-size="lg" data-title="Assign Reporting Manager">
                        <i class="fa-solid fa-plus"></i> <?php echo e(__('Assign Project Manager')); ?>

                    </a> 
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
            <?php if(!empty($reportingManagers) && $reportingManagers->count() > 0): ?>
                <?php $__currentLoopData = $reportingManagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex">
                        <div class="card w-100 position-relative">
                            <div class="card-body">

                            	<div class="position-absolute card-header-image mt-2">
                            		<img src="<?php echo e(!empty($list->avatar) ? uploadedAsset($list->avatar, 'users') : asset('images/user.jpg')); ?>" alt="Avatar" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                            	</div>
                                
                                <div class="dropdown dropdown-action profile-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            data-url="<?php echo e(route('reporting-manager.edit', ['reporting_manager' => ($list->id)])); ?>"
                                            data-ajax-modal="true" data-title="Edit Project" data-size="lg">
                                            <i class="fa-solid fa-pencil m-r-5"></i>
                                            <?php echo e(__('Edit')); ?>

                                        </a>
                                    
                                   
                                        <a class="dropdown-item deleteBtn"
                                            data-route="<?php echo e(route('reporting-manager.delete', ['reporting_manager' => $list->id])); ?>" data-title="Delete Reporting Manager"
                                            data-question="Are you sure you want to delete reporting manager?" href="javascript:void(0)">
                                            <i class="fa-regular fa-trash-can m-r-5"></i>
                                            <?php echo e(__('Delete')); ?>

                                        </a>
                                        
                                    </div>
                                </div>
                                
                                <h4 class="project-title mt-4">
                                    
                                    <a href="<?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-Employeeprofile')): ?> <?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($list->id)])); ?> <?php else: ?> # <?php endif; ?>" class="text-primary"><?php echo e($list->fullname); ?></a>
                                </h4>
                                <small class="block text-ellipsis m-b-15">
                                    <span class="text-xs"><?php echo e($list->subordinates->count() ?? 0); ?></span> <span
                                        class="text-muted"><?php echo e(__('Members')); ?></span>
                                </small>
                                <p class="text-muted">
                                    <?php echo e($list->email); ?>

                                </p>
                                <div class="">
                                	<div class="pro-deadline d-flex justify-content-between m-b-15">
                                        <div class="sub-title">
                                            <?php echo e(__('Department')); ?>:
                                        </div>
                                        <div class="text-muted">
                                            <?php echo e(@$list->employeeDetail->department->name); ?>

                                        </div>
                                    </div>

                                    <div class="pro-deadline d-flex justify-content-between m-b-15">
                                        <div class="sub-title">
                                            <?php echo e(__('Designation')); ?>:
                                        </div>
                                        <div class="text-muted">
                                            <?php echo e(@$list->employeeDetail->designation->name); ?>

                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <?php
                                    $reportingManagersTeam = $list->subordinates;
                                ?>
                                <?php if(!empty($reportingManagersTeam) && $reportingManagersTeam->count() > 0): ?>
                                    <div class="project-members m-b-15">
                                        <div><?php echo e(__('Team')); ?> :</div>
                                        <ul class="team-members">
                                            <?php $__currentLoopData = $reportingManagersTeam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <a href="<?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-Employeeprofile')): ?> <?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($team->id)])); ?> <?php else: ?> # <?php endif; ?>"
                                                        data-bs-toggle="tooltip" title="<?php echo e($team->fullname); ?>">
                                                        <img src="<?php echo e(!empty($team->avatar) ? uploadedAsset($team->avatar, 'users') : asset('images/user.jpg')); ?>"
                                                            alt="<?php echo e(__('Avatar')); ?>">
                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            
                                        </ul>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('page-scripts'); ?>
    <!-- Page Js -->
    <?php echo app('Illuminate\Foundation\Vite')([
        "resources/assets/css/ckeditor.css",
        "resources/js/ckeditor.js"
    ]); ?>
    <!-- /Page Js -->
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/reporting-manager/index.blade.php ENDPATH**/ ?>