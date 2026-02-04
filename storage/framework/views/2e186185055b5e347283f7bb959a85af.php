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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Payroll Items')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Payroll')); ?>

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

        <!-- Page Tab -->
        <div class="page-menu">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab_additions">Additions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_deductions">Deductions</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Tab -->
        
        <!-- Tab Content -->
        <div class="tab-content">
        
            <!-- Additions Tab -->
            <div class="tab-pane show active" id="tab_additions">
            
                <!-- Add Addition Button -->
                <div class="text-end mb-4 clearfix">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-PayrollAllowance')): ?>
                    <button class="btn btn-primary add-btn" type="button" 
                    data-ajax-modal="true" data-url="<?php echo e(route('allowances.create')); ?>"
                    data-size="md" data-title="<?php echo e(__('Add Allowance')); ?>"><i class="fa-solid fa-plus"></i> <?php echo e(__('Add Allowance')); ?></button>
                    <?php endif; ?>
                </div>
                <!-- /Add Addition Button -->

                <!-- Payroll Additions Table -->
                <div class="payroll-table card">
                    <div class="table-responsive">
                        <table class="table table-hover table-radius">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-PayrollAllowance', 'delete-PayrollAllowance'])): ?>
                                    <th class="text-end"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($allowances)): ?>
                                    <?php $__currentLoopData = $allowances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allowance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th><?php echo e($allowance->name); ?></th>
                                        <td><?php echo e(LocaleSettings('currency_symbol').' '. $allowance->amount); ?></td>
                                        <?php echo $__env->make('pages.payroll.allowances.actions', ['id' => $allowance->id], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </tr>                                    
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /Payroll Additions Table -->
                
            </div>
            <!-- Additions Tab -->

            
            <!-- Deductions Tab -->
            <div class="tab-pane" id="tab_deductions">
            
                <!-- Add Deductions Button -->
                <div class="text-end mb-4 clearfix">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-PayrollDeduction')): ?>
                    <button class="btn btn-primary add-btn" type="button" 
                    data-ajax-modal="true" data-url="<?php echo e(route('deductions.create')); ?>"
                    data-size="md" data-title="<?php echo e(__('Add Deduction')); ?>"><i class="fa-solid fa-plus"></i> <?php echo e(__('Add Deduction')); ?></button>
                    <?php endif; ?>
                </div>
                <!-- /Add Deductions Button -->

                <!-- Payroll Deduction Table -->
                <div class="payroll-table card">
                    <div class="table-responsive">
                        <table class="table table-hover table-radius">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-PayrollDeduction', 'delete-PayrollDeduction'])): ?>
                                    <th class="text-end"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($deductions)): ?>
                                    <?php $__currentLoopData = $deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deduction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th><?php echo e($deduction->name); ?></th>
                                        <td><?php echo e(LocaleSettings('currency_symbol').' '. $deduction->amount); ?></td>
                                        <?php echo $__env->make('pages.payroll.deductions.actions', ['id' => $deduction->id], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </tr>                                    
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /Payroll Deduction Table -->
                
            </div>
            <!-- /Deductions Tab -->
            
        </div>
        <!-- Tab Content -->
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('page-scripts'); ?>

<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/payroll/items.blade.php ENDPATH**/ ?>