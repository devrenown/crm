<?php $__env->startPush('page-styles'); ?>
<style>
    .clock {
      background:-webkit-linear-gradient(top, #f9f9f9,#f9f9f9);
      background:-moz-linear-gradient(top, #f9f9f9,#f9f9f9);
      background:linear-gradient(to bottom, #f9f9f9,#f9f9f9);
      width: 13rem;
      height: 13rem;
      margin: 1rem auto;
      border-radius: 50%;
      border: 6px solid #ffffff;
      position: relative;
      box-shadow: 0 1vw 3vw -1vw rgba(0,0,0,0.8);
    }

    /* center pin */
    .dot {
      width: 13px;
      height: 13px;
      background: #CCCCCC;
      border-radius: 50%;
      position: absolute;
      inset: 0;
      margin: auto;
      z-index: 20;
      box-shadow: 0 2px 4px -1px black;
    }

    /* =====================
       HANDS — TRUE CENTER
    ===================== */
    .hour-hand,
    .minute-hand,
    .second-hand {
      position: absolute;
      left: 50%;
      bottom: 50%;
      transform-origin: bottom center;
      will-change: transform;
    }

    .hour-hand {
      width: .3rem;
      height: 2.5rem;
      border-radius: 0 0 .9em .9em;
      background: #232425;
      box-shadow: #232425 0 0 2px;
      transform-origin: 0.1em 2.9em;
      z-index: 6;
    }

    .hour-hand:before {
        content: '';
        background: inherit;
        width: .8em;
        height: .5em;
        border-radius: 0 0 .8em .8em;
        box-shadow: #232425 0 0 1px;
        position: absolute;
        top: -.3em;
        left: -.25em;
        z-index: 6;
    }

    .hour-hand:after {
        content: '';
        width: 0;
        height: 0;
        border: .9em solid #232425;
        border-width: 0 .4em 1em .5em;
        border-left-color: transparent;
        border-right-color: transparent;
        position: absolute;
        top: -1.2em;
        left: -.3em;
        z-index: 6;
    }

    .minute-hand {
        width: .35em;
        height: 4.79em;
        border-radius: .5em;
        background: #343536;
        box-shadow: #343536 0 0 2px;
        transform-origin: 0.1em 4.6em;
        z-index: 7;
    }

    .second-hand {
      width: .2em;
      height: 7.5em;
      border-radius: .1em .1em 0 0 / 10em 10em 0 0;
      /*background: #c00;*/
      background: var(--bs-primary);
      margin: 0 0 -2em -.1em;
      box-shadow: rgba(0, 0, 0, .8) 0 0 .2em;
      transform-origin: 0.1em 5.5em;
      z-index: 8;
    }

    .second-hand:after {
        content: '';
        width: .4em;
        height: .4em;
        border-radius: .7em;
        background: inherit;
        position: absolute;
        left: -.15em;
        bottom: 1.7em;
    }

    .second-hand:before {
        content: '';
        width: .5em;
        height: 1.1em;
        border-radius: .2em .2em .4em .4em/.2em .2em 2em 2em;
        box-shadow: rgba(0, 0, 0, .8) 0 0 .2em;
        background: inherit;
        position: absolute;
        left: -.15em;
        bottom: -1em;
    }

    /* =====================
       NUMBERS — PERFECT RADIAL
    ===================== */

    .clock .numbers {
      position: absolute;
      inset: 0;
      font-family: emoji;
    }

    .clock .numbers span {
      position: absolute;
      left: 50%;
      top: 50%;
      font-size: 16px;
      font-weight: 600;
      color: #222;
      transform:
        translate(-50%, -50%)
        rotate(calc(var(--i) * 30deg))
        translateY(-5.3rem)
        rotate(calc(var(--i) * -30deg));
      transform-origin: center;
    }

    .clock .numbers span:nth-child(n+10) {
      font-size: 16px;
    }


    /* =====================
       DATE & DAY — SAFE ZONE
    ===================== */
    .clock .info {
      position: absolute;
      width: 80px;
      height: 22px;
      background: #e3e3e3;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 600;
      text-align: center;
      line-height: 22px;
      color: #111;
      left: 50%;
      transform: translateX(-50%);
      z-index: 4;
    }

    .clock .date {
      top: 40px;
    }

    .clock .day {
      bottom: 40px;
    }

    .card {
        height: 22rem;
        max-height: 22rem;
    }

</style>

<?php $__env->stopPush(); ?>

<div>
    <div class="row">
        <div class="col-md-4">
            <div class="card punch-status">
                <div class="card-body">
                    <h5 class="card-title">
                        <div class="text-center">
                           
                            <?php echo e(__('Timesheet')); ?> <small class="text-muted"><?php echo e(format_date(Date('Y-m-d'))); ?></small>
                        </div>
                    </h5>
                    
                    <div class="punch-info">

                        <div class="clock">
                          <div>
                            <div class="info date"></div>
                            <div class="info day"></div>
                          </div>
                          <div class="dot"></div>
                          <div>
                            <div class="hour-hand"></div>
                            <div class="minute-hand"></div>
                            <div class="second-hand"></div>
                          </div>

                          <div class="numbers">
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
                            <span style="--i:12">12</span>
                          </div>
                          <!-- <div class="diallines"></div> -->
                        </div>
                    </div>
                    <div class="punch-btn-section d-flex align-items-center gap-2 justify-content-between">
                        <!--[if BLOCK]><![endif]--><?php if(!empty($clockedIn) && !empty($timeId)): ?>
                        <a href="javascript:void(0)" data-url="<?php echo e(route('clockout-modal', ['timeId' => $timeId])); ?>" data-ajax-modal="true" data-size="lg" data-title="<?php echo e(__('Add Work Report')); ?>" data-timeid="<?php echo e($timeId); ?>" class="btn btn-primary punch-btn"><i class="fa-solid fa-right-from-bracket me-1"></i> <?php echo e(__('Clock Out')); ?></a> 
                        
                        <?php else: ?>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#clockin_modal" class="btn btn-primary punch-btn"><i class="fa-solid fa-right-to-bracket me-1"></i> <?php echo e(__('Clock In')); ?></button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <div class="text-center">
                            <span><?php echo e($totalHours); ?> <?php echo e(\Str::plural(__('Hour'), intval($totalHours))); ?></span> 
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card att-statistics">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e(__('Statistics')); ?></h5>

                    <?php
                      $minHoursInDay = 9;
                      $minHoursInWeek = 54;
                      $minHoursInMonth = 270;
                    ?>

                    <div class="stats-list">

                        <div class="stats-info">
                            <p>
                                <?php echo e(__('Today')); ?>

                                <strong>
                                    <small>
                                        <?php echo e($totalHoursToday); ?>/<?php echo e($minHoursInDay); ?> Hours
                                    </small>
                                </strong>
                            </p>

                            <?php
                                $todayHoursOnly = (int) explode(':', $totalHoursToday)[0];
                                $dayPercent = min(($todayHoursOnly / $minHoursInDay) * 100, 100);
                            ?>

                            <div class="progress">
                                <div class="progress-bar bg-primary"
                                     role="progressbar"
                                     aria-valuenow="<?php echo e($todayHoursOnly); ?>"
                                     aria-valuemin="<?php echo e($dayPercent); ?>"
                                     aria-valuemax="100" 
                                     style="width: <?php echo e($dayPercent); ?>%;">
                                </div>
                            </div>
                        </div>

                        <div class="stats-info">
                            <p>
                                <?php echo e(__('This Week')); ?>

                                <strong>
                                    <small>
                                        <?php echo e($totalHoursThisWeek); ?>/<?php echo e($minHoursInWeek); ?> Hours
                                    </small>
                                </strong>
                            </p>

                            <?php
                                $weekHoursOnly = (int) explode(':', $totalHoursThisWeek)[0];
                                $weekPercent = min(($weekHoursOnly / $minHoursInWeek) * 100, 100);
                            ?>

                            <div class="progress">
                                <div class="progress-bar bg-warning"
                                     role="progressbar"
                                     aria-valuenow="<?php echo e($weekHoursOnly); ?>"
                                     aria-valuemin="<?php echo e($weekPercent); ?>"
                                     aria-valuemax="100" 
                                     style="width: <?php echo e($weekPercent); ?>%;">
                                </div>
                            </div>
                        </div>

                        <div class="stats-info">
                            <p>
                                <?php echo e(__('This Month')); ?>

                                <strong>
                                    <small>
                                        <?php echo e($totalHoursThisMonth); ?>/<?php echo e($minHoursInMonth); ?> Hours
                                    </small>
                                </strong>
                            </p>

                            <?php
                                $monthHoursOnly = (int) explode(':', $totalHoursThisMonth)[0];
                                $weekPercent = min(($monthHoursOnly / $minHoursInMonth) * 100, 100);
                            ?>

                            <div class="progress">
                                <div class="progress-bar bg-success"
                                     role="progressbar"
                                     aria-valuenow="<?php echo e($monthHoursOnly); ?>"
                                     aria-valuemin="<?php echo e($weekPercent); ?>"
                                     aria-valuemax="100" 
                                     style="width: <?php echo e($weekPercent); ?>%;">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
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

    <script>
        /* -----------------------------
           ANALOG CLOCK (FIXED)
        ------------------------------ */

        const clockEl = document.querySelector('.clock');

        const weekday = [
            "Sunday","Monday","Tuesday",
            "Wednesday","Thursday","Friday","Saturday"
        ];

        /* CLOCK FUNCTION */
        function clock() {

          const d   = new Date();
          const h   = d.getHours();
          const m   = d.getMinutes();
          const s   = d.getSeconds();
          const ms  = d.getMilliseconds();

          const date    = d.getDate();
          let month     = d.getMonth() + 1;
          const year    = d.getFullYear();

          /* CALCULATE DEGREES */
           const sDeg = (s + ms / 1000) * 6;          // smooth sweep
           const mDeg = (m + s / 60) * 6;
           const hDeg = (h % 12 + m / 60) * 30;

          /* ELEMENTS */
          const hEl = document.querySelector('.hour-hand');
          const mEl = document.querySelector('.minute-hand');
          const sEl = document.querySelector('.second-hand');
          const dateEl = document.querySelector('.date');
          const dayEl = document.querySelector('.day');

          /* APPLY TRANSFORMS (DO NOT OVERWRITE CSS) */
          hEl.style.transform = `translateX(-50%) rotate(${hDeg}deg)`;
          mEl.style.transform = `translateX(-50%) rotate(${mDeg}deg)`;
          sEl.style.transform = `translateX(-50%) rotate(${sDeg}deg)`;

          /* DATE & DAY */
          if (month < 10) month = "0" + month;
          dateEl.innerHTML = `${date}/${month}/${year}`;
          dayEl.innerHTML = weekday[d.getDay()];

          requestAnimationFrame(clock);
        }

        requestAnimationFrame(clock);
    </script>
      
        <?php
        $__scriptKey = '3697181574-0';
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
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/livewire/employee-attendance.blade.php ENDPATH**/ ?>