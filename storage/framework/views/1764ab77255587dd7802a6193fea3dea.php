

<?php $__env->startSection('page-content'); ?>
<?php
    $user = auth()->user();
?>

<div class="content container-fluid">

    
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
         <?php $__env->slot('title', null, []); ?> <?php echo e(__('Leave Details')); ?> <?php $__env->endSlot(); ?>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('leaves.index')); ?>"><?php echo e(__('Leave Requests')); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo e(__('View Leave')); ?></li>
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

    
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-leave-summary')): ?>
    <div class="row g-3 mb-4">
        
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

            </div>
        <?php endif; ?>


    
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo e(__('Leave Request Information')); ?></h4>
        </div>

        <div class="card-body">

            <?php
                $termDetails = is_string($leave->term_details)
                    ? json_decode($leave->term_details, true)
                    : ($leave->term_details ?? []);
            ?>

            
            <?php $__currentLoopData = [
                'Employee' => $leave->user->full_name ?? null,
                'Leave Type' => $leave->leaveType->name ?? null,
                'From Date' => optional($leave->start_date)?->format('d M Y'),
                'To Date' => optional($leave->end_date)?->format('d M Y'),
                'Applied Days' => $leave->days,
                'Approved Days' => $leave->approved_days,
                
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($value): ?>
                    <div class="row mb-3">
                        <label class="col-md-3 fw-bold"><?php echo e($label); ?></label>
                        <div class="col-md-9"><?php echo e($value); ?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(!empty($termDetails)): ?>
            <hr>
            <h5 class="fw-bold mb-3">Leave Term Details</h5>

            <?php $__currentLoopData = $termDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border rounded p-3 mb-3">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Date</div>
                        <div class="col-md-9">
                            <?php echo e(\Carbon\Carbon::parse($term['date'])->format('d M Y')); ?>

                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Term</div>
                        <div class="col-md-9"><?php echo e($term['term']); ?></div>
                    </div>

                    <?php if($term['term'] === 'Halfday'): ?>
                        <div class="row mb-2">
                            <div class="col-md-3 fw-bold">Half Day Type</div>
                            <div class="col-md-9"><?php echo e($term['half_day_type']); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($term['short_leave_hours'])): ?>
                        <div class="row mb-2">
                            <div class="col-md-3 fw-bold">Short Leave Hours</div>
                            <div class="col-md-9"><?php echo e($term['short_leave_hours']); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        
            
            <div class="row mb-3">
                <label class="col-md-3 fw-bold">Final Status</label>
                <div class="col-md-9">
                    <span class="badge
                        bg-<?php echo e(match($leave->final_status) {
                            'Approved' => 'success',
                            'Partially Approved' => 'info',
                            'Rejected' => 'danger',
                            'Cancelled' => 'warning',
                            default => 'secondary'
                        }); ?>">
                        <?php echo e($leave->final_status); ?>

                    </span>
                </div>
            </div>


            <?php if(!empty($leave->reason)): ?>
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Reason</label>
                    <div class="col-md-9">
                        <?php echo e($leave->reason); ?>

                    </div>
                </div>
            <?php endif; ?>

            
            <?php if($leave->document_path): ?>
            <div class="row mb-3">
                <label class="col-md-3 fw-bold">Document</label>
                <div class="col-md-9">
                    <button class="btn btn-outline-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#leaveDocumentModal">
                        <i class="bi bi-file-earmark-text"></i> View Document
                    </button>
                </div>
            </div>
            <?php endif; ?>


            
            <?php if($leave->remarks): ?>
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Admin Comment</label>
                    <div class="col-md-9"><?php echo e($leave->remarks); ?></div>
                </div>
            <?php endif; ?>

            
            <hr>
            <h5 class="mb-3">Approval Flow</h5>

            
            <?php if($leave->levelStatus('L1')): ?>
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Level 1</label>
                    <div class="col-md-9">
                        <span class="badge bg-<?php echo e($leave->levelStatus('L1') === 'Approved' ? 'success' :
                            ($leave->levelStatus('L1') === 'Rejected' ? 'danger' : 'warning')); ?>">
                            <?php echo e($leave->levelStatus('L1')); ?>

                        </span>

                        <?php if($leave->approved_level_1_on): ?>
                            <div class="small mt-1">
                                By <?php echo e(optional($leave->level1Approver)->full_name); ?>

                                on <?php echo e($leave->approved_level_1_on->format('d M Y H:i')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($leave->approved_level_1_remark): ?>
                            <div class="text-muted mt-1">
                                <strong>Remark:</strong> <?php echo e($leave->approved_level_1_remark); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($leave->rejection_reason_l1): ?>
                            <div class="text-danger mt-1"><?php echo e($leave->rejection_reason_l1); ?></div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endif; ?>

            
            <?php if($leave->levelStatus('L2')): ?>
                <div class="row mb-3">
                    <label class="col-md-3 fw-bold">Level 2</label>
                    <div class="col-md-9">
                        <span class="badge bg-<?php echo e($leave->levelStatus('L2') === 'Approved' ? 'success' :
                            ($leave->levelStatus('L2') === 'Rejected' ? 'danger' : 'warning')); ?>">
                            <?php echo e($leave->levelStatus('L2')); ?>

                        </span>

                        <?php if($leave->approved_level_2_on): ?>
                            <div class="small mt-1">
                                By <?php echo e(optional($leave->level2Approver)->full_name); ?>

                                on <?php echo e($leave->approved_level_2_on->format('d M Y H:i')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($leave->approved_level_2_remark): ?>
                            <div class="text-muted mt-1">
                                <strong>Remark:</strong> <?php echo e($leave->approved_level_2_remark); ?>

                            </div>
                        <?php endif; ?>

                        <?php if($leave->rejection_reason_l2): ?>
                            <div class="text-danger mt-1"><?php echo e($leave->rejection_reason_l2); ?></div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endif; ?>

            
            <div class="mt-4">
                <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>

        </div>
    </div>
</div>
<?php
$url = URL::temporarySignedRoute(
    'leaves.document.view',
    now()->addMinutes(2),
    ['leave' => $leave->id]
);
?>

<?php if($leave->document_path): ?>
<div class="modal fade" id="leaveDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="height:80vh;">
                <iframe
                    src="<?php echo e($url); ?>"
                    style="width:100%; height:100%; border:0;">
                    </iframe>
                <!--Disable Downloade , Print.. -->
                <!-- <iframe
                    src="<?php echo e(route('leaves.document.view', $leave)); ?>#toolbar=0&navpanes=0&scrollbar=0"
                    style="width:100%; height:100%; border:0;">
                </iframe> -->
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/leaves/show.blade.php ENDPATH**/ ?>