

<?php $__env->startSection('page-content'); ?>
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
         <?php $__env->slot('title', null, []); ?> New Leave Request <?php $__env->endSlot(); ?>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('leaves.index')); ?>">Leave Requests</a>
            </li>
            <li class="breadcrumb-item active">
                New
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

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10 col-md-12">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Apply Leave</h5>
                </div>

                <div class="card-body">

                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form id="leave-create-form"
                          action="<?php echo e(route('leaves.store')); ?>"
                          method="POST"
                          enctype="multipart/form-data">

                        <?php echo csrf_field(); ?>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Leave Type</label>
                            <select name="leave_type_id" class="form-select" required>
                                <option value="">Select leave type</option>
                                <?php $__currentLoopData = $leaveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>"
                                            data-requires-document="<?php echo e($type->requires_document ? 1 : 0); ?>"
                                            data-max-days="<?php echo e($type->max_days_per_application ?? 0); ?>"
                                            <?php echo e(old('leave_type_id') == $type->id ? 'selected' : ''); ?>>
                                        <?php echo e($type->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['leave_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date"
                                       name="start_date"
                                       class="form-control"
                                       value="<?php echo e(old('start_date')); ?>"
                                       required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date"
                                       name="end_date"
                                       class="form-control"
                                       value="<?php echo e(old('end_date')); ?>">
                                <small class="text-muted d-block">
                                    Leave blank for single day
                                </small>
                            </div>
                        </div>

                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Leave Terms (Per Day)
                            </label>

                            <div id="term_details_container"
                                 class="border rounded p-2 bg-light">
                                <small class="text-muted">
                                    Select leave type for each date
                                </small>
                            </div>
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason</label>
                            <textarea name="reason"
                                      class="form-control"
                                      rows="3"
                                      required><?php echo e(old('reason')); ?></textarea>
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Attach Document</label>
                            <input type="file"
                                   name="document"
                                   class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted d-block">
                                Required only if leave type mandates document
                            </small>
                        </div>

                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('leaves.index')); ?>"
                               class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn btn-primary px-4">
                                Apply Leave
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('page-scripts'); ?>
<script>
(function() {
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    const leaveTypeSelect = document.querySelector('[name="leave_type_id"]');
    const documentInput = document.querySelector('[name="document"]');
    const container = document.getElementById('term_details_container');

    function generateTermFields() {
        container.innerHTML = '';

        const startDate = startDateInput.value;
        const endDate = endDateInput.value || startDate;
        if (!startDate) return;

        if (new Date(endDate) < new Date(startDate)) {
            alert('End date cannot be before start date');
            endDateInput.value = '';
            return;
        }

        const start = new Date(startDate);
        const end = new Date(endDate);
        let index = 0;

        const maxDays = parseInt(leaveTypeSelect.selectedOptions[0]?.dataset.maxDays || 0);
        const totalDays = Math.floor((end - start) / (1000*60*60*24)) + 1;
        if(maxDays && totalDays > maxDays){
            alert(`Maximum ${maxDays} day(s) allowed for this leave type.`);
            endDateInput.value = startDate;
            return;
        }

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dayStr = d.toISOString().split('T')[0];

            const row = document.createElement('div');
            row.className = 'row g-2 align-items-end mb-3';

            row.innerHTML = `
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-0">Date</label>
                    <div class="form-control-plaintext">${dayStr}</div>
                    <input type="hidden" name="term_details[${index}][date]" value="${dayStr}">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Term</label>
                    <select name="term_details[${index}][term]"
                            class="form-select day-term" required>
                        <option value="Fullday">Full Day</option>
                        <option value="Halfday">Half Day</option>
                        <option value="Shortleave">Short Leave</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 halfday-box d-none">
                    <label class="form-label fw-semibold">Half Day Type</label>
                    <select name="term_details[${index}][half_day_type]"
                            class="form-select">
                        <option value="First">First Half</option>
                        <option value="Second">Second Half</option>
                    </select>
                </div>

                <div class="col-12 col-md-4 shortleave-box d-none">
                    <label class="form-label fw-semibold">Short Leave Hours</label>
                    <input type="number"
                        name="term_details[${index}][short_leave_hours]"
                        class="form-control"
                        min="0.25"
                        step="0.25"
                        placeholder="e.g. 1.5">
                </div>
            `;

            index++;
            container.appendChild(row);
        }

        attachChangeListeners();
    }

    function attachChangeListeners() {
        document.querySelectorAll('.day-term').forEach(select => {
            toggleTermFields(select);
            select.addEventListener('change', () => toggleTermFields(select));
        });
    }

    function toggleTermFields(select) {
        const row = select.closest('.row');
        const halfDayBox = row.querySelector('.halfday-box');
        const shortLeaveBox = row.querySelector('.shortleave-box');

        // Reset required
        halfDayBox.classList.add('d-none');
        shortLeaveBox.classList.add('d-none');
        halfDayBox.querySelector('select').required = false;
        shortLeaveBox.querySelector('input').required = false;

        if (select.value === 'Halfday') {
            halfDayBox.classList.remove('d-none');
            halfDayBox.querySelector('select').required = true;
        }

        if (select.value === 'Shortleave') {
            shortLeaveBox.classList.remove('d-none');
            shortLeaveBox.querySelector('input').required = true;
        }
    }

    // Document required based on leave type
    leaveTypeSelect.addEventListener('change', function () {
        const requiresDoc = this.selectedOptions[0]?.dataset.requiresDocument == 1;
        documentInput.required = requiresDoc;
        generateTermFields();
    });

    startDateInput.addEventListener('change', generateTermFields);
    endDateInput.addEventListener('change', generateTermFields);

    if (startDateInput.value) generateTermFields();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/leaves/create.blade.php ENDPATH**/ ?>