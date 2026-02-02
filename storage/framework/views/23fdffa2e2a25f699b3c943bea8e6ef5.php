

<?php $__env->startSection('page-content'); ?>
<?php $user = auth()->user(); ?>

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
         <?php $__env->slot('title', null, []); ?> <?php echo e(__('Leave Management')); ?> <?php $__env->endSlot(); ?>

         <?php $__env->slot('right', null, []); ?> 
            <div class="col-auto float-end ms-auto">

                
                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-leave')): ?>
                    <a href="<?php echo e(route('leaves.create')); ?>" class="btn add-btn">
                        <i class="fa-solid fa-plus"></i> <?php echo e(__('New Leave Request')); ?>

                    </a>
                <?php endif; ?>

                
                <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['view-leave-type','edit-leave-type','delete-leave-type'])): ?>
                    <a href="<?php echo e(route('leave-type.index')); ?>" class="btn btn-outline-primary">
                        <i class="fa-solid fa-cogs"></i> <?php echo e(__('Manage Leave Types')); ?>

                    </a>
                <?php endif; ?>

            </div>
         <?php $__env->endSlot(); ?>

        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo e(__('Leave Requests')); ?></li>
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

    <!-- Dashboard Summary -->

    <style>
        .premium-card {
            border: 0;
            border-radius: 18px;
            background: #ffffff;
            padding: 18px;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.09);
        }
        .premium-card .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 10px;
            color: #fff;
        }

        /* Luxury Gradients */
        .grad-blue { background: linear-gradient(135deg, #0052d4, #4364f7, #6fb1fc); }
        .grad-green { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .grad-orange { background: linear-gradient(135deg, #fc4a1a, #f7b733); }
        .grad-purple { background: linear-gradient(135deg, #8e2de2, #4a00e0); }
        .grad-red { background: linear-gradient(135deg, #ff416c, #ff4b2b); }

        .value-text {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .label-text {
            font-size: 14px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 6px;
        }
    </style>

    <div class="row g-3 mb-3">

        
        <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-leave-summary')): ?>

            <!--
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="premium-card">
                    <div class="icon-box grad-purple"><i class="bi bi-wallet-fill"></i></div>
                    <p class="label-text"><?php echo e(__('Available Balance')); ?></p>
                    <h3 class="value-text text-primary">
                        <?php echo e($user->leaveBalance() ?? 0); ?> <span class="fs-6"><?php echo e(__('days')); ?></span>
                    </h3>
                </div>
            </div>
            -->

        
           
        <?php if($user->isEmployee()): ?>
        <?php $__currentLoopData = $leaveSummary ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="premium-card">
                    <div class="icon-box grad-blue">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div class="small">
                        <strong><?php echo e($summary['name']); ?></strong>

                        <div class="text-muted mb-1">
                            <strong>Mode:</strong> <?php echo e($summary['mode']); ?>

                        </div>

                        
                        <div>
                            <strong>
                                <?php if($summary['mode'] === 'Monthly'): ?>
                                    Earned:
                                <?php elseif($summary['mode'] === 'Unlimited'): ?>
                                    Entitlement:
                                <?php elseif($summary['mode'] === 'Not Eligible Yet'): ?>
                                    Entitlement:
                                <?php else: ?>
                                    Total:
                                <?php endif; ?>
                            </strong>

                            <?php if($summary['mode'] === 'Monthly'): ?>
                                <?php echo e($summary['earned']); ?>


                            <?php elseif($summary['mode'] === 'Unlimited'): ?>
                                Unlimited

                            <?php elseif($summary['mode'] === 'Not Eligible Yet'): ?>
                                0

                            <?php else: ?> 
                                <?php echo e($summary['earned'] ?? $summary['total'] ?? 0); ?>

                            <?php endif; ?>
                        </div>

                        
                        <div>
                            <strong>Used:</strong> <?php echo e($summary['used'] ?? 0); ?>

                        </div>

                        
                        <div>
                            <strong>Remaining:</strong>

                            <?php if($summary['mode'] === 'Unlimited'): ?>
                                Unlimited
                            <?php else: ?>
                                <?php echo e($summary['remaining'] ?? 0); ?>

                            <?php endif; ?>
                        </div>

                        
                        <?php if($summary['mode'] === 'Monthly' && isset($summary['earned_till_now'])): ?>
                            <small class="text-muted d-block">
                                (<?php echo e($summary['earned_till_now']); ?> earned till date)
                            </small>
                        <?php endif; ?>

                        
                        <?php if($summary['mode'] === 'Not Eligible Yet' && isset($summary['eligible_on'])): ?>
                            <small class="text-warning d-block">
                                Eligible from <?php echo e(\Carbon\Carbon::parse($summary['eligible_on'])->format('d M Y')); ?>

                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php endif; ?>


        
        <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-leave-request-summary')): ?>

            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-orange"><i class="bi bi-hourglass-split"></i></div>
                    <p class="label-text"><?php echo e(__('Pending Requests')); ?></p>
                    <h3 class="value-text text-warning"><?php echo e($pendingCount ?? 0); ?></h3>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-green"><i class="bi bi-check2-circle"></i></div>
                    <p class="label-text"><?php echo e(__('Approved Requests')); ?></p>
                    <h3 class="value-text text-success"><?php echo e($approvedCount ?? 0); ?></h3>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-purple"><i class="bi bi-x-octagon"></i></div>
                    <p class="label-text"><?php echo e(__('Rejected Requests')); ?></p>
                    <h3 class="value-text text-danger"><?php echo e($rejectedCount ?? 0); ?></h3>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="premium-card">
                    <div class="icon-box grad-red"><i class="bi bi-x-circle"></i></div>
                    <p class="label-text"><?php echo e(__('Cancelled Requests')); ?></p>
                    <h3 class="value-text text-danger"><?php echo e($cancelledCount ?? 0); ?></h3>
                </div>
            </div>

        <?php endif; ?>
    </div>


    <!-- /Dashboard Summary -->

    <!-- Filters  -->
    <?php if(!$user->isEmployee()): ?>
        <div class="card card-default mb-4">
            <div class="card-header"><?php echo e(__('Search & Filter')); ?></div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('leaves.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>"
                            class="form-control"
                            placeholder="Search by reason or employee...">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value=""><?php echo e(__('All Status')); ?></option>
                                <option value="Pending" <?php echo e(request('status') == 'Pending' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Pending')); ?>

                                </option>
                                <option value="Approved" <?php echo e(request('status') == 'Approved' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Approved')); ?>

                                </option>
                                <option value="Rejected" <?php echo e(request('status') == 'Rejected' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Rejected')); ?>

                                </option>
                                <option value="Cancelled" <?php echo e(request('status') == 'Cancelled' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Cancelled')); ?>

                                </option>

                                <option value="Pending_level_1" <?php echo e(request('status') == 'Pending_level_1' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Pending L1')); ?>

                                </option>
                                <option value="Pending_level_2" <?php echo e(request('status') == 'Pending_level_2' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Pending L2')); ?>

                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="leave_type" class="form-select">
                                <option value=""><?php echo e(__('All Types')); ?></option>
                                <?php $__currentLoopData = $leaveTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(request('leave_type') == $type->id ? 'selected' : ''); ?>>
                                        <?php echo e($type->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fa-solid fa-search"></i> <?php echo e(__('Filter')); ?>

                                </button>
                                <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-secondary"><?php echo e(__('Reset')); ?></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <!-- /Filters -->

    <!-- Leave Requests Table -->
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
<?php echo app('Illuminate\Foundation\Vite')(["resources/js/datatables.js"]); ?>
<?php echo $dataTable->scripts(attributes: ['type' => 'module']); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/leaves/index.blade.php ENDPATH**/ ?>