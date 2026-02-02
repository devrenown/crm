<?php $__env->startPush('page-style'); ?>

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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Clients')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Clients')); ?>

                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-client')): ?>
                        <a href="javascript:void(0)" data-url="<?php echo e(route('clients.create')); ?>" class="btn add-btn"
                            data-ajax-modal="true" data-size="lg" data-title="Add Client">
                            <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Client')); ?>

                        </a>
                    <?php endif; ?>

                    <div class="view-icons">
                        <a href="<?php echo e(route('clients.index')); ?>" class="grid-view btn btn-link active"><i
                                class="fa fa-th"></i></a>
                        <a href="<?php echo e(route('clients.list')); ?>" class="list-view btn btn-link"><i
                                class="fa-solid fa-bars"></i></a>
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
            <?php if(!empty($clients)): ?>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $showRoute = route('clients.show', ['client' => \Crypt::encrypt($client->id)]);
                    ?>
                    <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                        <div class="profile-widget">
                            <div class="profile-img">
                                <a href="<?php echo e($showRoute); ?>" class="avatar">
                                    <img src="<?php echo e(!empty($client->avatar) ? uploadedAsset($client->avatar, 'users') : asset('images/user.jpg')); ?>"
                                        alt="User Image">
                                </a>
                            </div>

                            <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-client','delete-client'])): ?>
                            <div class="dropdown profile-action">
                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                                        class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-client')): ?>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        data-url="<?php echo e(route('clients.edit', ['client' => \Crypt::encrypt($client->id)])); ?>"
                                        data-ajax-modal="true" data-title="Edit Client" data-size="lg">
                                        <i class="fa-solid fa-pencil m-r-5"></i>
                                        <?php echo e(__('Edit')); ?>

                                    </a>
                                    <?php endif; ?>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-client')): ?>
                                    <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('clients.destroy', $client->id)); ?>"
                                        data-title="Delete Client" data-question="Are you sure you want to delete?"
                                        href="javascript:void(0)">
                                        <i class="fa-regular fa-trash-can m-r-5"></i>
                                        <?php echo e(__('Delete')); ?>

                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?php echo e($showRoute); ?>"><?php echo e($client->fullname); ?></a>
                            </h4>
                            <?php if(!empty($client->clientDetail) && !empty($client->clientDetail->designation)): ?>
                                <div class="small text-muted"><?php echo e($client->clientDetail->designation->name); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/clients/index.blade.php ENDPATH**/ ?>