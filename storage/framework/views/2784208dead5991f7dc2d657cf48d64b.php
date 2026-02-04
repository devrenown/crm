<div class="modal-body">
        
        <div class="row">
                
                <form action="<?php echo e(route('tenant.update')); ?>" method="POST" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <input type="hidden" name="tenant_id" value="<?php echo e($tenant->id); ?>">
                    <input type="hidden" name="user_id" value="<?php echo e($tenant->adminUser->id); ?>">

                    <div class="row">
                        
                        <div class="col-lg-6 mb-3">

                            <label for="organization_name" class="form-label"><?php echo e(__('Organization Name')); ?></label>
                            <input type="text" class="form-control required" id="organization_name" name="organization_name" tabindex="1"
                            value="<?php echo e($tenant->name); ?>" placeholder="Enter Organization Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                          <label for="organization_size" class="form-label">Organization Size</label>
                          <select name="organization_size" id="organization_size" class="form-select form-control required">
                            <option value="" selected disabled>Select Organization Size</option>

                            <option value="1-50" <?php echo e($tenant->size == '1-50' ? 'selected' : ''); ?>>1 - 50</option>

                            <option value="50-100" <?php echo e($tenant->size == '50-100' ? 'selected' : ''); ?>>50 - 100</option>

                            <option value="100-200" <?php echo e($tenant->size == '100-200' ? 'selected' : ''); ?>>100 - 200</option>

                            <option value="200+" <?php echo e($tenant->size == '200+' ? 'selected' : ''); ?>>200+</option>

                          </select>

                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="f_name" class="form-label"><?php echo e(__('First Name')); ?></label>

                            <input type="text" class="form-control required" id="f_name" name="f_name" tabindex="1" value="<?php echo e(@$tenant->adminUser->firstname); ?>" placeholder="Enter First Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="l_name" class="form-label"><?php echo e(__('Last Name')); ?></label>

                            <input type="text" class="form-control required" id="l_name" name="l_name" tabindex="1" value="<?php echo e(@$tenant->adminUser->lastname); ?>" placeholder="Enter First Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="email" class="form-label"><?php echo e(__('Email Address')); ?></label>
                            <input type="email" class="form-control required" id="email" name="email" tabindex="1"
                            value="<?php echo e(@$tenant->adminUser->email); ?>" placeholder="Enter email" autocomplete="off">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="phone" class="form-label"><?php echo e(__('Contact Number')); ?></label>
                            <input type="number" class="form-control required" id="phone" name="phone" tabindex="1" value="<?php echo e(@$tenant->adminUser->phone); ?>" placeholder="Enter contact" maxlength="10">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="status" class="form-label"><?php echo e(__('Status')); ?></label>
                            <select class="form-control form-select" name="status" id="status"> 
                                <option value="active" <?php echo e($tenant->status === \App\Enums\TenantStatus::ACTIVE ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e($tenant->status === \App\Enums\TenantStatus::INACTIVE ? 'selected' : ''); ?>>Inactive</option>
                                <option value="suspended" <?php echo e($tenant->status === \App\Enums\TenantStatus::SUSPENDED ? 'selected' : ''); ?>>Suspended</option>
                            </select>
                        </div>

                        

                    </div>

                    <div class="submit-section mb-3">
                        <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['class' => 'btn btn-primary submit-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'btn btn-primary submit-btn']); ?><?php echo e(__('Update')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a)): ?>
<?php $attributes = $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a; ?>
<?php unset($__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a31ff0802d1df0c26bb607f30439b3a)): ?>
<?php $component = $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a; ?>
<?php unset($__componentOriginal8a31ff0802d1df0c26bb607f30439b3a); ?>
<?php endif; ?>
                    </div>

                </form>

        </div>
</div>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/tenant/edit.blade.php ENDPATH**/ ?>