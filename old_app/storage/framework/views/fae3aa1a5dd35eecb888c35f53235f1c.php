<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- /Header -->
    <!-- Sidebar -->
    
    <?php if(!request()->is('onboarding/welcome/*') && !request()->is('onboarding') && !request()->is('onboarding/start/*')): ?> 

        <?php if (! empty(trim($__env->yieldContent('sidebar')))): ?>
            <?php echo $__env->yieldContent('sidebar'); ?>
        <?php else: ?>
            <?php echo $__env->make('partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

    <?php endif; ?> 
    <!-- /Sidebar -->
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div id="loader-wrapper">
            <div id="loader">
                <div class="loader-ellips">
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                </div>
            </div>
        </div>
        <!-- Page Content -->
        <?php echo $__env->yieldContent('page-content'); ?>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    <!-- Delete Modal -->
    <div class="modal custom-modal fade" id="GeneralDeleteModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3 class="modal_title">Delete</h3>
                        <p class="modal_message">Are you sure want to delete?</p>
                    </div>
                    <form method="post">
                        <?php echo method_field('DELETE'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="modal-btn delete-action">
                            <input type="hidden" name="id">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-bs-dismiss="modal"
                                        class="btn btn-primary cancel-btn"><?php echo e(__('Cancel')); ?></a>
                                </div>
                                <div class="col-6">
                                    <button type="submit"
                                        class="btn btn-primary continue-btn w-100"><?php echo e(__('Delete')); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/layouts/app.blade.php ENDPATH**/ ?>