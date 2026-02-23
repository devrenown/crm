<!DOCTYPE html>
<html lang="en">
    
<?php echo $__env->make('pages.front.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- =================  For specific pages styles ===============  -->
<?php echo $__env->yieldPushContent('styles'); ?>

<?php echo $__env->make('pages.front.layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('pages.front.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- =================  For specific pages scripts ===============  -->

<?php echo $__env->make('pages.front.layouts.footer-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldPushContent('scripts'); ?>

</html><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/pages/front/layouts/app.blade.php ENDPATH**/ ?>