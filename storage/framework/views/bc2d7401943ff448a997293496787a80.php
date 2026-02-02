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
            
               <?php $__env->slot('title', null, []); ?> <?php echo e(__("Employee Profile")); ?> <?php $__env->endSlot(); ?>
            
            <div class="d-flex justify-content-between align-items-center w-100">
              <ul class="breadcrumb">
                  <li class="breadcrumb-item">
                      <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                  </li>
                  <li class="breadcrumb-item active">
                      <?php echo e(__('Profile')); ?>

                  </li>
              </ul>

              <button onclick="history.back()" class="btn btn-sm btn-primary">Back</button>
            </div>
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
                      <a href="#"
                        ><img
                        src="<?php echo e(!empty($user->avatar) ? asset('storage/users/' . $user->avatar) : Vite::asset('resources/assets/img/user.jpg')); ?>"
                        alt="User Image"
                      /></a>
                    </div>
                  </div>
                  <div class="profile-basic">
                    <div class="row">

                      <div class="col-md-5">
                        <div class="profile-info-left">
                          <h3 class="user-name m-t-0 mb-3"><?php echo e($user->fullname); ?></h3>
                          <?php if(!empty($user->employeeDetail->department_id)): ?>
                          <h5 class="mb-2"><?php echo e(__('Department')); ?> : <span class="text-muted"><?php echo e($user->employeeDetail->department->name ?? ''); ?></span> </h5>
                          <?php endif; ?>
                          <?php if(!empty($user->employeeDetail->designation_id)): ?>
                          <p class="mb-2"><?php echo e(__('Designation')); ?> : <span class="text-muted"><?php echo e($user->employeeDetail->designation->name ?? ''); ?></span> </p>
                          <?php endif; ?>
                          <?php if(!empty($user->employeeDetail->emp_id)): ?>
                          <p class="mb-2"><?php echo e(__('Employee ID')); ?> : <span class="text-muted"><?php echo e($user->employeeDetail->emp_id ?? ''); ?></span> </p>
                          <?php endif; ?>
                          <?php if(!empty($user->employeeDetail->date_joined)): ?>
                          <p class="mb-0">
                            <?php echo e(__('Date of Join')); ?> : <span class="text-muted"><?php echo e(format_date($employee->date_joined)); ?></span> 
                          </p>
                          <?php endif; ?>

                          <div class="staff-msg">
                            <a class="btn btn-primary btn-sm" href="#"
                              ><?php echo e(__('Send Message')); ?></a
                            >
                            <?php if(@$user->onboardingInvitation->is_sent == 1 && $user->is_onboarding_complete == 0): ?>
                            <button class="btn btn-primary btn-sm" id="apr-onboarding">Approve Onboarding</button>
                            <?php endif; ?>
                          </div>
                          <br>
                        </div>
                      </div>

                      <div class="col-md-7">
                        <ul class="personal-info">
                          <?php if(!empty($user->phone)): ?>
                              <li>
                                  <div class="title"><?php echo e(__('Phone')); ?>:</div>
                                  <div class="text"><a href="#"><?php echo e($user->phoneNumber); ?></a></div>
                              </li>
                          <?php endif; ?>
                          <?php if(!empty($user->email)): ?>
                              <li>
                                  <div class="title"><?php echo e(__('Email')); ?>:</div>
                                  <div class="text"><?php echo e($user->email); ?></div>
                              </li>
                          <?php endif; ?>

                          <?php if(!empty($employee->dob)): ?>
                              <li>
                                  <div class="title"><?php echo e(__('Date Of Birth')); ?>:</div>
                                  <div class="text"><?php echo e(format_date($employee->dob)); ?></div>
                              </li>
                          <?php endif; ?>

                          <?php if(!empty($user->gender)): ?>
                              <li>
                                  <div class="title"><?php echo e(__('Gender')); ?>:</div>
                                  <div class="text"><?php echo e($user->gender == 1 ? 'Male' : ($user->gender == 2 ? 'Female' : 'Other')); ?></div>
                              </li>
                          <?php endif; ?>

                          <?php if($user->is_onboarding_complete == 0): ?>

                            <?php if(@$user->onboardingInvitation->is_sent == 1): ?>
                            <button class="btn btn-primary btn-sm" id="send-invite">Reinvite for Onboarding</button>
                            <?php else: ?>
                            <button class="btn btn-primary btn-sm" id="send-invite">Invite for Onboarding</button>
                            <?php endif; ?>
                          <?php endif; ?>
                        </ul>
                      </div>

                      <?php if(!empty($user->reportingManager || $user->subReportingManager )): ?>
                        <div class="col-md-10">
                            <div class="card py-3 px-4">

                              <div class="row align-items-center mb-2">

                                <div class="col-md-6">
                                  
                                  <div class="">
                                    <?php echo e(__('Reporting')); ?> :
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
                                    <?php echo e(__('Co Reporting')); ?> :
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
                  </div>
                  <div class="pro-edit">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                    <a href="javascript:void(0)" data-url="<?php echo e(route('employees.edit', ['employee' => \Crypt::encrypt($user->id)])); ?>" data-ajax-modal="true" 
                      data-title="Edit Employee" data-size="lg" data-bs-toggle="tooltip" data-bs-title="<?php echo e(__('Edit profile')); ?>"><i class="fa-solid fa-pencil"></i
                    ></a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card tab-box">
          <div class="row user-tabs">
            <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
              <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item">
                  <a
                    href="#emp_profile"
                    data-bs-toggle="tab"
                    class="nav-link active"
                    ><?php echo e(__('Profile')); ?></a>
                </li>
                <?php if (\Illuminate\Support\Facades\Blade::check('activeRoleIn', ['Admin', 'Hr'])): ?>
                <li class="nav-item">
                  <a
                    href="#bank_statutory"
                    data-bs-toggle="tab"
                    class="nav-link"
                    ><?php echo e(__('Bank & Statutory')); ?>

                  </a>
                </li>
                <?php endif; ?>
                <?php if(!empty($user->assets) && ($user->assets->count() > 0)): ?>
                <li class="nav-item">
                  <a
                    href="#emp_assets"
                    data-bs-toggle="tab"
                    class="nav-link"
                    ><?php echo e(__('Assets')); ?></a>
                </li>
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-assignedRole')): ?>
                  <li>
                    <a href="#emp_roles" data-bs-toggle="tab" class="nav-link"><?php echo e(__('Roles')); ?></a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>

        <div class="tab-content">
          <!-- Profile Info Tab -->
          <div
            id="emp_profile"
            class="pro-overview tab-pane fade show active">
            <div class="row">
                
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">

                      <?php echo e(__('Personal Informations')); ?>

                      
                      
                      <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                      <a href="<?php echo e(route('employee.personal-info', $employee->id)); ?>" class="edit-icon me-1">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      <?php endif; ?>
                      
                    </h3>
                    <ul class="personal-info">

                      <?php if(!empty($user->company)): ?>
                        <li>
                          <div class="title"><?php echo e(__('Company')); ?></div>
                          <div class="text"><?php echo e(@$user->companyDetail->name); ?></div>
                        </li>
                      <?php endif; ?>

                      <?php if(isset($employee->designation_id) && isset($employee->designation)): ?>
                        <li>
                          <div class="title"><?php echo e(__('Designation')); ?></div>
                          <div class="text"><?php echo e(@$employee->designation->name); ?> <?php echo e($employee->department_id ? '(' . @$employee->department->name . ')' : 'N/A'); ?></div>
                        </li>
                      <?php endif; ?>

                      <?php if(!empty($employee->passport_no)): ?>
                        <li>
                          <div class="title"><?php echo e(__('Passport No.')); ?></div>
                          <div class="text"><?php echo e($employee->passport_no); ?></div>
                        </li>
                      <?php endif; ?>
                      <?php if(!empty($employee->passport_expiry_date)): ?>
                      <li>
                        <div class="title"><?php echo e(__('Passport Exp Date.')); ?></div>
                        <div class="text"><?php echo e(format_date($employee->passport_expiry_date)); ?></div>
                      </li>
                      <?php endif; ?>
                      <?php if(!empty($employee->passport_tel)): ?>
                      <li>
                        <div class="title"><?php echo e(__('Tel')); ?></div>
                        <div class="text"><?php echo e($employee->passport_tel); ?></a></div>
                      </li>
                      <?php endif; ?>
                      <?php if(!empty($employee->nationality)): ?>
                      <li>
                        <div class="title"><?php echo e(__('Nationality')); ?></div>
                        <div class="text"><?php echo e($employee->nationality); ?></div>
                      </li>
                      <?php endif; ?>
                      <?php if(!empty($employee->religion)): ?>
                      <li>
                        <div class="title"><?php echo e(__('Religion')); ?></div>
                        <div class="text"><?php echo e($employee->religion); ?></div>
                      </li>
                      <?php endif; ?>
                      <?php if(!empty($employee->marital_status)): ?>
                      <li>
                        <div class="title"><?php echo e(__('Marital status')); ?></div>
                        <div class="text"><?php echo e($employee->marital_status); ?></div>
                      </li>
                      <?php endif; ?>
                      <?php if(!empty($employee->spouse_occupation)): ?>
                      <li>
                        <div class="title"><?php echo e(__('Employment of spouse')); ?></div>
                        <div class="text"><?php echo e($employee->spouse_occupation); ?></div>
                      </li>
                      <?php endif; ?>
                      <?php if(!empty($employee->no_of_children)): ?>
                      <li>
                        <div class="title"><?php echo e(__('No. of children')); ?></div>
                        <div class="text"><?php echo e($employee->no_of_children); ?></div>
                      </li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      <?php echo e(__('Identification')); ?>

                      
                      <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                      <a href="javascript:void(0)" data-url="<?php echo e(route('employee.identity', $employee->id)); ?>" class="edit-icon me-1" data-title="<?php echo e(__('Employee Identity')); ?>" data-ajax-modal="true" data-size="lg" data-bs-toggle="tooltip" data-bs-title="Identity">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      <?php endif; ?>
                      
                    </h3>
                    
                    <div class="row">
                        <h5>Current Address:</h5>
                        
                        <div class="col-6">City</div>
                        <div class="col-6 muted"><?php echo e($employee->current_city ?? ''); ?></div>
                    </div>
                    
                    <div class="row">
                        <h5>Permanent Address:</h5>
                        
                        <div class="col-6">City</div>
                        <div class="col-6 muted"><?php echo e($employee->permanent_city ?? ''); ?></div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
                
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      <?php echo e(__('Education Informations')); ?>


                      <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                      <a
                      href="javascript:void(0)" data-url="<?php echo e(route('employee.education', $employee->id)); ?>"
                        class="edit-icon" data-title="<?php echo e(__('Education Information')); ?>"
                        data-ajax-modal="true" data-size="lg"
                        data-bs-toggle="tooltip" data-bs-title="Education"
                        ><i class="fa-solid fa-pencil"></i>
                      </a>
                      <?php endif; ?>
                    </h3>
                    <div class="experience-box">
                      <ul class="experience-list">
                        <?php if(!empty($employee->education) && $employee->education->count() > 0): ?>
                          <?php $__currentLoopData = $employee->education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $education): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <li>
                            <div class="experience-user">
                              <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                              <div class="timeline-content">

                                <div class="text-black fw-bold"><?php echo e($education->course); ?> <span class="text-muted small"> ( <?php echo e($education->institution); ?> ) </span></div>
                                <span class="time"><?php echo e($education->start_date); ?> - <?php echo e($education->end_date); ?></span>
                                <?php if(!empty($education->file)): ?>
                                    <a href="<?php echo e(uploadedAsset($education->file,'employees/education')); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(__('View File')); ?></a>
                                <?php endif; ?>
                              </div>
                            </div>
                          </li>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      <?php echo e(__('Work Experience')); ?>

                      

                      <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                      <a
                      href="<?php echo e(route('employee.experience', $employee->id)); ?>" 
                          class="edit-icon"><i class="fa-solid fa-pencil"></i>
                      </a>
                      <?php endif; ?>
                    </h3>
                    <div class="experience-box">
                      <ul class="experience-list">
                          <?php if(!empty($employee->workExperience)): ?>
                              <?php $__currentLoopData = $employee->workExperience; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $experience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <li>
                                <div class="experience-user">
                                  <div class="before-circle"></div>
                                </div>
                                <div class="experience-content">
                                  <div class="timeline-content">
                                    <span class="name"><?php echo e($experience->position .__(" At "). $experience->company); ?></span>
                                    <span class="time"
                                      ><?php echo e(format_date($experience->start_date)); ?> - <?php echo e(format_date($experience->end_date)); ?> (<?php echo e($experience->dateDifference); ?>) </span>
                                      <?php if(!empty($experience->file)): ?>
                                          <a href="<?php echo e(uploadedAsset($experience->file,'employees/work-experience')); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(__('View File')); ?></a>
                                      <?php endif; ?>
                                  </div>
                                </div>
                              </li>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      <?php echo e(__('Family Informations')); ?>

                      <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                      <a href="javascript:void(0)" data-url="<?php echo e(route('family-information.create', ['user' => $user->id])); ?>"
                          class="edit-icon" data-title="<?php echo e(__('Add Family Information')); ?>"
                          data-ajax-modal="true" data-size="lg"
                          data-bs-toggle="tooltip" data-bs-title="Add Family Member"
                          >
                          <i class="fa-solid fa-plus"></i>
                      </a>
                      <?php endif; ?>
                    </h3>
                    <div class="table-responsive">
                      <table class="table table-nowrap">
                        <thead>
                          <tr>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Relationship')); ?></th>
                            <th><?php echo e(__('Date of Birth')); ?></th>
                            <th><?php echo e(__('Phone')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if($user->has('family')): ?>
                              <?php $__currentLoopData = $user->family; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                  <?php if(!empty($member->picture)): ?>
                                  <td>
                                      <?php echo Spatie\Menu\Laravel\Html::userAvatar($member->name, !empty($member->picture) ? uploadedAsset($member->picture,'family-members'): Vite::asset('resources/assets/img/user.jpg')); ?>

                                  </td>
                                  <?php else: ?>
                                  <td><?php echo e($member->name); ?></td>
                                  <?php endif; ?>
                                  <td><?php echo e($member->relationship); ?></td>
                                  <td><?php echo e(format_date($member->dob)); ?></td>
                                  <td><?php echo e($member->phone); ?></td>
                                  <?php if (isset($component)) { $__componentOriginal3cb096f2e62c7df2672a776d39e07de4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3cb096f2e62c7df2672a776d39e07de4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-action','data' => ['class' => 'position-absolute']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'position-absolute']); ?>
                                      <a class="dropdown-item" href="javascript:void(0)" data-url="<?php echo e(route('family-information.edit', $member->id)); ?>" data-ajax-modal="true" 
                                          data-title="<?php echo e(__('Edit Family Member')); ?>" data-size="lg" data-bs-toggle="tooltip" data-bs-title="<?php echo e(__("Edit Family Member Information")); ?>">
                                          <i class="fa-solid fa-pencil m-r-5"></i>
                                          <?php echo e(__('Edit')); ?>

                                      </a>
                                      <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('family-information.destroy', $member->id)); ?>"
                                          data-title="<?php echo e(__('Delete User')); ?>" data-bs-toggle="tooltip" data-bs-title="<?php echo e(__('Delete Family Member')); ?>" data-question="<?php echo e(__('Are you sure you want to delete?')); ?>"
                                          href="javascript:void(0)" data-bs-toggle="tootip" data-bs-title="<?php echo e(__('Delete Family Member')); ?>">
                                          <i class="fa-regular fa-trash-can m-r-5"></i>
                                          <?php echo e(__('Delete')); ?>

                                      </a>
                                   <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3cb096f2e62c7df2672a776d39e07de4)): ?>
<?php $attributes = $__attributesOriginal3cb096f2e62c7df2672a776d39e07de4; ?>
<?php unset($__attributesOriginal3cb096f2e62c7df2672a776d39e07de4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3cb096f2e62c7df2672a776d39e07de4)): ?>
<?php $component = $__componentOriginal3cb096f2e62c7df2672a776d39e07de4; ?>
<?php unset($__componentOriginal3cb096f2e62c7df2672a776d39e07de4); ?>
<?php endif; ?>

                                </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                  <div class="card-body">
                    <h3 class="card-title">
                      <?php echo e(__('Emergency Contact')); ?>

                      <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-employee')): ?>
                      <a href="javascript:void(0)" data-url="<?php echo e(route('employee.emergency-contacts', $employee->id)); ?>"
                          class="edit-icon" data-title="<?php echo e(__('Emergency Contacts')); ?>"
                          data-ajax-modal="true" data-size="lg"
                          >
                          <i class="fa-solid fa-pencil"></i>
                      </a>
                      <?php endif; ?>
                    </h3>
                    <h5 class="section-title"><?php echo e(__('Primary')); ?></h5>
                    <?php
                        $primary_contact = $employee->emergency_contacts['primary'] ?? null;
                        $secondary_contact = $employee->emergency_contacts['secondary'] ?? null;
                    ?>
                    <?php if(!empty($primary_contact)): ?>
                    <ul class="personal-info">
                      <li>
                        <div class="title"><?php echo e(__('Name')); ?></div>
                        <div class="text"><?php echo e($primary_contact['name']); ?></div>
                      </li>
                      <li>
                        <div class="title"><?php echo e(__('Relationship')); ?></div>
                        <div class="text"><?php echo e($primary_contact['relationship']); ?></div>
                      </li>
                      <li>
                        <div class="title"><?php echo e(__('Phone')); ?></div>
                        <div class="text"><?php echo e($primary_contact['phone']); ?></div>
                      </li>
                      <li>
                        <div class="title"><?php echo e(__('Address')); ?></div>
                        <div class="text"><?php echo e($primary_contact['address']); ?></div>
                      </li>
                    </ul>
                    <?php endif; ?>
                    <?php if(!empty($secondary_contact)): ?>
                    <hr />
                    <h5 class="section-title"><?php echo e(__('Secondary')); ?></h5>
                    <ul class="personal-info">
                      <li>
                        <div class="title">Name</div>
                        <div class="text"><?php echo e($secondary_contact['name']); ?></div>
                      </li>
                      <li>
                        <div class="title">Relationship</div>
                        <div class="text"><?php echo e($secondary_contact['relationship']); ?></div>
                      </li>
                      <li>
                        <div class="title">Phone</div>
                        <div class="text"><?php echo e($secondary_contact['phone']); ?></div>
                      </li>
                    </ul>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /Profile Info Tab -->

          <?php if (\Illuminate\Support\Facades\Blade::check('activeRoleIn', ['Admin', 'Hr'])): ?>
          <!-- Bank Statutory Tab -->
          <div class="tab-pane fade" id="bank_statutory">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title"><?php echo e(__('Basic Salary Information')); ?></h3>
                <form action="<?php echo e(route('employee.salary-setting', $employee->id)); ?>" method="post">
                  <?php echo csrf_field(); ?>
                  <div class="row">
                    <input type="hidden" name="salary_detail_id" value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->id : ''); ?>">
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"
                          ><?php echo e(__('Salary basis')); ?>

                          <span class="text-danger">*</span></label
                        >
                        <select class="form-control" name="basis">
                          <option value=""><?php echo e(__('Select salary basis type')); ?></option>
                          <?php $__currentLoopData = \App\Enums\Payroll\SalaryType::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option <?php echo e((!empty($employee->salaryDetails) && $employee->salaryDetails->basis === $item) ? 'selected': ''); ?> value="<?php echo e($item->value); ?>"><?php echo e($item->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"
                          ><?php echo e(__('Salary amount')); ?>

                        </label>
                        <div class="input-group">
                          <span class="input-group-text"><?php echo e(LocaleSettings('currency_symbol')); ?></span>
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Type your salary amount"
                            name="base_salary"
                            value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->base_salary : 0.00); ?>"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Payment type')); ?></label>
                        <select class="form-control" name="payment_method">
                          <option value=""><?php echo e(__('Select payment type')); ?></option>
                          <?php $__currentLoopData = \App\Enums\Payroll\PaymentMethod::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php echo e(!empty($employee->salaryDetails) && $employee->salaryDetails->payment_method === $item ? 'selected': ''); ?> value="<?php echo e($item->value); ?>"><?php echo e($item->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <?php if(!empty(SalarySettings('enable_provident_fund'))): ?>
                  <hr />
                  <h3 class="card-title"><?php echo e(__('PF Information')); ?></h3>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('PF contribution')); ?></label>
                        <select class="form-control" name="pf_contribution">
                          <option value=""><?php echo e(__('Select To Enable')); ?></option>
                          <option <?php echo e((!empty($employee->salaryDetails) && $employee->salaryDetails->pf_contribution == '1') ? 'selected': ''); ?> value="1"><?php echo e(__('Yes')); ?></option>
                          <option <?php echo e((!empty($employee->salaryDetails) && $employee->salaryDetails->pf_contribution == '0') ? 'selected': ''); ?> value="0"><?php echo e(__('No')); ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('PF No.')); ?></label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="pf_number"
                          value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->pf_number: ''); ?>"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Additional Rate')); ?></label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="additional_pf_rate"
                          value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->additional_pf: ''); ?>"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Total rate')); ?></label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="total_pf_rate"
                          value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->total_pf : ''); ?>"
                        />
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>
                  <?php if(!empty(SalarySettings('enable_esi_fund'))): ?>  
                  <hr />
                  <h3 class="card-title"><?php echo e(__('ESI Information')); ?></h3>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Enable ESI contribution')); ?></label>
                        <select class="form-control" name="esi_contribution">
                          <option value=""><?php echo e(__('Select To Enable')); ?></option>
                          <option <?php echo e(!empty($employee->salaryDetails) && $employee->salaryDetails->esi_contribution == '1' ? 'selected': ''); ?> value="1"><?php echo e(__('Yes')); ?></option>
                          <option <?php echo e(!empty($employee->salaryDetails) && $employee->salaryDetails->esi_contribution == '0' ? 'selected': ''); ?> value="0"><?php echo e(__('No')); ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('ESI No.')); ?></label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="esi_number"
                          value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->esi_number: ''); ?>"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Additional Rate')); ?></label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="additional_esi_rate"
                          value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->additional_esi: ''); ?>"
                        />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"><?php echo e(__('Total rate')); ?></label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="N/A"
                          name="total_esi_rate"
                          value="<?php echo e(!empty($employee->salaryDetails) ? $employee->salaryDetails->total_additional_esi_rate: ''); ?>"
                        />
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>

                  <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit">
                      <?php echo e(__('Save')); ?>

                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /Bank Statutory Tab -->
          <?php endif; ?>

          <!-- Assets -->
          <div class="tab-pane fade" id="emp_assets">
              <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('employee-asset', ['user' => $user]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1931421818-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
          </div>
          <!-- /Assets -->

          <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-assignedRole')): ?>
          <!-- Bank Statutory Tab -->
          <div class="tab-pane fade" id="emp_roles">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title"><?php echo e(__('Assign Roles')); ?></h3>
                <form action="<?php echo e(route('employees.assignRoles')); ?>" method="post" id="assignRoleForm">
                  <?php echo csrf_field(); ?>
                  <div class="row">
                    <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">

                    <div class="col-sm-4">
                      <div class="input-block mb-3">
                        <label class="col-form-label"
                          ><?php echo e(__('Roles')); ?>

                          <span class="text-danger">*</span></label
                        >
                        <select class="form-control select" name="roles[]" data-placeholder="<?php echo e(__('Select Roles')); ?>" multiple required>
                          <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option <?php echo e($user->roles->contains('id', $role->id) ? 'selected': ''); ?> value="<?php echo e($role->name); ?>">
                                <?php echo e($role->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>
                    </div>

                  </div>

                  <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-assignedRole')): ?>
                  <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit">
                      <?php echo e(__('Assign Roles')); ?>

                    </button>
                  </div>
                  <?php endif; ?>

                </form>
              </div>
            </div>
          </div>
          <!-- /Bank Statutory Tab -->
          <?php endif; ?>

        </div>

    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-scripts'); ?>

<script>
  $('#apr-onboarding').on('click', function() {
    let isConfirm = confirm('Are you sure you want to approve this ?');
    if(!isConfirm) return;

    $.ajax({
      url: "<?php echo e(route('approve-onboarding')); ?>",
      type: 'POST',
      data: {
        _token: '<?php echo e(csrf_token()); ?>',
        user_id: '<?php echo e($user->id); ?>'
      },
      success: function(res) {
        Toastify({
            text: res.msg,
            className: 'success',
        }).showToast();

        window.location.reload();
      },
      error: function(xhr) {
        console.log(xhr.responseText())
      }
    })

  });

  $('#send-invite').on('click', function() {
    let isConfirm = confirm('Are you sure you want to Send Invite ?');
    if(!isConfirm) return;

    $.ajax({
      url: "<?php echo e(route('send-onboarding-invitation')); ?>",
      type: 'POST',
      data: {
        _token: '<?php echo e(csrf_token()); ?>',
        user_id: '<?php echo e($user->id); ?>',
        invitation_id: "<?php echo e(@$user->onboardingInvitation->id ?? ''); ?>"
      },
      success: function(res) {
        Toastify({
            text: res.msg,
            className: 'success',
        }).showToast();

        window.location.reload();
      },
      error: function(xhr) {
        console.log(xhr.responseText())
      }
    })

  });

  $(document).on('submit', '#assignRoleForm', function (e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    $.ajax({
        url: form.action,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,

        success: function (res) {
            if (res.success) {
                alert(res.message);
                window.location.href = window.location.pathname + '#emp_roles';
            } else {
                alert('Something went wrong!');
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
  });

</script>

<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/employees/show.blade.php ENDPATH**/ ?>