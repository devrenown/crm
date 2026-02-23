<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('pages.front.blocks.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.features', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.pricing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.how-it-works', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.counter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.security', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.testimony', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.faq', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.contact', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('pages.front.blocks.demo-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn">&#8679;</button>

<?php $__env->stopSection(); ?>

<!-- =================  For styles =============== -->
<?php $__env->startPush('styles'); ?>

 <style>
    .text-primary {
        color: #1A194A !important;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1);       
    }

    .modal button.btn-close {
        background: none;
    }
 </style>

<?php $__env->stopPush(); ?>

<!-- =================  For scripts ===============  -->
<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make(FRONT_LAYOUT_PATH, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/pages/front/index.blade.php ENDPATH**/ ?>