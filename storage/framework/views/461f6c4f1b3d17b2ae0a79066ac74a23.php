<?php $__env->startPush('page-styles'); ?>
    <!-- Page Css -->
    <!-- /Page Css -->
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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Projects')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#"><?php echo e(__('Projects')); ?></a>
                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-project')): ?>
                        <a href="javascript:void(0)" data-url="<?php echo e(route('projects.create')); ?>" class="btn add-btn"
                            data-ajax-modal="true" data-size="lg" data-title="Add Project">
                            <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Project')); ?>

                        </a>
                    <?php endif; ?>
                    <div class="view-icons">
                        <a href="<?php echo e(route('projects.index')); ?>" class="grid-view btn btn-link active"><i
                                class="fa fa-th"></i></a>
                        
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

        <div class="row">
            <?php if(!empty($projects) && $projects->count() > 0): ?>
                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex">
                        <div class="card w-100">
                            <div class="card-body">
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['view-projects', 'edit-project', 'delete-project'])): ?>
                                    <div class="dropdown dropdown-action profile-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-projects')): ?>
                                                <a class="dropdown-item" href="<?php echo e(route('projects.show', ['project' => \Crypt::encrypt($project->id)])); ?>">
                                                    <i class="fa-solid fa-eye m-r-5"></i>
                                                    <?php echo e(__('View Details')); ?>

                                                </a>
                                            <?php endif; ?>

                                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-project')): ?>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    data-url="<?php echo e(route('projects.edit', ['project' => ($project->id)])); ?>"
                                                    data-ajax-modal="true" data-title="Edit Project" data-size="lg">
                                                    <i class="fa-solid fa-pencil m-r-5"></i>
                                                    <?php echo e(__('Edit')); ?>

                                                </a>
                                            <?php endif; ?>
                                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-project')): ?>
                                                <a class="dropdown-item deleteBtn"
                                                    data-route="<?php echo e(route('projects.destroy', $project->id)); ?>" data-title="Delete Project"
                                                    data-question="Are you sure you want to delete project?" href="javascript:void(0)">
                                                    <i class="fa-regular fa-trash-can m-r-5"></i>
                                                    <?php echo e(__('Delete')); ?>

                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <h4 class="project-title">
                                    <?php echo e($project->name); ?>

                                </h4>
                                <small class="block text-ellipsis m-b-15">
                                    <span class="text-xs"><?php echo e($project->tasks->count() ?? 0); ?></span> <span
                                        class="text-muted"><?php echo e(__('Opened Tasks')); ?></span>
                                    <span class="text-xs"><?php echo e($project->tasks->count() ?? 0); ?></span> <span
                                        class="text-muted"><?php echo e(__('Tasks Completed')); ?></span>
                                </small>
                                <p class="text-muted">
                                    <?php echo e($project->short_desc); ?>

                                </p>
                                <div class="d-flex justify-content-between">
                                    <div class="pro-deadline m-b-15">
                                        <div class="sub-title">
                                            <?php echo e(__('Start Date')); ?>:
                                        </div>
                                        <div class="text-muted">
                                            <?php echo e(format_date($project->startDate)); ?>

                                        </div>
                                    </div>
                                    <div class="pro-deadline m-b-15">
                                        <div class="sub-title">
                                            <?php echo e(__('Deadline')); ?>:
                                        </div>
                                        <div class="text-muted">
                                            <?php echo e(format_date($project->endDate)); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="project-members m-b-15">
                                    <div><?php echo e(__('Project Leader')); ?> :</div>
                                    <ul class="team-members">
                                        <?php if(!empty($project->leader_id)): ?>
                                            <li>
                                                <a href="<?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-Employeeprofile')): ?> <?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($project->leader_id)])); ?> <?php else: ?> # <?php endif; ?>"
                                                    data-bs-toggle="tooltip" title="<?php echo e($project->leader->fullname); ?>">
                                                    <img src="<?php echo e(!empty($project->leader->avatar) ? uploadedAsset($project->leader->avatar, 'users') : asset('images/user.jpg')); ?>"
                                                        alt="<?php echo e(__('Avatar')); ?>">
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <?php
                                    $projectTeam = $project->team;
                                ?>
                                <?php if(!empty($projectTeam) && $projectTeam->count() > 0): ?>
                                    <div class="project-members m-b-15">
                                        <div><?php echo e(__('Team')); ?> :</div>
                                        <ul class="team-members">
                                            <?php $__currentLoopData = $projectTeam->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <a href="<?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-Employeeprofile')): ?> <?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($member->user->id)])); ?> <?php else: ?> # <?php endif; ?>"
                                                        data-bs-toggle="tooltip" title="<?php echo e($member->user->fullname); ?>">
                                                        <img src="<?php echo e(!empty($member->user->avatar) ? uploadedAsset($member->user->avatar, 'users') : asset('images/user.jpg')); ?>"
                                                            alt="<?php echo e(__('Avatar')); ?>">
                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(!empty($projectTeam) && $projectTeam->count() > 4): ?>
                                                <li class="dropdown avatar-dropdown">
                                                    <a href="#" class="all-users dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-expanded="false"><?php echo e(!empty($projectTeam) && $projectTeam->count() > 0 ? ($projectTeam->count() - 4) : ''); ?></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <div class="avatar-group">
                                                            <?php $__currentLoopData = $projectTeam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <a class="avatar avatar-xs" data-bs-toggle="tooltip"
                                                                    title="<?php echo e($member->user->fullname); ?>"
                                                                    href="<?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-Employeeprofile')): ?> <?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($member->user->id)])); ?> <?php else: ?> # <?php endif; ?>">
                                                                    <img src="<?php echo e(!empty($member->user->avatar) ? uploadedAsset($member->user->avatar, 'users') : asset('images/user.jpg')); ?>"
                                                                        alt="<?php echo e($member->user->fullname); ?>">
                                                                </a>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endif; ?>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/Modules/Project/resources/views/index.blade.php ENDPATH**/ ?>