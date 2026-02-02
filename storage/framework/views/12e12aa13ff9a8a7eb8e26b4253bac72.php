<?php $__env->startPush('page-styles'); ?>

<?php $__env->startSection('page-content'); ?>
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
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Holidays')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Holidays')); ?>

                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-holiday')): ?>
                    <a data-url="<?php echo e(route('holidays.create')); ?>" href="javascript:void(0)" class="btn add-btn"
                        data-ajax-modal="true" data-size="lg" data-title="Add Holiday">
                        <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Holiday')); ?>

                    </a>
                    <?php endif; ?>

                    <div class="view-icons">
                        <a href="<?php echo e(route('holidays.index')); ?>" data-bs-toggle="tooltip" data-bs-title="<?php echo e(__("Holidays List")); ?>" class="list-view btn btn-link"><i class="fa-solid fa-bars"></i></a>
                        <a href="<?php echo e(route('holidays.calendar')); ?>" data-bs-toggle="tooltip" data-bs-title="<?php echo e(__("Holidays Calendar")); ?>" class="grid-view btn btn-link active"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
             <?php $__env->endSlot(); ?>
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
            <div class="col-lg-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            
                                <!-- Calendar -->
                                <div id="calendar"></div>
                                <!-- /Calendar -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('page-scripts'); ?>
<script type="module">
    $(document).ready(function(){
        let calendarEl = document.getElementById('calendar');
        let calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
            initialView: 'dayGridMonth',
            height: 'auto',
            expandRows: true,
            eventDisplay: 'block',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridDay,dayGridMonth,dayGridYear'
            },
            events: <?php echo json_encode($events, 15, 512) ?>,
            dateClick: function () {
                $('.add-btn').click();
            }
        });
        calendar.render();
    })
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/holidays/calendar.blade.php ENDPATH**/ ?>