<?php $__env->startPush('page-styles'); ?>
<style>
    .modern-analog-clock {
        width: 10rem;
        height: 10rem;
        border: 6px solid #ddd;
        border-radius: 50%;
        margin: 0 auto;
        position: relative;
        background: #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .numbers span {
        position: absolute;
        left: 46%;
        top: 45%;
        transform-origin: 0 0;
        font-size: 13px;
        font-weight: 600;
        color: #444;

        transform: rotate(calc(var(--i) * 30deg)) 
               translate(0, -60px) 
               rotate(calc(var(--i) * -30deg));
    }

    .hand {
        position: absolute;
        bottom: 50%;
        left: 50%;
        transform-origin: bottom center;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .hand.hour {
        width: 6px;
        height: 32px;
        background: #333;
    }

    .hand.minute {
        width: 4px;
        height: 45px;
        background: #007bff;
    }

    .hand.second {
        width: 2px;
        height: 50px;
        background: #e74c3c;
    }

    .center-dot {
        width: 10px;
        height: 10px;
        background: #333;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

</style>

<?php $__env->stopPush(); ?>

<div>
    <div class="row">
        <div class="col-md-5">
            <div class="card punch-status">
                <div class="card-body">
                    <h5 class="card-title">
                        <div class="row">
                            <div class="col-8">
                                <?php echo e(__('Timesheet')); ?> <small class="text-muted"><?php echo e(format_date(Date('Y-m-d'))); ?></small>
                            </div>
                            <div class="col-4">
                                <span><?php echo e($totalHours); ?> <?php echo e(\Str::plural(__('Hour'), intval($totalHours))); ?></span> 
                            </div>
                        </div>
                    </h5>
                    
                    <div class="punch-info">

                        <div class="modern-analog-clock">
                            <div class="numbers">
                                <span style="--i:0">12</span>
                                <span style="--i:1">1</span>
                                <span style="--i:2">2</span>
                                <span style="--i:3">3</span>
                                <span style="--i:4">4</span>
                                <span style="--i:5">5</span>
                                <span style="--i:6">6</span>
                                <span style="--i:7">7</span>
                                <span style="--i:8">8</span>
                                <span style="--i:9">9</span>
                                <span style="--i:10">10</span>
                                <span style="--i:11">11</span>
                            </div>

                            <div class="hand hour"></div>
                            <div class="hand minute"></div>
                            <div class="hand second"></div>
                            <div class="center-dot"></div>
                        </div>
                    </div>
                    <div class="punch-btn-section">
                        <!--[if BLOCK]><![endif]--><?php if(!empty($clockedIn) && !empty($timeId)): ?>
                        

                        <a href="javascript:void(0)" data-url="<?php echo e(route('clockout-modal', ['timeId' => $timeId])); ?>" data-ajax-modal="true" data-size="lg" data-title="<?php echo e(__('Add Work Report')); ?>" data-timeid="<?php echo e($timeId); ?>" class="btn btn-primary punch-btn"><?php echo e(__('Clock Out')); ?></a> 
                        
                        <?php else: ?>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#clockin_modal" class="btn btn-primary punch-btn"><?php echo e(__('Clock In')); ?></button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="statistics">
                        <div class="row">
                            <?php if(!empty($clockedIn) && !empty($timeStarted)): ?>
                            <div class="col-md-12 text-center">
                                <div class="stats-box">
                                    <p><?php echo e(__('Started At')); ?></p>
                                    <h6><?php echo e(format_date($timeStarted,'H:i:s A')); ?></h6>
                                </div>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card att-statistics">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Statistics')); ?></h5>
                    <div class="stats-list">
                        <div class="stats-info">
                            <p><?php echo e(__('Today')); ?> <strong><small> <?php echo e($totalHoursToday); ?> <?php echo e(\Str::plural(__('Hour'),$totalHoursToday)); ?></small></strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-primary w-<?php echo e($totalHoursToday /100); ?>" role="progressbar" aria-valuenow="<?php echo e($totalHoursToday/100); ?>"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="stats-info">
                            <p><?php echo e(__('This Week ')); ?><strong> <small> <?php echo e($totalHoursThisWeek); ?> <?php echo e(\Str::plural(__('Hour'),$totalHoursThisWeek)); ?></small></strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-warning w-<?php echo e($totalHoursThisWeek/100); ?>" role="progressbar" aria-valuenow="<?php echo e($totalHoursThisWeek/100); ?>"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="stats-info">
                            <p><?php echo e(__('This Month')); ?> <strong> <small> <?php echo e($totalHoursThisMonth); ?> <?php echo e(\Str::plural(__('Hour'),$totalHoursToday)); ?></small></strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-success w-<?php echo e($totalHoursThisMonth /100); ?>" role="progressbar" aria-valuenow="<?php echo e($totalHoursThisMonth /100); ?>"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card recent-activity">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Today Activity')); ?></h5>
                    <ul class="res-activity-list">
                        <!--[if BLOCK]><![endif]--><?php if(!empty($todayActivity)): ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $todayActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <p class="mb-0"><?php echo e(__('Punch In at')); ?></p>
                                <p class="res-activity-time">
                                    <i class="fa-regular fa-clock"></i>
                                    <?php echo e(!empty($item->startTime) ? $item->startTime->format('H:i A'): ''); ?>

                                </p>
                            </li>
                            <!--[if BLOCK]><![endif]--><?php if(!empty($item->endTime)): ?>
                            <li>
                                <p class="mb-0"><?php echo e(__('Punch Out at')); ?></p>
                                <p class="res-activity-time">
                                    <i class="fa-regular fa-clock"></i>
                                    <?php echo e(!empty($item->endTime) ? $item->endTime->format('H:i A'): ''); ?>

                                </p>
                            </li>
                            <hr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo e(__('Date')); ?> </th>
                            <th><?php echo e(__('Punch In')); ?></th>
                            <th><?php echo e(__('Punch Out')); ?></th>
                            <th><?php echo e(__('Total Hours')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!--[if BLOCK]><![endif]--><?php if(!empty($attendances)): ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $records): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    // 1. Calculate total minutes for the day
                                    $totalMinutes = 0;
                                    foreach ($records as $r) {
                                        $totalMinutes += ($r['totalHours'] * 60) + $r['totalMinutes'];
                                    }
                                    $hours = floor($totalMinutes / 60);
                                    $minutes = $totalMinutes % 60;

                                    $startTimes = array_column($records, 'created_at'); // Extract all 'created_at' values
                                    $endTimes   = array_column($records, 'endTime');     // Extract all 'endTime' values

                                    $punchIn  = !empty($startTimes) ? min($startTimes) : null;
                                    $punchOut = !empty($endTimes) ? max($endTimes) : null;

                                    $recordDate = \Carbon\Carbon::parse($date);
                                ?>
        
                            <tr>
                                
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e(format_date($date)); ?></td>
                                
                                <td><?php echo e($punchIn ? \Carbon\Carbon::parse($punchIn)->format('h:i A') : ''); ?></td>
                                
                                <td>

                                    <!--[if BLOCK]><![endif]--><?php if(empty($punchOut) && $recordDate->lt(now()->startOfDay())): ?>
                                        <span class="text-danger">Miss Out</span>
                                    <?php else: ?>
                                        <?php echo e($punchOut ? \Carbon\Carbon::parse($punchOut)->format('h:i A') : ''); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    
                                </td>
                                
                                <td><?php echo e(sprintf('%02d:%02d', $hours, $minutes)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal custom-modal fade" id="clockin_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form wire:submit.prevent="clockin" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div x-data="{forProject: false}">
                    <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <div class="status-toggle">
                        <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('For Project ?')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'checkbox','id' => 'forProject','class' => 'check','@click' => 'forProject =! forProject','name' => 'forProject','wire:model' => 'forProject']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'checkbox','id' => 'forProject','class' => 'check','@click' => 'forProject =! forProject','name' => 'forProject','wire:model' => 'forProject']); ?>
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
                        <label for="forProject" class="checktoggle">checkbox</label>
                    </div>
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
                    <div x-show="forProject">
                        <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['required' => true]); ?><?php echo e(__('Project')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                            <select class="form-control" name="project" wire:model="project">
                                <option value=""><?php echo e(__('Select Project')); ?></option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = \Modules\Project\Models\Project::get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($project->id); ?>"><?php echo e($project->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
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
                <div class="submit-section mb-3">
                    <?php if (isset($component)) { $__componentOriginal8a31ff0802d1df0c26bb607f30439b3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a31ff0802d1df0c26bb607f30439b3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.button','data' => ['type' => 'submit','class' => 'btn btn-primary submit-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'btn btn-primary submit-btn']); ?><?php echo e(__('Start')); ?> <?php echo $__env->renderComponent(); ?>
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
        </div>
    </div>
      
        <?php
        $__scriptKey = '2062473177-0';
        ob_start();
    ?>
        <script defer type="module">
            document.addEventListener('livewire:initialized', () => {
                Livewire.dispatch('refreshAttendance')
                Livewire.dispatch('fetchStatistics')
                Livewire.dispatch('IsClockedIn')
            })

            Livewire.on('Notification', (param) => {
                Toastify({
                    text: param,
                    className: "success",
                }).showToast()
            })

            /* -----------------------------
                   ANALOG CLOCK
                ------------------------------*/
                function updateModernClock() {
                    let now = new Date();

                    let seconds = now.getSeconds();
                    let minutes = now.getMinutes();
                    let hours   = now.getHours();

                    document.querySelector(".hand.hour").style.transform =
                        `translateX(-50%) rotate(${hours * 30 + minutes / 2}deg)`;

                    document.querySelector(".hand.minute").style.transform =
                        `translateX(-50%) rotate(${minutes * 6}deg)`;

                    document.querySelector(".hand.second").style.transform =
                        `translateX(-50%) rotate(${seconds * 6}deg)`;
                }

                setInterval(updateModernClock, 1000);
                updateModernClock();


                /* -----------------------------
                   LIVE TOTAL HOURS COUNTER
                ------------------------------*/
                let startedAt = "<?php echo e($timeStarted); ?>"; // From backend
                if (startedAt) {

                    function updateWorkedHours() {
                        let start = new Date(startedAt);
                        let now   = new Date();

                        let diffMs = now - start;
                        let diffMins = Math.floor(diffMs / 60000);
                        let diffHours = Math.floor(diffMins / 60);
                        let mins = diffMins % 60;

                        document.getElementById("totalHoursRunningLive").textContent =
                            `${diffHours}h : ${String(mins).padStart(2, "0")}m Worked`;
                    }

                    updateWorkedHours();
                    setInterval(updateWorkedHours, 60000);
                }
        </script>

        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>

</div>
<?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/livewire/employee-attendance.blade.php ENDPATH**/ ?>