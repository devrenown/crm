<?php use \Illuminate\Support\Facades\Vite; ?>
<!DOCTYPE html>
<html lang="<?php echo e(!empty(LocaleSettings('lang')) ? LocaleSettings('lang') : 'en'); ?>"
    data-layout="<?php echo e(!empty(Theme('layout')) ? Theme('layout') : 'vertical'); ?>"
    data-layout-mode="<?php echo e(!empty(Theme('color_scheme')) ? Theme('color_scheme') : 'orange'); ?>"
    data-layout-width="<?php echo e(!empty(Theme('layout_width')) ? Theme('layout_width') : 'fluid'); ?>"
    data-layout-position="<?php echo e(!empty(Theme('layout_position')) ? Theme('layout_position') : 'fluid'); ?>"
    data-topbar="<?php echo e(!empty(Theme('topbar_color')) ? Theme('topbar_color') : 'default'); ?>" 
    data-layout-style="<?php echo e(!empty(Theme('sidebar_view')) ? Theme('sidebar_view') : 'default'); ?>"
    data-sidebar="<?php echo e(!empty(Theme('sidebar_color')) ? Theme('sidebar_color') : 'dark'); ?>"
    data-sidebar-size="<?php echo e(!empty(Theme('sidebar_size')) ? Theme('sidebar_size'): 'lg'); ?>" 
    data-sidebar-image="<?php echo e(asset('assets/img/laptop.png')); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="csrf-param" content="_token" />
    <title><?php echo e($pageTitle ?? ''); ?> - <?php echo e(!empty(Theme('name')) ? Theme('name') : config('app.name')); ?></title>
    <?php echo $__env->make('partials.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
</head>

<body <?php if(isset($bodyClass)): ?> class="<?php echo e($bodyClass); ?>" <?php endif; ?>>
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <?php echo $__env->yieldContent('content'); ?>
        
    </div>

    <!-- jQuery (must be first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Moment.js (required by datetimepicker) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <!-- /Main Wrapper -->
    <?php echo $__env->make('partials.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('modals'); ?>
    
</body>

</html>
<?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/layouts/blank.blade.php ENDPATH**/ ?>