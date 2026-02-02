<?php $__env->startPush('page-styles'); ?>
    <style>
      .profile-info-left {
        border: none !important;
      }
      .text-primary {
        color: #307AFB !important;
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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Profile')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Profile')); ?>

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
        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img
                                    src="<?php echo e($user->avatar ? asset('storage/users/' . $user->avatar) : asset('images/user.jpg')); ?>"
                                    alt="User Image" style="object-fit: cover;"></a>

                                    
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">

                                    <div class="col-md-5 mb-3">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-3"><?php echo e($user->fullname); ?></h3>
                                            <?php if(!empty($employee->department_id)): ?>
                                              <h5 class="mb-2"><?php echo e(__('Department')); ?> : <span class="text-muted"><?php echo e($employee->department->name ?? ''); ?></span> </h5>
                                            <?php endif; ?>
                                            <?php if(!empty($employee->designation_id)): ?>
                                              <p class="mb-2"><?php echo e(__('Designation')); ?> : <span class="text-muted"><?php echo e($employee->designation->name ?? ''); ?></span> </p>
                                            <?php endif; ?>
                                            <?php if(!empty($employee->emp_id)): ?>
                                              <p class="mb-2"><?php echo e(__('Employee ID')); ?> : <span class="text-muted"><?php echo e($employee->emp_id ?? ''); ?></span> </p>
                                            <?php endif; ?>
                                            <?php if(!empty($employee->date_joined)): ?>
                                              <p class="mb-0">
                                                <?php echo e(__('Date of Join')); ?> : <span class="text-muted"><?php echo e(format_date($employee->date_joined)); ?></span> 
                                              </p>
                                            <?php endif; ?>

                                            <div class="d-flex gap-2 justify-content-center justify-content-lg-start">
                                                <div class="staff-msg">
                                                    <a class="btn btn-primary btn-sm" href="apps/chat"><?php echo e(__('Send Message')); ?></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <?php if(!empty($user->phone)): ?>
                                                <li>
                                                    <div class="title"><?php echo e(__('Phone')); ?>:</div>
                                                    <div class="text"><a href="#"><?php echo e($user->phoneNumber); ?></a>
                                                    </div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(!empty($user->email)): ?>
                                                <li>
                                                    <div class="title"><?php echo e(__('Email')); ?>:</div>
                                                    <div class="text"><a href=""><?php echo e($user->email); ?></a></div>
                                                </li>
                                            <?php endif; ?>

                                            

                                            <?php if(!empty($user->gender)): ?>
                                                <li>
                                                    <div class="title"><?php echo e(__('Gender')); ?>:</div>
                                                    <div class="text"><a href=""><?php echo e($user->gender == 1 ? 'Male' : ($user->gender == 2 ? 'Female' : 'Other')); ?></a></div>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                </div>

                                <?php if(!empty($user->reportingManager || $user->subReportingManager )): ?>
                                    <div class="col-md-10">
                                        <div class="card py-3 px-4">

                                          <div class="row align-items-center mb-2">

                                            <div class="col-md-6">
                                              
                                              <div class="">
                                                <?php echo e(__('Reporting Manager')); ?> :
                                              </div>
                                              
                                            </div>

                                            <div class="col-md-6">
                                              <div class="bg-light py-2 px-4 mb-0">
                                                <p class="mb-0"><i class="fa-solid fa-user text-primary me-2 fs-5"></i>
                                                <?php echo e($user->reportingManager->fullname ?? 'N/A'); ?></p>
                                              </div>
                                            </div>

                                          </div>

                                          <div class="row align-items-center">
                                            <div class="col-md-6">
                                              <div class="">
                                                <?php echo e(__('Sub Reporting Manager')); ?> :
                                              </div>
                                            </div>

                                            <div class="col-md-6">
                                              <div class="bg-light py-2 px-4 mb-0">
                                                <p class="mb-0"><i class="fa-solid fa-user-group text-primary me-2 fs-5"></i>
                                                <?php echo e($user->subReportingManager->fullname ?? 'N/A'); ?></p>
                                              </div>
                                            </div>

                                          </div>

                                      </div>
                                    </div>

                                  <?php else: ?>
                                    <div class="col-md-10">
                                        <div class="card py-3 px-4 text-center">
                                          <p class="fs-5 fw-bold">No Reporting Manager Assigned !</p>
                                        </div>
                                    </div>
                                  <?php endif; ?>

                            </div>

                            <div class="pro-edit">
                                <a data-ajax-modal="true" data-title="Profile Information" data-size="lg" class="edit-icon"
                                    href="javascript:void(0)" data-url="<?php echo e(route('profile.edit')); ?>"><i
                                        class="fa-solid fa-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3 p-4 shadow-sm">

            <h4 class="mb-4">Change Password</h4>

            <form action="<?php echo e(route('profile.update-password')); ?>" method="POST" id="update-password">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
                <div class="row">
                    <div class="mb-3 col-lg-6">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" required>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="new_password_confirmation" placeholder="Confirm new password" required>
                    </div>

                </div>

                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-primary btn-sm">Update Password</button>
                </div>

            </form>
        </div>


    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/profile.blade.php ENDPATH**/ ?>