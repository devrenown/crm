

<?php $__env->startSection('page-content'); ?>
    <div class="content container-fluid">

        <?php
            $docFields = [
                'offer_letter' => 'Offer Letter',
                'appointment_letter' => 'Appointment Letter',
                'experience_letter' => 'Experience Letter',
                'relieving_letter' => 'Relieving Letter',
                'increment_letter' => 'Increment Letter',
                'salary_slip' => 'Salary Slip',
                'bank_statement' => 'Bank Statement',
            ];
        ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Employment Details</h4>
            <a onclick="window.history.back()" class="btn btn-primary btn-sm">Back</a>
        </div>

        <form id="employmentForm" action="<?php echo e(route('onboard.employement')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="emp_detail_id" value="<?php echo e($employeeDetail->id); ?>">
            <input type="hidden" name="user_id" value="<?php echo e($employeeDetail->user_id); ?>">

            <div id="employment-wrapper">
                <?php $__empty_1 = true; $__currentLoopData = $experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="employment-item border rounded p-3 mb-3">

                        <input type="hidden" name="exp_ids[]" value="<?php echo e($exp->id); ?>">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Employment</h5>

                            <span class="delete-icon filled-delete"
                                data-route="<?php echo e(route('onboard.deleteEmployement', ['employement_id' => $exp->id, 'user_id' => $employeeDetail->user_id])); ?>" style="cursor: pointer;">
                                <i class="fa-regular fa-trash-can"></i>
                            </span>
                           
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Job Position <span class="text-danger">*</span> </label>
                                <input type="text" name="positions[]" class="form-control" value="<?php echo e($exp->position); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Name <span class="text-danger">*</span> </label>
                                <input type="text" name="companies[]" class="form-control" value="<?php echo e($exp->company); ?>" required>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'mb-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-2']); ?>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-0']); ?>
                                        <?php echo e(__('Reporting Manager / HR Name')); ?>  <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['class' => 'floating person_name','type' => 'text','name' => 'person_names[]','value' => ''.e($exp->person_name ?? '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'floating person_name','type' => 'text','name' => 'person_names[]','value' => ''.e($exp->person_name ?? '').'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'mb-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-2']); ?>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-0']); ?>
                                        <?php echo e(__('Reporting Manager / HR Contact Number')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <input type="number" name="person_contacts[]" class="form-control"
                                    value="<?php echo e($exp->person_contact); ?>" maxlength="10">
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Employee ID</label>
                                <input type="text" name="emp_ids[]" class="form-control text-uppercase" value="<?php echo e($exp->employee_id); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location <span class="text-danger">*</span> </label>
                                <input type="text" name="locations[]" class="form-control" value="<?php echo e($exp->location); ?>" required>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Start Date <span class="text-danger">*</span> </label>
                                
                                <input id="dob" name="exp_start_dates[]" value="<?php echo e(old('dob', optional($exp->start_date)->format('Y-m-d') )); ?>" type="date"
                                class="form-control" required>
                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>End Date <span class="text-danger">*</span> </label>
                                
                                <input id="dob" name="exp_end_dates[]" value="<?php echo e(old('dob', optional($exp->end_date)->format('Y-m-d') )); ?>" type="date"
                                class="form-control" required>
                               
                            </div>
                        </div>

                        <h6 class="mt-3">Documents</h6>
                        <div class="row">
                            
                            <?php if(!empty($docFields)): ?>
                                <?php $__currentLoopData = $docFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-3">
                                        <label><?php echo e($label); ?></label>
                                        <input type="file" name="<?php echo e($field); ?>s[]" class="form-control" accept="image/*,application/pdf">
                                        <input type="hidden" name="old_<?php echo e($field); ?>s[]" value="<?php echo e($exp->$field); ?>">
                                        <?php if($exp->$field): ?>
                                            <a href="<?php echo e(asset('storage/employees/work-experience/' . $exp->$field)); ?>" target="_blank"
                                                class="d-block mt-1">
                                                View <?php echo e($label); ?>

                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label>Reason for Leaving</label>
                            <textarea name="leaving_reasons[]" class="form-control"
                                rows="2"><?php echo e($exp->leaving_reason); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    
                    <div class="employment-item border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5>Employment</h5>

                            <span class="delete-icon remove-employment" type="button" href="javascript:void(0)" style="cursor: pointer;">
                                <i class="fa-regular fa-trash-can"></i>
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Job Position</label>
                                <input type="text" name="positions[]" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Name</label>
                                <input type="text" name="companies[]" class="form-control">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'mb-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-2']); ?>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-0']); ?>
                                        <?php echo e(__('Reporting Manager / HR Name')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['class' => 'floating person_name','type' => 'text','name' => 'person_name[]','value' => ''.e(old('person_name')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'floating person_name','type' => 'text','name' => 'person_name[]','value' => ''.e(old('person_name')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'mb-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-2']); ?>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-0']); ?>
                                        <?php echo e(__('Reporting Manager / HR Contact Number')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['class' => 'floating person_contact number','type' => 'number','name' => 'person_contact[]','value' => ''.e(old('person_contact')).'','maxlength' => '10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'floating person_contact number','type' => 'number','name' => 'person_contact[]','value' => ''.e(old('person_contact')).'','maxlength' => '10']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'mb-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-2']); ?>
                                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-0']); ?> <?php echo e(__('Employee ID (if assigned)')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['class' => 'floating emp_ids text-uppercase','type' => 'text','name' => 'emp_ids[]','value' => ''.e(old('emp_ids')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'floating emp_ids text-uppercase','type' => 'text','name' => 'emp_ids[]','value' => ''.e(old('emp_ids')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Location</label>
                                <input type="text" name="locations[]" class="form-control">
                            </div>

                        </div>

                        <div class="row">
                            
                            <div class="col-md-6 mb-3">
                                <label>Start Date </label>
                                <div class="cal-icon">
                                    <input name="exp_start_dates[]" value="<?php echo e(old('exp_start_dates')); ?>" type="text"
                                        class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>End Date</label>
                                <div class="cal-icon">
                                    <input name="exp_end_dates[]" value="<?php echo e(old('exp_end_dates')); ?>" type="text"
                                        class="form-control datepicker">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Contact Person</label>
                                <input type="text" name="person_contacts[]" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Employee ID</label>
                                <input type="text" name="emp_ids[]" class="form-control text-uppercase">
                            </div>
                        </div>

                        <h6 class="mt-3">Documents</h6>
                        <div class="row">
                                                     
                            <?php $__currentLoopData = $docFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 mb-3">
                                    <label><?php echo e($label); ?></label>
                                    <input type="file" name="<?php echo e($field); ?>s[]" class="form-control" accept="image/*,application/pdf">
                                    <input type="hidden" name="old_<?php echo e($field); ?>s[]" value="">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="mb-3">
                            <label>Reason for Leaving</label>
                            <textarea name="leaving_reasons[]" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="add-more">
                <a href="javascript:void(0);" id="add-employment" type="button"><i class="fa fa-plus-circle"></i>
                    <?php echo e(__('Add More')); ?></a>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Employment Details</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-scripts'); ?>
    <script>
        $(document).ready(function () {
            // Clone template for new employment
            $('#add-employment').click(function () {
                let clone = $('.employment-item:first').clone();
                clone.find('input[type=text], input[type=date], textarea').val('');
                clone.find('input[type=file]').val('');
                clone.find('input[type=hidden]').val('');
                clone.find('a').remove(); // remove old file links
                clone.find('.delete-icon')
                    .removeAttr('data-route data-title data-question href')
                    .show();
                $('#employment-wrapper').append(clone);
            });

            // Remove employment block
            $(document).on('click', '.remove-employment', function () {
                $(this).closest('.employment-item').remove();
            });

            $(document).on('click', '.filled-delete', function () {
                let isConfirm = confirm('Are you sure you want to delete?');
                if (!isConfirm) return;

                let btn = $(this);  
                let url = btn.data('route'); 

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {},
                    success: function (res) {
                        btn.closest('.employment-item').remove();
                    }
                })
            });

            // Ajax submit
            $('#employmentForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        alert(res.message + ' ✅')
                        toastr.success(res.message);
                        window.location.href = "<?php echo e(route('employees.index')); ?>";
                    },
                    error: function (xhr) {
                        toastr.error('Something went wrong');
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/employees/edit-experience.blade.php ENDPATH**/ ?>