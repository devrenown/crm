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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Attendances')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Attendance List')); ?>

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

        <!-- Search Filter -->
        <form action="" method="get">
            <div x-data="{employee: '<?php echo e(request()->employee); ?>', month: '<?php echo e(request()->month); ?>',year: '<?php echo e(request()->year); ?>'}" class="row filter-row">
                <div class="col-sm-6 col-md-3">  
                    <div class="input-block mb-3 form-focus">
                        <input type="text" name="employee" x-model="employee" class="form-control floating">
                        <label class="focus-label"><?php echo e(__('Employee Name')); ?></label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="input-block mb-3 form-focus select-focus">
                        <select name="month" x-model="month" class="select floating"> 
                            <option value=""> - </option>
                            <option value="01"><?php echo e(__('Jan')); ?></option>
                            <option value="02"><?php echo e(__('Feb')); ?></option>
                            <option value="03"><?php echo e(__('Mar')); ?></option>
                            <option value="04"><?php echo e(__('Apr')); ?></option>
                            <option value="05"><?php echo e(__('May')); ?></option>
                            <option value="06"><?php echo e(__('Jun')); ?></option>
                            <option value="07"><?php echo e(__('Jul')); ?></option>
                            <option value="08"><?php echo e(__('Aug')); ?></option>
                            <option value="09"><?php echo e(__('Sep')); ?></option>
                            <option value="10"><?php echo e(__('Oct')); ?></option>
                            <option value="11"><?php echo e(__('Nov')); ?></option>
                            <option value="12"><?php echo e(__('Dec')); ?></option>
                        </select>
                        <label class="focus-label"><?php echo e(__('Select Month')); ?></label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="input-block mb-3 form-focus select-focus">
                        <select name="year" x-model="year" class="select floating"> 
                            <option value=""> - </option>
                            <?php $__currentLoopData = $years_range; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option><?php echo e($year->year); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <label class="focus-label"><?php echo e(__('Select Year')); ?></label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="row">
                        <div class="col-6">
                            <a href="<?php echo e(route('attendances.index')); ?>" class="btn btn-dark w-100">Reset</a>
                        </div>  
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100"><?php echo e(__('Search')); ?></button>
                        </div>
                    </div>
                </div>       
            </div>
        </form> 
        <!-- /Search Filter -->
        
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th class="position-sticky start-0"><?php echo e(__('Employee' . '(' . count($employees) . ')' )); ?></th>
                                <?php for($day = 1; $day <= $days_in_month; $day++): ?>
                                <th><?php echo e($day); ?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($employees)): ?>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="position-sticky start-0">
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <div>    
                                                <?php
                                                    $img = !empty($employee->avatar) ? asset('storage/users/'.$employee->avatar): asset('images/user.jpg');
                                                    $link = route('employees.show', ['employee' => Crypt::encrypt($employee->id)]);
                                                ?> 
                                                <?php echo \Spatie\Menu\Laravel\Html::userAvatar($employee->fullname, $img, $link); ?>

                                            </div>
                                            <div>
                                                <a href="<?php echo e(route('attendance.history', ['employee_id' => encrypt($employee->id)])); ?>" title="View <?php echo e($employee->fullname); ?>'s Attendance History"><i class="fa-solid fa-clock-rotate-left"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <?php for($day = 1; $day <= $days_in_month; $day++): ?>
                                        <?php
                                            $currentMonth = request()->month ?? now()->month;
                                            $year = request()->year ?? now()->year;
                                            $attendance = $employee->attendances()
                                                    ->whereDay('created_at', $day)
                                                    ->whereMonth('created_at', $currentMonth)
                                                    ->whereYear('created_at', $year)
                                                    ->first();
                                        ?>
                                        <?php if(!empty($attendance->startDate) && !empty($attendance->endDate)): ?>
                                        <td><a href="javascript:void(0);" data-ajax-modal="true" data-title="<?php echo e(__('Attendance Details')); ?>" data-size="lg" data-url="<?php echo e(route('attendance.details', $attendance->id)); ?>"><i class="fa-solid fa-check text-success"></i></a></td>
                                        <?php else: ?>
                                        <td><a href="javascript:void(0);"><i class="fa-solid fa-close text-danger"></i></a></td>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php echo e($employees->links()); ?>

            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('page-scripts'); ?>
    <!-- Page Js -->
    <!-- /Page Js -->
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/attendances/index.blade.php ENDPATH**/ ?>