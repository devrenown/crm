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
         <?php $__env->slot('title', null, []); ?> <?php echo e($project->name); ?> <?php $__env->endSlot(); ?>
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
                <div class="view-icons">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-taskboards')): ?>
                    <a href="<?php echo e(route('project.taskboard',['id' => \Crypt::encrypt($project->id)])); ?>" class="grid-view btn btn-link p-2 text-decoration-none" data-bs-toggle="tooltip" title="<?php echo e(__('Task Board')); ?>"><i class="fa fa-th"></i> <?php echo e(__('Task Board')); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('projects.index')); ?>" class="grid-view btn btn-link" data-bs-toggle="tooltip" title="<?php echo e(__('Projects Grid View')); ?>"><i class="fa fa-th"></i></a>
                    
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-project')): ?>
                    <a href="javascript:void(0)" data-url="<?php echo e(route('projects.edit', ['project' => ($project->id)])); ?>" data-ajax-modal="true"
                        data-title="Edit Project" data-size="lg" class="list-view btn btn-link" data-bs-toggle="tooltip" title="<?php echo e(__('Edit Project')); ?>">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-project')): ?>
                    <a data-route="<?php echo e(route('projects.destroy', $project->id)); ?>" data-title="Delete Project" class="list-view btn btn-link deleteBtn"
                        data-question="Are you sure you want to delete project?" href="javascript:void(0)" data-bs-toggle="tooltip" title="<?php echo e(__('Delete Project')); ?>">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                    <?php endif; ?>
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
        <div class="col-lg-8 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div class="project-title">
                        <h5 class="card-title"><?php echo e(__('Brief Description')); ?></h5>
                        <small class="block text-ellipsis m-b-15"><span class="text-xs"><?php echo e($project->tasks->count() ?? 0); ?></span> 
                            <span class="text-muted"><?php echo e(__('Opened Tasks')); ?>, </span>
                            <span class="text-xs"><?php echo e($project->tasks->count() ?? 0); ?></span> <span class="text-muted"><?php echo e(__('Tasks Completed')); ?></span>
                        </small>
                        <p><?php echo e($project->short_desc); ?></p>
                    </div>
                    
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-20"><?php echo e(__('Full Description')); ?></h5>
                    <div class="row">
                        <div class="col-12">
                            <?php echo $project->description; ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php if(!empty($projectFiles) && $projectFiles->count() > 0): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-20"><?php echo e(__('Uploaded files')); ?></h5>
                    <ul class="files-list">
                        <?php $__currentLoopData = $projectFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <li>
                            <div class="files-cont">
                                <div class="file-type">
                                    <span class="files-icon">
                                        <?php switch($file->mime_type):
                                            case ('application/pdf'): ?>
                                                <i class="fa-regular fa-file-pdf"></i>
                                                <?php break; ?>
                                            <?php case ('image/jpeg' || 'image/png' || 'image/jpg'): ?>
                                                <i class="fa-regular fa-file-pdf"></i>
                                                <?php break; ?>
                                            <?php default: ?>
                                            <i class="fa-regular fa-file-file"></i>
                                        <?php endswitch; ?>
                                    </span>
                                </div>
                                <div class="files-info">
                                    <span class="file-name text-ellipsis"><a target="_blank" href="<?php echo e($file->getFullUrl()); ?>"><?php echo e($file->file_name); ?></a></span>
                                    <span class="file-date"><?php echo e(__('Date')); ?>: <?php echo e(format_date($file->created_at)); ?></span>
                                    <div class="file-size"><?php echo e(__('Size')); ?>: <?php echo e(format_file_size($file->size)); ?></div>
                                </div>
                                <ul class="files-action">
                                    <li class="dropdown dropdown-action">
                                        <a href="#" class="dropdown-toggle btn btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_horiz</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="<?php echo e($file->getFullUrl()); ?>" download=""><?php echo e(__('Download')); ?></a>
                                            <a class="dropdown-item deleteBtn" href="javascript:void(0)" data-route="<?php echo e(route('project-file.destroy', $file->id)); ?>" data-title="Delete Project File"
                                                data-question="Are you sure you want to delete project file?"><?php echo e(__('Delete')); ?></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div> 
            <?php endif; ?>
            
        </div>
        <div class="col-lg-4 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title m-b-15"><?php echo e(__('Project details')); ?></h6>
                    <table class="table table-striped table-border">
                        <tbody>
                            <tr>
                                <td><?php echo e(__('Cost')); ?>:</td>
                                <td class="text-end">
                                    
                                    <?php echo e(LocaleSettings('currency_symbol')); ?> <?php echo e(($project->rateType == 'Fixed') ? $project->rate: ($project->rate)); ?>

                                </td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Created')); ?>:</td>
                                <td class="text-end"><?php echo e(format_date($project->created_at)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Date Started')); ?>:</td>
                                <td class="text-end"><?php echo e(format_date($project->startDate)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Deadline')); ?>:</td>
                                <td class="text-end"><?php echo e(format_date($project->endDate)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e(__('Priority')); ?>:</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <?php switch($project->priority):
                                            case ('High'): ?>
                                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> <?php echo e(__('Highest priority')); ?></a>
                                            <?php break; ?>
                                            <?php case ('Medium'): ?>
                                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-info"></i><?php echo e(__('Medium priority')); ?></a>
                                                <?php break; ?>
                                            <?php case ('Normal'): ?>
                                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-primary"></i> <?php echo e(__('Normal priority')); ?></a>
                                                <?php break; ?>
                                            <?php case ('Low'): ?>
                                                <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-success"></i> <?php echo e(__('Low priority')); ?></a>
                                                <?php break; ?>
                                            <?php default: ?>
                                            <a class="dropdown-item" href="#"><i class="fa-regular fa-circle-dot text-success"></i> <?php echo e(__('Low priority')); ?></a>
                                        <?php endswitch; ?>    
                                    </div>
                                </td>
                               
                            </tr>
                            <tr>
                                <td><?php echo e(__('Created by')); ?>:</td>
                                <td class="text-end"><a href="#"><?php echo e($project->createdBy->fullname); ?></a></td>
                            </tr>
                            <?php if(!empty($project->status)): ?>
                            <tr>
                                <td><?php echo e(__('Status')); ?>:</td>
                                <td class="text-end"><?php echo e($project->status); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
            <div class="card project-user">
                <div class="card-body">
                    <h6 class="card-title m-b-20"><?php echo e(__('Project Leader')); ?> 
                    </h6>
                    <ul class="list-box">
                        <li>
                            <a href="<?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-Employeeprofile')): ?> <?php echo e(route('employees.show', ['employee' => \Crypt::encrypt($project->leader_id)])); ?> <?php else: ?> # <?php endif; ?>">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar"><img src="<?php echo e(!empty($project->leader->avatar) ? uploadedAsset($project->leader->avatar,'users'): asset('images/user.jpg')); ?>" alt="<?php echo e(__('avatar')); ?>"></span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"><?php echo e($project->leader->fullname); ?></span>
                                        <div class="clearfix"></div>
                                        <span class="message-content"><?php echo e(__('Team Leader')); ?></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
                $projectTeam = $project->team;
            ?>
            <?php if(!empty($projectTeam) && $projectTeam->count()): ?>    
            <div class="card project-user">
                <div class="card-body">
                    <h6 class="card-title m-b-20">
                        <?php echo e(__('Assigned Team')); ?>

                    </h6>
                    <ul class="list-box">  
                        <?php $__currentLoopData = $projectTeam->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="profile.html">
                                <div class="list-item">
                                    <div class="list-left">
                                        <span class="avatar">
                                            <img src="<?php echo e(!empty($member->user->avatar) ? uploadedAsset($member->user->avatar,'users'): asset('images/user.jpg')); ?>" alt="<?php echo e($member->user->fullname.' avatar'); ?>">
                                        </span>
                                    </div>
                                    <div class="list-body">
                                        <span class="message-author"><?php echo e($member->user->fullname); ?></span>
                                        <div class="clearfix"></div>
                                        <span class="message-content"><?php echo e($member->user->employeeDetail->designation->name ?? ''); ?></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/Modules/Project/resources/views/show.blade.php ENDPATH**/ ?>