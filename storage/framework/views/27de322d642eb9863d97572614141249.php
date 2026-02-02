

<?php $__env->startSection('page-content'); ?>

<style>
    #tasks-table th:nth-child(3),
    #tasks-table td:nth-child(3) {
        width: 200px !important;  /* Description column */
    }
</style>
    <div class="content container-fluid">

        <!-- Page Header -->
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
             <?php $__env->slot('title', null, []); ?> <?php echo e(activeRole() !== \App\Enums\UserType::EMPLOYEE->value ? "Employee's Latest Work Reports" : 'Work Reports'); ?> <?php $__env->endSlot(); ?>

            <ul class="breadcrumb w-100 justify-content-between">
                <div class="d-flex">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?php echo e(__('Work Reports')); ?>

                    </li>
                </div>

                <?php if(activeRole() !== \App\Enums\UserType::EMPLOYEE->value): ?>
                <div class="mb-3">
                    <select id="filterRange" class="form-select" style="width:220px;">
                        <option value="all">All Reports</option>
                        <option value="today">Today</option>
                        <option value="7days">Last 7 Days</option>
                        <option value="1month">Last 1 Month</option>
                        <option value="1year">Last 1 Year</option>
                    </select>
                </div>
                <?php endif; ?>
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
        

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <?php echo $dataTable->table(['class' => 'table table-striped custom-table w-100']); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('page-scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')([
    "resources/js/datatables.js",
    "resources/assets/css/ckeditor.css",
    "resources/js/ckeditor.js"
]); ?>
<?php echo $dataTable->scripts(attributes: ['type' => 'module']); ?>


<script>

$(document).on('change', '#filterRange', function() {
    var table = $('#tasks-table').DataTable();
    var filterValue = $(this).val(); 

    var currentUrl = table.ajax.url();
    
    // 2. Remove any existing 'filter' parameter to avoid duplicates
    // This is a robust way to ensure only the latest filter is applied
    var url = new URL(currentUrl, window.location.origin);
    url.searchParams.delete('filter'); 
    
    // 3. Add the new 'filter' parameter if it's not 'all'
    if (filterValue !== 'all') {
        url.searchParams.append('filter', filterValue);
    }
    
    // 4. Set the new AJAX URL and reload the table
    table.ajax.url(url.toString()).load();
});

</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/work-report/index.blade.php ENDPATH**/ ?>