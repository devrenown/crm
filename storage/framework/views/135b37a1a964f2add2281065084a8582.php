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
         <?php $__env->slot('title', null, []); ?> <?php echo e($project->name); ?> <?php $__env->endSlot(); ?>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
            </li>
            <li class="breadcrumb-item active">
                <?php echo e(__('Task Board')); ?>

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

    <div class="row board-view-header">
        <div class="col-4">
            <div class="pro-teams">
                <div class="pro-team-lead">
                    <h4><?php echo e(__('Lead')); ?></h4>
                    <div class="avatar-group">
                        <div class="avatar">
                            <img class="avatar-img rounded-circle border border-white" src="<?php echo e(!empty($project->leader->avatar) ? uploadedAsset($project->leader->avatar,'users'): asset('images/user.jpg')); ?>" alt="<?php echo e(__('avatar')); ?>">
                        </div>
                    </div>
                </div>
                <?php
                    $projectTeam = $project->team;
                ?>
                <?php if(!empty($projectTeam) && $projectTeam->count()): ?>  
                <div class="pro-team-members">
                    <h4><?php echo e(__('Team')); ?></h4>
                    <div class="avatar-group">
                        <?php $__currentLoopData = $projectTeam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="avatar">
                            <img class="avatar-img rounded-circle border border-white" src="<?php echo e(!empty($member->user->avatar) ? uploadedAsset($member->user->avatar,'users'): asset('images/user.jpg')); ?>" alt="<?php echo e($member->user->fullname.' avatar'); ?>">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-8 text-end">
            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-task')): ?>
            <a href="javascript:void(0)" class="btn btn-white float-end ms-2"
                data-url="<?php echo e(route('task-boards.create',['project_id' => $project->id])); ?>" data-ajax-modal="true"
                data-size="md" data-title="Add Task Board">
                <i class="fa-solid fa-plus"></i> <?php echo e(__('Create List')); ?>

            </a>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-projects')): ?>
            <a href="<?php echo e(route('projects.show', ['project' => \Crypt::encrypt($project->id)])); ?>" class="btn btn-white float-end" data-bs-toggle="tooltip" title="View Project"><i class="fa fa-link"></i></a>
            <?php endif; ?>
        </div>
    </div>    
    <div class="kanban-board card mb-0">
        <div class="card-body">
            <div class="kanban-cont">
                <?php $__currentLoopData = $taskBoards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $board): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="kanban-list">
                    <div class="kanban-header" style="background: <?php echo e($board->color ?? '#42a5f5'); ?>;">
                        <span class="status-title"><?php echo e($board->name); ?></span>
                        <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-task', 'delete-task'])): ?>
                        <div class="dropdown kanban-action">
                            <a href="#" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-task')): ?>
                                <a href="javascript:void(0)" class="dropdown-item" data-url="<?php echo e(route('task-boards.edit', ['task_board' => $board->id, 'project_id' => $project->id])); ?>" data-ajax-modal="true"
                                    data-title="<?php echo e(__('Edit Task Board')); ?>" data-size="md"><?php echo e(__('Edit')); ?></a>
                                <?php endif; ?>
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-task')): ?>
                                <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('task-boards.destroy', ['task_board' => $board->id, 'project_id' => $project->id])); ?>" data-title="<?php echo e(__('Delete Task Board')); ?>"
                                    data-question="<?php echo e(__('Are you sure you want to delete taskboard?')); ?>" href="javascript:void(0)"> 
                                    <?php echo e(__('Delete')); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php
                        $tasks = $board->tasks()->orderBy('priority')->get()
                    ?>
                    <div class="kanban-wrap" data-board="<?php echo e($board->id); ?>">
                        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card panel" style="color: <?php echo e($board->color ?? '#42a5f5'); ?> !important;" data-id="<?php echo e($task->priority); ?>" data-task="<?php echo e($task->id); ?>" data-board="<?php echo e($board->id); ?>">
                            <div class="kanban-box">
                                <div class="task-board-header">
                                    <span class="status-title"><a href="javascript:void(0);"><?php echo e($task->name); ?></a></span>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-task', 'delete-task'])): ?>
                                    <div class="dropdown kanban-task-action">
                                        <a href="#" data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                        
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-task')): ?>
                                            <a class="dropdown-item" href="javascript:void(0)" data-ajax-modal="true" data-title="<?php echo e(__('Edit Task')); ?>" data-url="<?php echo e(route('project-tasks.edit',$task->id)); ?>" data-size="md">
                                                <?php echo e(__('Edit')); ?>

                                            </a>
                                            <?php endif; ?>
                                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-task')): ?>
                                            <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('project-tasks.destroy', $task->id)); ?>" data-title="<?php echo e(__('Delete Task')); ?>"
                                                data-question="<?php echo e(__('Are you sure you want to delete Task?')); ?>" href="javascript:void(0)">
                                                <?php echo e(__('Delete')); ?>

                                            </a>
                                            <?php endif; ?>
                                        </div>
                                        
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="task-board-body">
                                    <div class="kanban-info">
                                        <p><?php echo e($task->description); ?></p>
                                    </div>
                                    <div class="kanban-footer">
                                        <span class="task-info-cont">
                                            <span class="task-date"><i class="fa-regular fa-clock"></i> <?php echo e(format_date($task->startDate)); ?> - <?php echo e(format_date($task->endDate)); ?></span>
                                        </span>
                                        <span class="task-users">
                                            <?php if(!empty($task->followers) && $task->followers->count() > 0): ?>
                                            <img src="<?php echo e(!empty($task->followers->first()->user->avatar) ? uploadedAsset($task->followers->first()->user->avatar,'users'): asset('images/user.jpg')); ?>" class="task-avatar" width="24" height="24" alt="avatar">
                                            <span class="task-user-count"><?php echo e($task->followers->count() ?? 0); ?></span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-task')): ?>
                    <div class="add-new-task">
                        <a href="javascript:void(0);" data-ajax-modal="true" data-url="<?php echo e(route('project-tasks.create',['project' => $project->id,'board' => $board->id])); ?>" data-size="md" data-title="<?php echo e(__('Add Task')); ?>"><?php echo e(__('Add New Task')); ?></a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>  

</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('page-scripts'); ?>
    <!-- Page Js -->
    <script type="module">
        var taskBoxWrapper = [].slice.call(document.querySelectorAll('.kanban-wrap'));
        for (var i = 0; i < taskBoxWrapper.length; i++) {
            new Sortable(taskBoxWrapper[i], {
                group: 'taskboard',
                handle: ".kanban-box",
                draggable: ".panel",
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                dataIdAttr: 'data-id', 
                onEnd: function (event) {
                    var element = $(event.item)
                    var priority = event.newIndex
                    var taskId = element.data('task')
                    let taskBoard = $(event.to).data('board')
                    $.ajax({
                        url: "<?php echo e(route('project-task.update-dragged')); ?>",
                        type: "POST",
                        data: {
                            task: taskId,
                            priority: priority,
                            board: taskBoard,
                        },  
                        success: function(e)
                        {
                            if(e.success){
                                Toastify({
                                    text: "<?php echo e(__('Task updated successfully')); ?>",
                                    className: "success",
                                }).showToast();
                            }else{
                                alert('something went wrong')
                            }
                        }
                    })                   
                },
            });
        }
    </script>
    <!-- /Page Js -->
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/Modules/Project/resources/views/tasks/index.blade.php ENDPATH**/ ?>