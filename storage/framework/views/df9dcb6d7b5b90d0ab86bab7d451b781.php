<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="card punch-status">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Timesheet')); ?> <small class="text-muted"><?php echo e($attendance->startDate); ?></small></h5>
                    <?php if(!empty($attendance->created_at)): ?>
                    <div class="punch-det">
                        <h6><?php echo e(__('First Punchedd In At')); ?></h6>
                        <p><?php echo e($attendance->created_at->format('Y-m-d H:i:s A')); ?></p>
                    </div>
                    <?php endif; ?>
                    <div class="punch-info">
                        <div class="punch-hours">
                            <span><?php echo e($totalHours); ?> <?php echo e(\Str::plural(__('Hour'), intval($totalHours))); ?></span>
                        </div>
                    </div>
                    <?php if(!empty($attendance->updated_at)): ?>
                    <div class="punch-det">
                        <h6><?php echo e(__('Last Punch In At')); ?></h6>
                        <p><?php echo e($attendance->updated_at->format('Y-m-d H:i:s A')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card recent-activity">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Activity')); ?></h5>
                    <ul class="res-activity-list">
                        <?php if(!empty($attendanceActivity)): ?>
                            <?php $__currentLoopData = $attendanceActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <p class="mb-0"><?php echo e(__('Punch In at')); ?></p>
                                <p class="res-activity-time">
                                    <i class="fa-regular fa-clock"></i>
                                    <?php echo e(!empty($item->startTime) ? $item->startTime->format('H:i A'): ''); ?>

                                </p>
                            </li>
                            <?php if(!empty($item->endTime)): ?>
                            <li>
                                <p class="mb-0"><?php echo e(__('Punch Out at')); ?></p>
                                <p class="res-activity-time">
                                    <i class="fa-regular fa-clock"></i>
                                    <?php echo e(!empty($item->endTime) ? $item->endTime->format('H:i A'): ''); ?>

                                </p>
                            </li>
                            <hr>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/attendances/attendance-details.blade.php ENDPATH**/ ?>